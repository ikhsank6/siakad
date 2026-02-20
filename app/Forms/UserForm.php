<?php

namespace App\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;

class UserForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')
                ->label('Display Name')
                ->required()
                ->maxLength(255)
                ->placeholder('John Doe'),

            TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255)
                ->placeholder('john@example.com'),

            Select::make('roles')
                ->options(\App\Models\Role::pluck('name', 'id'))
                ->multiple()
                ->required()
                ->preload()
                ->searchable()
                ->label('Access Roles')
                ->live()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    // Automatically set default_role_id if only one role is selected
                    if (count($state) === 1) {
                        $set('default_role_id', $state[0]);
                    }
                }),

            Select::make('default_role_id')
                ->label('Default Role')
                ->options(function (callable $get) {
                    $selectedRoles = $get('roles') ?? [];
                    if (empty($selectedRoles)) {
                        return [];
                    }

                    return \App\Models\Role::whereIn('id', $selectedRoles)->pluck('name', 'id');
                })
                ->required()
                ->searchable() // Forces custom UI to avoid UI glitches with native select
                ->helperText('This role will be active by default when the user logs in.')
                ->placeholder('Select a default role'),

            ToggleButtons::make('is_active')
                ->label('Account Status')
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
                ->default(0)
                ->extraAttributes(['class' => 'premium-toggle-group'])
                ->inline(),
        ];
    }
}
