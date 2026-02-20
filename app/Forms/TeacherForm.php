<?php

namespace App\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class TeacherForm
{
    public static function schema(): array
    {
        return [
            Select::make('user_id')
                ->relationship('user', 'name')
                ->required()
                ->searchable()
                ->preload()
                ->label('User Account'),
            TextInput::make('nip')
                ->required()
                ->unique('teachers', 'nip', ignoreRecord: true)
                ->label('NIP'),
            TextInput::make('name')
                ->required()
                ->label('Full Name'),
            TextInput::make('phone')
                ->tel()
                ->label('Phone Number'),
            Textarea::make('address')
                ->label('Address'),
        ];
    }
}
