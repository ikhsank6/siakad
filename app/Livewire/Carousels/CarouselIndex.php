<?php

namespace App\Livewire\Carousels;

use App\Forms\CarouselForm;
use App\Livewire\Concerns\HasTableView;
use App\Models\Carousel;
use App\Repositories\Contracts\CarouselRepositoryInterface;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Carousels')]
class CarouselIndex extends Component implements HasForms
{
    use HasTableView;
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public ?array $data = [];

    public ?Carousel $record = null;

    public $showModal = false;

    protected CarouselRepositoryInterface $carouselRepository;

    public function boot(CarouselRepositoryInterface $carouselRepository): void
    {
        $this->carouselRepository = $carouselRepository;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(CarouselForm::schema())
            ->statePath('data')
            ->model($this->record ?? Carousel::class)
            ->columns(2);
    }

    public function create(): void
    {
        $this->record = null;
        $this->resetValidation();
        $this->form->fill();
        $this->showModal = true;
    }

    public function edit(Carousel $carousel): void
    {
        $this->record = $carousel;
        $this->resetValidation();
        $this->form->fill($carousel->attributesToArray());
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
        $data = $this->form->getState();
        $data['updated_by'] = Auth::id();

        try {
            DB::transaction(function () use ($data) {
                if ($this->record) {
                    $this->carouselRepository->update($this->record->id, $data);
                } else {
                    $data['created_by'] = Auth::id();
                    $this->carouselRepository->create($data);
                }
            });

            $message = $this->record ? 'Carousel updated successfully.' : 'Carousel created successfully.';
            $this->dispatch('notify', text: $message, variant: 'success');
            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(Carousel $carousel): void
    {
        try {
            $this->carouselRepository->delete($carousel->id);

            $this->dispatch('notify', text: 'Carousel deleted successfully.', variant: 'success');
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

    public function updateOrder(array $orderedIds): void
    {
        try {
            DB::transaction(function () use ($orderedIds) {
                $this->carouselRepository->updateOrder($orderedIds);
            });

            $this->dispatch('notify', text: 'Carousel order updated successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.carousels.index', [
            'carousels' => $this->carouselRepository->searchByTerm($this->search, $this->perPage),
        ]);
    }
}
