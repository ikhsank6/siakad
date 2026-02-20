<?php

namespace App\Livewire\Roles;

use App\Forms\RoleForm;
use App\Livewire\Concerns\HasTableView;
use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
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
#[Title('Roles')]
class RoleIndex extends Component implements HasForms
{
    use HasTableView;
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public ?array $data = [];

    public ?Role $record = null;

    public $showModal = false;

    protected RoleRepositoryInterface $roleRepository;

    public function boot(RoleRepositoryInterface $roleRepository): void
    {
        $this->roleRepository = $roleRepository;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(RoleForm::schema())
            ->statePath('data')
            ->model($this->record ?? Role::class)
            ->columns(1);
    }

    public function create(): void
    {
        $this->record = null;
        $this->resetValidation();
        $this->form->fill();
        $this->showModal = true;
    }

    public function edit(Role $role): void
    {
        $this->record = $role;
        $this->resetValidation();
        $this->form->fill($role->attributesToArray());
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
                    $this->roleRepository->update($this->record->id, $data);
                } else {
                    $this->roleRepository->create($data);
                }
            });

            $message = $this->record ? 'Role updated successfully.' : 'Role created successfully.';
            $this->dispatch('notify', text: $message, variant: 'success');
            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(Role $role): void
    {
        try {
            DB::transaction(function () use ($role) {
                $this->roleRepository->delete($role->id);
            });

            $this->dispatch('notify', text: 'Role deleted successfully.', variant: 'success');
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
        return view('livewire.roles.index', [
            'roles' => $this->roleRepository->searchWithUserCount($this->search, $this->perPage),
        ]);
    }
}
