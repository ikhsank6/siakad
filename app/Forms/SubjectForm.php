<?php

namespace App\Forms;

use Filament\Forms\Components\TextInput;

class SubjectForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('code')
                ->required()
                ->unique('subjects', 'code', ignoreRecord: true)
                ->label('Subject Code'),
            TextInput::make('name')
                ->required()
                ->label('Subject Name'),
            TextInput::make('default_hours_per_week')
                ->numeric()
                ->required()
                ->label('Default Hours Per Week'),
        ];
    }
}
