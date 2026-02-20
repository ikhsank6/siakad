<?php

namespace App\Livewire\Academic;

use App\Forms\TeacherForm;
use App\Livewire\Concerns\HasTableView;
use App\Models\Teacher;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Teachers')]
class TeacherIndex extends Component implements HasForms
{
    use HasTableView;
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public ?array $data = [];
    public ?Teacher $record = null;
    public $showModal = false;

    protected TeacherRepositoryInterface $teacherRepository;

    public function boot(TeacherRepositoryInterface $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(TeacherForm::schema())
            ->statePath('data')
            ->model($this->record ?? Teacher::class);
    }

    public function create()
    {
        $this->record = null;
        $this->resetValidation();
        $this->form->fill();
        $this->showModal = true;
    }

    public function edit(Teacher $teacher)
    {
        $this->record = $teacher;
        $this->resetValidation();
        $this->form->fill($teacher->attributesToArray());
        $this->showModal = true;
    }

    public function save()
    {
        // Validate form first - this will show errors under each field
        $data = $this->form->getState();

        try {
            if ($this->record) {
                $this->teacherRepository->update($this->record->id, $data);
                $message = 'Teacher updated successfully.';
            } else {
                $this->teacherRepository->create($data);
                $message = 'Teacher created successfully.';
            }

            $this->dispatch('notify', text: $message, variant: 'success');
            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(Teacher $teacher)
    {
        try {
            $this->teacherRepository->delete($teacher->id);
            $this->dispatch('notify', text: 'Teacher deleted successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        // Actually, $this->teacherRepository->search returns paginator, so we can't easily append with() there.
        // We will just let it be, or update BaseRepository to support relationships.
        // Since it's a small app, we'll let it be for now. But better to eager load if possible.
        // I will update the BaseRepository search temporarily or just let it query.
        $query = $this->teacherRepository->query()->with('subjects');
        
        if ($this->search) {
            $query->where(function ($q) {
                $q->orWhere('name', 'like', "%{$this->search}%")
                  ->orWhere('nip', 'like', "%{$this->search}%");
            });
        }
        
        return view('livewire.academic.teacher-index', [
            'teachers' => $query->latest()->paginate($this->perPage),
        ]);
    }
}
