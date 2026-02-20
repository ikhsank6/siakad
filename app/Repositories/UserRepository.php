<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * The storage disk for user files.
     */
    private const STORAGE_DISK = 'public';

    /**
     * The avatar storage path.
     */
    private const AVATAR_PATH = 'avatars';

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    // ==================== PUBLIC METHODS ====================

    /**
     * Search users with role relation.
     */
    public function searchWithRoles(?string $term, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->with('role', 'roles');

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Create user with roles.
     */
    public function createWithRoles(array $userData, array $roleIds, ?int $defaultRoleId = null): User
    {
        return DB::transaction(function () use ($userData, $roleIds, $defaultRoleId) {
            $userData = $this->handleEmailVerificationOnCreate($userData);

            $password = null;
            if (! isset($userData['password'])) {
                $password = Str::password(16);
                $userData['password'] = Hash::make($password);
            }

            $user = $this->model->create($userData);
            $user->syncRoles($roleIds, $defaultRoleId);

            $this->sendVerificationIfInactive($user, $userData['is_active'] ?? false, $password);

            return $user->fresh(['role', 'roles']);
        });
    }

    /**
     * Update user with roles.
     */
    public function updateWithRoles(int $userId, array $userData, array $roleIds, ?int $defaultRoleId = null): bool
    {
        return DB::transaction(function () use ($userId, $userData, $roleIds, $defaultRoleId) {
            $user = $this->findOrFail($userId);
            $userData = $this->handleEmailVerificationOnUpdate($user, $userData);

            $user->update($userData);
            $user->syncRoles($roleIds, $defaultRoleId);

            $this->sendVerificationIfInactive($user, $userData['is_active'] ?? true);

            return true;
        });
    }

    /**
     * Update user password.
     */
    public function updatePassword(int $userId, string $newPassword): bool
    {
        return $this->update($userId, [
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Update user avatar.
     */
    public function updateAvatar(int $userId, $file): string
    {
        return DB::transaction(function () use ($userId, $file) {
            $user = $this->findOrFail($userId);

            // Delete old media if exists
            if ($user->media_id) {
                app(\App\Repositories\Contracts\MediaRepositoryInterface::class)->deleteMedia($user->media_id);
            }

            // Create new media
            $media = app(\App\Repositories\Contracts\MediaRepositoryInterface::class)->upload($file, self::AVATAR_PATH);

            $user->update([
                'avatar' => $media->filename,
                'media_id' => $media->id,
            ]);

            return $media->filename;
        });
    }

    /**
     * Delete user avatar.
     */
    public function deleteAvatar(int $userId): bool
    {
        return DB::transaction(function () use ($userId) {
            $user = $this->findOrFail($userId);

            if ($user->media_id) {
                app(\App\Repositories\Contracts\MediaRepositoryInterface::class)->deleteMedia($user->media_id);
            }

            return $user->update([
                'avatar' => null,
                'media_id' => null,
            ]);
        });
    }

    /**
     * Set default role for login.
     */
    public function setDefaultRole(int $userId, int $roleId): bool
    {
        return DB::transaction(function () use ($userId, $roleId) {
            $user = $this->findOrFail($userId);

            $this->validateUserHasRole($user, $roleId);

            $user->syncRoles($user->roles->pluck('id')->toArray(), $roleId);

            return true;
        });
    }

    /**
     * Register a new user.
     */
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $defaultRole = $this->getDefaultRole();

            $user = $this->model->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id' => $defaultRole?->id,
                'is_active' => false,
            ]);

            if ($defaultRole) {
                $user->roles()->attach($defaultRole->id, ['is_default' => true]);
            }

            event(new Registered($user));

            return $user;
        });
    }

    /**
     * Set the active role for a user.
     */
    public function setActiveRole(int $userId, int $roleId): bool
    {
        return DB::transaction(function () use ($userId, $roleId) {
            $user = $this->findOrFail($userId);
            $role = Role::findOrFail($roleId);

            return $user->setActiveRole($role);
        });
    }

    // ==================== PRIVATE HELPERS ====================

    /**
     * Handle email_verified_at logic when creating a user.
     */
    private function handleEmailVerificationOnCreate(array $userData): array
    {
        $isActive = $userData['is_active'] ?? false;
        $userData['email_verified_at'] = $isActive ? now() : null;

        return $userData;
    }

    /**
     * Handle email_verified_at logic when updating a user.
     */
    private function handleEmailVerificationOnUpdate(User|\Illuminate\Database\Eloquent\Model $user, array $userData): array
    {
        if (! isset($userData['is_active'])) {
            return $userData;
        }

        $wasActive = $user->is_active;
        $isNowActive = $userData['is_active'];

        // If becoming active and was not verified, verify now
        if ($isNowActive && ! $wasActive && ! $user->email_verified_at) {
            $userData['email_verified_at'] = now();
        }

        // If becoming inactive, remove verification
        if (! $isNowActive && $wasActive) {
            $userData['email_verified_at'] = null;
        }

        return $userData;
    }

    /**
     * Send email verification notification if user is inactive.
     */
    private function sendVerificationIfInactive(User|\Illuminate\Database\Eloquent\Model $user, bool $isActive, ?string $password = null): void
    {
        if (! $isActive && ! $user->email_verified_at) {
            $user->sendEmailVerificationNotification($password);
        }
    }

    /**
     * Remove avatar file from storage if exists.
     */
    private function removeAvatarFile(User|\Illuminate\Database\Eloquent\Model $user): void
    {
        if ($user->avatar && Storage::disk(self::STORAGE_DISK)->exists($user->avatar)) {
            Storage::disk(self::STORAGE_DISK)->delete($user->avatar);
        }
    }

    /**
     * Validate that user has the specified role.
     *
     * @throws \Exception
     */
    private function validateUserHasRole(User|\Illuminate\Database\Eloquent\Model $user, int $roleId): void
    {
        $roleIds = $user->roles->pluck('id')->toArray();

        if (! in_array($roleId, $roleIds)) {
            throw new \Exception('Unauthorized role selection.');
        }
    }

    /**
     * Get the default role for registration.
     */
    private function getDefaultRole(): ?Role
    {
        return Role::where('slug', 'user')->first();
    }
}
