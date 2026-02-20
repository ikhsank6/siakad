<?php

namespace App\Livewire\Menus;

use App\Forms\MenuForm;
use App\Livewire\Concerns\HasTableView;
use App\Models\Menu;
use App\Repositories\Contracts\MenuRepositoryInterface;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Menus')]
class MenuIndex extends Component implements HasForms
{
    use HasTableView;
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 50;

    public ?array $data = [];

    public ?Menu $record = null;

    public $showModal = false;

    protected MenuRepositoryInterface $menuRepository;

    public function boot(MenuRepositoryInterface $menuRepository): void
    {
        $this->menuRepository = $menuRepository;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(MenuForm::schema())
            ->statePath('data')
            ->model($this->record ?? Menu::class)
            ->columns(1);
    }

    public function create(): void
    {
        $this->record = null;
        $this->resetValidation();
        $this->form->fill([
            'order' => $this->menuRepository->getNextOrder(),
        ]);
        $this->showModal = true;
    }

    public function edit(Menu $menu): void
    {
        $this->record = $menu;
        $this->resetValidation();
        $this->form->fill($menu->attributesToArray());
        $this->showModal = true;
    }

    public function updatedShowModal($value): void
    {
        if (! $value) {
            $this->resetValidation();
            $this->record = null;
            $this->form->fill();
        }
    }

    public function save(): void
    {
        // Validate form first - this will show errors under each field
        $data = $this->form->getState();

        try {
            DB::transaction(function () use ($data) {
                if ($this->record) {
                    $this->menuRepository->update($this->record->id, $data);
                } else {
                    $this->menuRepository->create($data);
                }
            });

            $message = $this->record ? 'Menu updated successfully.' : 'Menu created successfully.';
            $this->dispatch('notify', text: $message, variant: 'success');
            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(Menu $menu): void
    {
        try {
            DB::transaction(function () use ($menu) {
                $this->menuRepository->delete($menu->id);
            });

            $this->dispatch('notify', text: 'Menu deleted successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    /**
     * Update menu order from drag and drop
     */
    public function updateOrder(array $orderedIds): void
    {
        try {
            DB::transaction(function () use ($orderedIds) {
                $this->menuRepository->updateOrder($orderedIds);
            });

            $this->dispatch('notify', text: 'Menu order updated successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    /**
     * Update menu parent from drag and drop between parents
     */
    public function updateParent(int $menuId, ?int $newParentId): void
    {
        try {
            DB::transaction(function () use ($menuId, $newParentId) {
                $this->menuRepository->updateParent($menuId, $newParentId);
            });

            $this->dispatch('notify', text: 'Menu moved successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.menus.index', [
            'menus' => $this->menuRepository->searchWithParent($this->search, $this->perPage),
        ]);
    }
}
