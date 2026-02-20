<?php

namespace App\Models;

use App\Notifications\VerifyEmailNotification;
use App\Traits\HasUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasUuid, Notifiable, SoftDeletes;

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(?string $password = null): void
    {
        $this->notify(new VerifyEmailNotification($password));
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordQueued($token));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // Active role
        'is_active',
        'avatar',
        'media_id',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the media associated with the user avatar.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the currently active role (for menu access, permissions, etc.)
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get all roles that the user has (many-to-many).
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    /**
     * Get the default role for login.
     */
    public function getDefaultRole(): ?Role
    {
        return $this->roles()->wherePivot('is_default', true)->first()
            ?? $this->roles()->first();
    }

    /**
     * Set the active role.
     */
    public function setActiveRole(Role $role): bool
    {
        if (! $this->roles()->where('roles.id', $role->id)->exists()) {
            return false;
        }

        $this->update(['role_id' => $role->id]);

        return true;
    }

    /**
     * Sync user roles and set default.
     */
    public function syncRoles(array $roleIds, ?int $defaultRoleId = null): void
    {
        // Prepare pivot data with is_default
        $syncData = [];
        foreach ($roleIds as $roleId) {
            $syncData[$roleId] = ['is_default' => $roleId == $defaultRoleId];
        }

        $this->roles()->sync($syncData);

        // Set active role to default role or first role
        $activeRoleId = $defaultRoleId ?? ($roleIds[0] ?? null);
        if ($activeRoleId) {
            $this->update(['role_id' => $activeRoleId]);
        }
    }

    /**
     * Check if the user has access to a specific menu.
     */
    public function hasMenuAccess(Menu $menu): bool
    {
        if (! $this->role) {
            return false;
        }

        return $this->role->menus()->where('menus.id', $menu->id)->exists();
    }

    /**
     * Check if the user has access to a specific route.
     */
    public function hasRouteAccess(string $routeName): bool
    {
        if (! $this->role) {
            return false;
        }

        return $this->role->menus()->where('route', $routeName)->exists();
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get the teacher profile for the user.
     */
    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Get the student profile for the user.
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }
}
