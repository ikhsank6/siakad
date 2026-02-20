<?php

namespace App\Livewire\Layout;

use App\Services\MenuService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\Component;

class Sidebar extends Component
{
    public array $menuTree = [];

    public string $currentRoute = '';

    public function mount(MenuService $menuService): void
    {
        $this->menuTree = $menuService->getMenuTreeForUser();
        $this->currentRoute = Route::currentRouteName() ?? '';
    }

    #[On('refresh-sidebar')]
    public function refreshSidebar(): void
    {
        $menuService = app(MenuService::class);
        $this->menuTree = $menuService->getMenuTreeForUser();
    }

    public function render(): View
    {
        return view('livewire.layout.sidebar');
    }
}
