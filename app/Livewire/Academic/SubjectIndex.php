<?php

namespace App\Livewire\Academic;

use App\Forms\SubjectForm;
use App\Livewire\Concerns\HasTableView;
use App\Models\Subject;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Subjects')]
class SubjectIndex extends Component implements HasForms
{
    use HasTableView;
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public ?array $data = [];
    public ?Subject $record = null;
    public $showModal = false;

    protected SubjectRepositoryInterface $subjectRepository;

    public function boot(SubjectRepositoryInterface $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(SubjectForm::schema())
            ->statePath('data')
            ->model($this->record ?? Subject::class);
    }

    public function create()
    {
        $this->record = null;
        $this->resetValidation();
        $this->form->fill();
        $this->showModal = true;
    }

    public function edit(Subject $subject)
    {
        $this->record = $subject;
        $this->resetValidation();
        $this->form->fill($subject->attributesToArray());
        $this->showModal = true;
    }

    public function save()
    {
        $data = $this->form->getState();

        if ($this->record) {
            $this->subjectRepository->update($this->record->id, $data);
        } else {
            $this->subjectRepository->create($data);
        }

        $this->dispatch('notify', text: 'Subject saved successfully.', variant: 'success');
        $this->showModal = false;
        $this->dispatch('refresh');
    }

    public function delete(Subject $subject)
    {
        $this->subjectRepository->delete($subject->id);
        $this->dispatch('notify', text: 'Subject deleted successfully.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.academic.subject-index', [
            'subjects' => $this->subjectRepository->search(['name', 'code'], $this->search, $this->perPage),
        ]);
    }
}
