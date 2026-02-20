<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface NotificationRepositoryInterface extends RepositoryInterface
{
    /**
     * Get notifications for a role.
     */
    public function getForRole(int $roleId, int $limit = 5, bool $unreadOnly = true): Collection;

    /**
     * Get paginated notifications for a role.
     */
    public function getPaginatedForRole(int $roleId, ?string $filter = null, int $perPage = 15): LengthAwarePaginator;

    /**
     * Count unread notifications for a role.
     */
    public function countUnreadForRole(int $roleId): int;

    /**
     * Mark notification as read.
     */
    public function markAsRead(int $id, int $roleId): bool;

    /**
     * Mark all notifications as read for a role.
     */
    public function markAllAsReadForRole(int $roleId): int;

    /**
     * Delete all read notifications for a role.
     */
    public function deleteAllReadForRole(int $roleId): int;
}
