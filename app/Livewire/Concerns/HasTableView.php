<?php

namespace App\Livewire\Concerns;

use Livewire\Attributes\Url;

trait HasTableView
{
    #[Url]
    public $view = 'table';

    public function updatedView(): void
    {
        $this->resetPage();
    }
}
