<?php

namespace App\Livewire\Academic;

use App\Forms\StudentForm;
use App\Livewire\Concerns\HasTableView;
use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Students')]
class StudentIndex extends Component implements HasForms
{
    use HasTableView;
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public ?array $data = [];
    public ?Student $record = null;
    public $showModal = false;

    protected StudentRepositoryInterface $studentRepository;

    public function boot(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(StudentForm::schema())
            ->statePath('data')
            ->model($this->record ?? Student::class);
    }

    public function create()
    {
        $this->record = null;
        $this->resetValidation();
        $this->form->fill();
        $this->showModal = true;
    }

    public function edit(Student $student)
    {
        $this->record = $student;
        $this->resetValidation();
        $this->form->fill($student->attributesToArray());
        $this->showModal = true;
    }

    public function save()
    {
        // Validate form first - this will show errors under each field
        $data = $this->form->getState();

        try {
            if ($this->record) {
                $this->studentRepository->update($this->record->id, $data);
                $message = 'Student updated successfully.';
            } else {
                $this->studentRepository->create($data);
                $message = 'Student created successfully.';
            }

            $this->dispatch('notify', text: $message, variant: 'success');
            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(Student $student)
    {
        try {
            $this->studentRepository->delete($student->id);
            $this->dispatch('notify', text: 'Student deleted successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.academic.student-index', [
            'students' => $this->studentRepository->search(['name', 'nisn'], $this->search, $this->perPage),
        ]);
    }
}
