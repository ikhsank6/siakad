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
        $data = $this->form->getState();

        if ($this->record) {
            $this->teacherRepository->update($this->record->id, $data);
        } else {
            $this->teacherRepository->create($data);
        }

        $this->dispatch('notify', text: 'Teacher saved successfully.', variant: 'success');
        $this->showModal = false;
        $this->dispatch('refresh');
    }

    public function delete(Teacher $teacher)
    {
        $this->teacherRepository->delete($teacher->id);
        $this->dispatch('notify', text: 'Teacher deleted successfully.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.academic.teacher-index', [
            'teachers' => $this->teacherRepository->search(['name', 'nip'], $this->search, $this->perPage),
        ]);
    }
}
