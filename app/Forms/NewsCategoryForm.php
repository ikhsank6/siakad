<?php

namespace App\Forms;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class NewsCategoryForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')
                ->label('Category Name')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

            TextInput::make('slug')
                ->label('Slug')
                ->helperText('Otomatis terisi dari nama kategori')
                ->maxLength(255),

            Textarea::make('description')
                ->label('Description')
                ->rows(3),

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
