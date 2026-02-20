<?php

namespace App\Forms;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

use Filament\Forms\Components\ToggleButtons;

class AcademicYearForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->placeholder('e.g. 2023/2024')
                ->label('Tahun Akademik'),
            Select::make('semester')
                ->options([
                    'Ganjil' => 'Ganjil',
                    'Genap' => 'Genap',
                ])
                ->required()
                ->searchable()
                ->label('Semester'),
            DatePicker::make('start_date')
                ->required()
                ->label('Tanggal Mulai'),
            DatePicker::make('end_date')
                ->required()
                ->label('Tanggal Selesai'),
            ToggleButtons::make('is_active')
                ->label('Status Tahun Akademik')
                ->options([
                    1 => 'Aktif',
                    0 => 'Non-Aktif',
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
                ->inline()
                ->helperText('Hanya satu tahun akademik yang bisa aktif dalam satu waktu.'),
        ];
    }
}
