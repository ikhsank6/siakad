<?php

namespace App\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ClassForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->unique(table: 'classes', column: 'name', ignoreRecord: true)
                ->maxLength(255)
                ->label('Class Name/Code'),
            Select::make('grade_level')
                ->options([
                    10 => 'Grade 10',
                    11 => 'Grade 11',
                    12 => 'Grade 12',
                ])
                ->required()
                ->searchable()
                ->label('Grade Level'),
            TextInput::make('major')
                ->nullable()
                ->maxLength(255)
                ->label('Major/Specialization (e.g. IPA, IPS, Bahasa)'),
            Select::make('room_id')
                ->relationship('room', 'name')
                ->searchable()
                ->preload()
                ->nullable()
                ->label('Home Room (Optional)'),
        ];
    }
}
