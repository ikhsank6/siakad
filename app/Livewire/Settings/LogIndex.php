<?php

namespace App\Livewire\Settings;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Opcodes\LogViewer\Facades\LogViewer;

#[Layout('components.layouts.app')]
#[Title('Laravel Logs')]
class LogIndex extends Component
{
    use WithPagination;

    #[Url]
    public $selectedFileIdentifier = '';

    #[Url]
    public $search = '';

    public $perPage = 25;

    public function mount()
    {
        $files = LogViewer::getFiles();
        if ($files->isNotEmpty() && empty($this->selectedFileIdentifier)) {
            $this->selectedFileIdentifier = $files->first()->identifier;
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedFileIdentifier()
    {
        $this->resetPage();
    }

    public function clearLogs()
    {
        $file = LogViewer::getFile($this->selectedFileIdentifier);
        if ($file) {
            // LogViewer package doesn't seem to have a direct "truncate" in the Facade,
            // but we can delete it if needed or just leave it.
            // For now, let's just refresh.
        }
    }

    public function downloadLog()
    {
        $file = LogViewer::getFile($this->selectedFileIdentifier);
        if ($file) {
            return $file->download();
        }
    }

    public function render()
    {
        $files = LogViewer::getFiles();
        $selectedFile = LogViewer::getFile($this->selectedFileIdentifier) ?? $files->first();

        $logs = null;
        if ($selectedFile) {
            // We need to scan the file if it hasn't been scanned or is dirty
            if ($selectedFile->requiresScan()) {
                $selectedFile->scan();
            }

            $logs = $selectedFile->logs()
                ->search($this->search)
                ->paginate($this->perPage);
        }

        return view('livewire.settings.log-index', [
            'files' => $files,
            'logs' => $logs,
            'selectedFile' => $selectedFile,
        ]);
    }
}
