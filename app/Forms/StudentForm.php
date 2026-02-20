<?php

namespace App\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class StudentForm
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
            Select::make('class_id')
                ->relationship('academicClass', 'name')
                ->searchable()
                ->preload()
                ->label('Class'),
            TextInput::make('nisn')
                ->required()
                ->unique('students', 'nisn', ignoreRecord: true)
                ->label('NISN'),
            TextInput::make('name')
                ->required()
                ->label('Full Name'),
        ];
    }
}
