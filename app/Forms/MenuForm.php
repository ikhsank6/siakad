<?php

namespace App\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;

class MenuForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')
                ->label('Menu Item Name')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),

            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),

            Select::make('parent_id')
                ->relationship('parent', 'name')
                ->searchable()
                ->preload()
                ->label('Parent Menu'),

            TextInput::make('order')
                ->numeric()
                ->default(0)
                ->required()
                ->readOnly()
                ->helperText('Order is managed via drag and drop on the list page.'),

            TextInput::make('icon')
                ->label('Icon Name')
                ->placeholder('e.g. home')
                ->helperText(new \Illuminate\Support\HtmlString('Get icons from <a href="https://fluxui.dev/components/icon" target="_blank" class="text-metronic-primary hover:underline">fluxui.dev/components/icon</a> or <a href="https://heroicons.com" target="_blank" class="text-metronic-primary hover:underline">heroicons.com</a>'))
                ->prefixIcon(fn ($state) => filled($state) ? (str_contains($state, 'heroicon-') ? $state : "heroicon-o-{$state}") : 'heroicon-o-magnifying-glass'),

            TextInput::make('route')
                ->label('Route Name')
                ->placeholder('e.g. dashboard'),

            ToggleButtons::make('is_active')
                ->label('Visibility')
                ->options([
                    1 => 'Visible',
                    0 => 'Hidden',
                ])
                ->icons([
                    1 => 'heroicon-m-eye',
                    0 => 'heroicon-m-eye-slash',
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
