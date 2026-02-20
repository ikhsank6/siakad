<?php

namespace App\Livewire\Academic;

use App\Forms\RoomForm;
use App\Livewire\Concerns\HasTableView;
use App\Models\Room;
use App\Repositories\Contracts\RoomRepositoryInterface;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Rooms')]
class RoomIndex extends Component implements HasForms
{
    use HasTableView;
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public ?array $data = [];
    public ?Room $record = null;
    public $showModal = false;

    protected RoomRepositoryInterface $roomRepository;

    public function boot(RoomRepositoryInterface $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(RoomForm::schema())
            ->statePath('data')
            ->model($this->record ?? Room::class);
    }

    public function create()
    {
        $this->record = null;
        $this->resetValidation();
        $this->form->fill();
        $this->showModal = true;
    }

    public function edit(Room $room)
    {
        $this->record = $room;
        $this->resetValidation();
        $this->form->fill($room->attributesToArray());
        $this->showModal = true;
    }

    public function save()
    {
        // Validate form first - this will show errors under each field
        $data = $this->form->getState();

        try {
            if ($this->record) {
                $this->roomRepository->update($this->record->id, $data);
                $message = 'Room updated successfully.';
            } else {
                $this->roomRepository->create($data);
                $message = 'Room created successfully.';
            }

            $this->dispatch('notify', text: $message, variant: 'success');
            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(Room $room)
    {
        try {
            $this->roomRepository->delete($room->id);
            $this->dispatch('notify', text: 'Room deleted successfully.', variant: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.academic.room-index', [
            'rooms' => $this->roomRepository->search(['name', 'type'], $this->search, $this->perPage),
        ]);
    }
}
