<?php

namespace App\Forms;

use App\Models\NewsCategory;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class NewsForm
{
    public static function schema(): array
    {
        return [
            Select::make('news_category_id')
                ->label('Category')
                ->options(NewsCategory::active()->pluck('name', 'id'))
                ->required()
                ->searchable(),

            TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

            TextInput::make('slug')
                ->label('Slug')
                ->helperText('Auto-generated from title, or enter custom slug')
                ->maxLength(255),

            Textarea::make('excerpt')
                ->label('Excerpt')
                ->helperText('Short summary, leave empty to auto-generate')
                ->rows(2),

            RichEditor::make('content')
                ->label('Content')
                ->required()
                ->columnSpanFull(),

            FileUpload::make('image')
                ->label('Featured Image')
                ->image()
                ->directory('news')
                ->columnSpanFull(),

            DateTimePicker::make('published_at')
                ->label('Publish Date')
                ->default(now()),

            ToggleButtons::make('is_featured')
                ->label('Featured')
                ->options([
                    1 => 'Yes',
                    0 => 'No',
                ])
                ->icons([
                    1 => 'heroicon-m-check-circle',
                    0 => 'heroicon-m-x-circle',
                ])
                ->colors([
                    1 => 'success',
                    0 => 'danger',
                ])
                ->default(0)
                ->extraAttributes(['class' => 'premium-toggle-group'])
                ->inline(),

            ToggleButtons::make('is_active')
                ->label('Status')
                ->options([
                    1 => 'Active',
                    0 => 'Inactive',
                ])
                ->icons([
                    1 => 'heroicon-m-check-circle',
                    0 => 'heroicon-m-x-circle',
                ])
                ->colors([
                    1 => 'success',
                    0 => 'danger',
                ])
                ->default(1)
                ->extraAttributes(['class' => 'premium-toggle-group'])
                ->inline(),
        ];
    }
}
