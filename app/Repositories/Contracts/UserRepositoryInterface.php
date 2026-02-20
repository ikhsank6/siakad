<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Search users with role relation.
     */
    public function searchWithRoles(?string $term, int $perPage = 10): LengthAwarePaginator;

    /**
     * Create user with roles.
     */
    public function createWithRoles(array $userData, array $roleIds, ?int $defaultRoleId = null): User;

    /**
     * Update user with roles.
     */
    public function updateWithRoles(int $userId, array $userData, array $roleIds, ?int $defaultRoleId = null): bool;

    /**
     * Update user password.
     */
    public function updatePassword(int $userId, string $newPassword): bool;

    /**
     * Update user avatar.
     *
     * @param  mixed  $file
     */
    public function updateAvatar(int $userId, $file): string;

    /**
     * Delete user avatar.
     */
    public function deleteAvatar(int $userId): bool;

    /**
     * Set default role for login.
     */
    public function setDefaultRole(int $userId, int $roleId): bool;

    /**
     * Register a new user.
     */
    public function register(array $data): User;

    /**
     * Set the active role for a user.
     */
    public function setActiveRole(int $userId, int $roleId): bool;
}
