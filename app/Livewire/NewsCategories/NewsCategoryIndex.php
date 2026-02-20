<?php

namespace App\Livewire\NewsCategories;

use App\Forms\NewsCategoryForm;
use App\Livewire\Concerns\HasTableView;
use App\Models\NewsCategory;
use App\Repositories\Contracts\NewsCategoryRepositoryInterface;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('News Categories')]
class NewsCategoryIndex extends Component implements HasForms
{
    use HasTableView;
    use InteractsWithForms;
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $perPage = 10;

    public ?array $data = [];

    public ?NewsCategory $record = null;

    public $showModal = false;

    protected NewsCategoryRepositoryInterface $newsCategoryRepository;

    public function boot(NewsCategoryRepositoryInterface $newsCategoryRepository): void
    {
        $this->newsCategoryRepository = $newsCategoryRepository;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(NewsCategoryForm::schema())
            ->statePath('data')
            ->model($this->record ?? NewsCategory::class)
            ->columns(1);
    }

    public function create(): void
    {
        $this->record = null;
        $this->form->fill();
        $this->showModal = true;
    }

    public function edit(NewsCategory $newsCategory): void
    {
        $this->record = $newsCategory;
        $this->form->fill($newsCategory->attributesToArray());
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['updated_by'] = Auth::id();

        try {
            DB::transaction(function () use ($data) {
                if ($this->record) {
                    $this->newsCategoryRepository->update($this->record->id, $data);
                } else {
                    $data['created_by'] = Auth::id();
                    $this->newsCategoryRepository->create($data);
                }
            });

            $message = $this->record ? 'News Category updated successfully.' : 'News Category created successfully.';
            $this->dispatch('notify', text: $message, variant: 'success');
            $this->showModal = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(NewsCategory $newsCategory): void
    {
        try {
            DB::transaction(function () use ($newsCategory) {
                $this->newsCategoryRepository->delete($newsCategory->id);
            });

            $this->dispatch('notify', text: 'News Category deleted successfully.', variant: 'success');
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
        return view('livewire.news-categories.index', [
            'categories' => $this->newsCategoryRepository->searchByTerm($this->search, $this->perPage),
        ]);
    }
}
