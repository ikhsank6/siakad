<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }

    /**
     * Get notifications for a role.
     */
    public function getForRole(int $roleId, int $limit = 5, bool $unreadOnly = true): Collection
    {
        $query = $this->model->where('to_role_id', $roleId);

        if ($unreadOnly) {
            $query->where('read', false);
        }

        return $query->latest()->take($limit)->get();
    }

    /**
     * Get paginated notifications for a role.
     */
    public function getPaginatedForRole(int $roleId, ?string $filter = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with('fromRole')->where('to_role_id', $roleId);

        if ($filter === 'unread') {
            $query->where('read', false);
        } elseif ($filter === 'read') {
            $query->where('read', true);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Count unread notifications for a role.
     */
    public function countUnreadForRole(int $roleId): int
    {
        return $this->model
            ->where('to_role_id', $roleId)
            ->where('read', false)
            ->count();
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(int $id, int $roleId): bool
    {
        return DB::transaction(function () use ($id, $roleId) {
            return $this->model
                ->where('id', $id)
                ->where('to_role_id', $roleId)
                ->update(['read' => true]) > 0;
        });
    }

    /**
     * Mark all notifications as read for a role.
     */
    public function markAllAsReadForRole(int $roleId): int
    {
        return DB::transaction(function () use ($roleId) {
            return $this->model
                ->where('to_role_id', $roleId)
                ->where('read', false)
                ->update(['read' => true]);
        });
    }

    /**
     * Delete all read notifications for a role.
     */
    public function deleteAllReadForRole(int $roleId): int
    {
        return DB::transaction(function () use ($roleId) {
            return $this->model
                ->where('to_role_id', $roleId)
                ->where('read', true)
                ->delete();
        });
    }

    /**
     * Delete a notification for a specific role.
     */
    public function deleteForRole(int $id, int $roleId): bool
    {
        return DB::transaction(function () use ($id, $roleId) {
            return $this->model
                ->where('id', $id)
                ->where('to_role_id', $roleId)
                ->delete() > 0;
        });
    }
}
