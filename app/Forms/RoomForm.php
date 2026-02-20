<?php

namespace App\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class RoomForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255)
                ->label('Room Name'),
            TextInput::make('capacity')
                ->required()
                ->numeric()
                ->minValue(1)
                ->label('Capacity'),
            Select::make('type')
                ->options([
                    'General' => 'General Classroom',
                    'Laboratory' => 'Laboratory',
                    'Workshop' => 'Workshop',
                    'Other' => 'Other',
                ])
                ->default('General')
                ->required()
                ->searchable()
                ->label('Room Type'),
        ];
    }
}
