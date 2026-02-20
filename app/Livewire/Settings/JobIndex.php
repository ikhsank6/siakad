<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Failed Jobs')]
class JobIndex extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public $perPage = 10;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function retry($id)
    {
        try {
            Artisan::call('queue:retry', ['id' => [$id]]);
            $this->dispatch('notify', text: 'Job retried successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function retryAll()
    {
        try {
            Artisan::call('queue:retry', ['id' => ['all']]);
            $this->dispatch('notify', text: 'All failed jobs have been queued for retry.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete($id)
    {
        try {
            DB::table('failed_jobs')->where('id', $id)->delete();
            $this->dispatch('notify', text: 'Failed job deleted successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function deleteAll()
    {
        try {
            DB::table('failed_jobs')->truncate();
            $this->dispatch('notify', text: 'All failed jobs cleared.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        $failedJobs = DB::table('failed_jobs')
            ->when($this->search, function ($query) {
                $query->where('payload', 'like', '%'.$this->search.'%')
                    ->orWhere('exception', 'like', '%'.$this->search.'%')
                    ->orWhere('queue', 'like', '%'.$this->search.'%');
            })
            ->orderBy('failed_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.settings.job-index', [
            'failedJobs' => $failedJobs,
        ]);
    }
}
