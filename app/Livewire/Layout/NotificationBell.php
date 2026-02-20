<?php

namespace App\Livewire\Layout;

use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NotificationBell extends Component
{
    protected NotificationRepositoryInterface $notificationRepository;

    public function boot(NotificationRepositoryInterface $notificationRepository): void
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function getNotificationsProperty()
    {
        return $this->notificationRepository->getForRole(
            Auth::user()->role_id,
            5,
            true
        );
    }

    public function getUnreadCountProperty()
    {
        return $this->notificationRepository->countUnreadForRole(Auth::user()->role_id);
    }

    public function markAsRead(string $uuid)
    {
        try {
            $notification = $this->notificationRepository->findByUuid($uuid);

            $redirectUrl = DB::transaction(function () use ($notification) {
                if ($notification && $notification->to_role_id == Auth::user()->role_id) {
                    $this->notificationRepository->markAsRead($notification->id, Auth::user()->role_id);

                    return $notification->url;
                }

                return null;
            });

            if ($redirectUrl) {
                return redirect($redirectUrl);
            }
        } catch (\Exception $e) {
            // Silent fail for notification bell
        }
    }

    public function render()
    {
        return view('livewire.layout.notification-bell');
    }
}
