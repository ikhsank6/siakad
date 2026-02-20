<?php

namespace App\Livewire\Academic;

use App\Forms\AcademicYearForm;
use App\Livewire\Concerns\HasTableView;
use App\Models\AcademicYear;
use App\Repositories\Contracts\AcademicYearRepositoryInterface;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Academic Years')]
class AcademicYearIndex extends Component implements HasForms
{
    use HasTableView;
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public ?array $data = [];
    public ?AcademicYear $record = null;
    public $showModal = false;

    protected AcademicYearRepositoryInterface $academicYearRepository;

    public function boot(AcademicYearRepositoryInterface $academicYearRepository)
    {
        $this->academicYearRepository = $academicYearRepository;
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(AcademicYearForm::schema())
            ->statePath('data')
            ->model($this->record ?? AcademicYear::class);
    }

    public function create()
    {
        $this->record = null;
        $this->resetValidation();
        $this->form->fill();
        $this->showModal = true;
    }

    public function edit(AcademicYear $academicYear)
    {
        $this->record = $academicYear;
        $this->resetValidation();
        $this->form->fill($academicYear->attributesToArray());
        $this->showModal = true;
    }

    public function save()
    {
        $data = $this->form->getState();

        try {
            if ($this->record) {
                if ($data['is_active']) {
                    $this->academicYearRepository->setActiveYear($this->record->id);
                    // Update other fields
                    unset($data['is_active']);
                    $this->academicYearRepository->update($this->record->id, $data);
                } else {
                    $this->academicYearRepository->update($this->record->id, $data);
                }
                $message = 'Academic Year updated successfully.';
            } else {
                $newRecord = $this->academicYearRepository->create($data);
                if ($data['is_active']) {
                    $this->academicYearRepository->setActiveYear($newRecord->id);
                }
                $message = 'Academic Year created successfully.';
            }

            $this->dispatch('notify', text: $message, variant: 'success');
            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(AcademicYear $academicYear)
    {
        try {
            $this->academicYearRepository->delete($academicYear->id);
            $this->dispatch('notify', text: 'Academic Year deleted successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        $query = clone $this->academicYearRepository->query();
        
        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }
        
        return view('livewire.academic.academic-year-index', [
            'academicYears' => $query->latest()->paginate($this->perPage),
        ]);
    }
}
