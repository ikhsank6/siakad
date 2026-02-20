<?php

namespace App\Livewire\News;

use App\Forms\NewsForm;
use App\Livewire\Concerns\HasTableView;
use App\Models\News;
use App\Repositories\Contracts\NewsRepositoryInterface;
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
#[Title('News')]
class NewsIndex extends Component implements HasForms
{
    use HasTableView;
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public ?array $data = [];

    public ?News $record = null;

    public $showModal = false;

    protected NewsRepositoryInterface $newsRepository;

    public function boot(NewsRepositoryInterface $newsRepository): void
    {
        $this->newsRepository = $newsRepository;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(NewsForm::schema())
            ->statePath('data')
            ->model($this->record ?? News::class)
            ->columns(2);
    }

    public function create(): void
    {
        $this->record = null;
        $this->resetValidation();
        $this->form->fill(['published_at' => now()]);
        $this->showModal = true;
    }

    public function edit(News $news): void
    {
        $this->record = $news;
        $this->resetValidation();
        $this->form->fill($news->attributesToArray());
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
                    $this->newsRepository->update($this->record->id, $data);
                } else {
                    $data['created_by'] = Auth::id();
                    $this->newsRepository->create($data);
                }
            });

            $message = $this->record ? 'News updated successfully.' : 'News created successfully.';
            $this->dispatch('notify', text: $message, variant: 'success');
            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(News $news): void
    {
        try {
            $this->newsRepository->delete($news->id);

            $this->dispatch('notify', text: 'News deleted successfully.', variant: 'success');
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
        return view('livewire.news.index', [
            'news' => $this->newsRepository->searchByTerm($this->search, $this->perPage),
        ]);
    }
}
