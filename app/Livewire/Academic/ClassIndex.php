<?php

namespace App\Livewire\Academic;

use App\Forms\ClassForm;
use App\Livewire\Concerns\HasTableView;
use App\Models\AcademicClass;
use App\Repositories\Contracts\AcademicClassRepositoryInterface;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Classes')]
class ClassIndex extends Component implements HasForms
{
    use HasTableView;
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public ?array $data = [];
    public ?AcademicClass $record = null;
    public $showModal = false;

    protected AcademicClassRepositoryInterface $classRepository;

    public function boot(AcademicClassRepositoryInterface $classRepository)
    {
        $this->classRepository = $classRepository;
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(ClassForm::schema())
            ->statePath('data')
            ->model($this->record ?? AcademicClass::class);
    }

    public function create()
    {
        $this->record = null;
        $this->resetValidation();
        $this->form->fill();
        $this->showModal = true;
    }

    public function edit(AcademicClass $class)
    {
        $this->record = $class;
        $this->resetValidation();
        $this->form->fill($class->attributesToArray());
        $this->showModal = true;
    }

    public function save()
    {
        // Validate form first - this will show errors under each field
        $data = $this->form->getState();

        try {
            if ($this->record) {
                $this->classRepository->update($this->record->id, $data);
                $message = 'Class updated successfully.';
            } else {
                $this->classRepository->create($data);
                $message = 'Class created successfully.';
            }

            $this->dispatch('notify', text: $message, variant: 'success');
            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(AcademicClass $class)
    {
        try {
            $this->classRepository->delete($class->id);
            $this->dispatch('notify', text: 'Class deleted successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        // Actually, $this->classRepository->search returns paginator
        $query = clone $this->classRepository->query();
        
        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }
        
        return view('livewire.academic.class-index', [
            'classes' => $query->with('room')->latest()->paginate($this->perPage),
        ]);
    }
}
