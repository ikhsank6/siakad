<?php

namespace App\Livewire\Layout;

use App\Livewire\Concerns\HasTableView;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Notifications')]
class NotificationIndex extends Component
{
    use HasTableView;
    use WithPagination;

    #[Url]
    public string $filter = 'all'; // all, unread, read

    #[Url]
    public int $perPage = 10;

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    protected NotificationRepositoryInterface $notificationRepository;

    public function boot(NotificationRepositoryInterface $notificationRepository): void
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function markAsRead(int $id): void
    {
        try {
            DB::transaction(function () use ($id) {
                $this->notificationRepository->markAsRead($id, Auth::user()->role_id);
            });

            $this->dispatch('notify', text: 'Notification marked as read.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function markAllAsRead(): void
    {
        try {
            DB::transaction(function () {
                $this->notificationRepository->markAllAsReadForRole(Auth::user()->role_id);
            });

            $this->dispatch('notify', text: 'All notifications marked as read.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(int $id): void
    {
        try {
            DB::transaction(function () use ($id) {
                $this->notificationRepository->delete($id);
            });

            $this->dispatch('notify', text: 'Notification deleted.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function deleteAllRead(): void
    {
        try {
            DB::transaction(function () {
                $this->notificationRepository->deleteAllReadForRole(Auth::user()->role_id);
            });

            $this->dispatch('notify', text: 'All read notifications deleted.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function getUnreadCountProperty(): int
    {
        return $this->notificationRepository->countUnreadForRole(Auth::user()->role_id);
    }

    public function render()
    {
        return view('livewire.layout.notification-index', [
            'notifications' => $this->notificationRepository->getPaginatedForRole(
                Auth::user()->role_id,
                $this->filter === 'all' ? null : $this->filter,
                $this->perPage
            ),
        ]);
    }
}
