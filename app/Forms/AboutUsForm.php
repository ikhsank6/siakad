<?php

namespace App\Forms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;

class AboutUsForm
{
    public static function schema(): array
    {
        return [
            Section::make('Company Information')
                ->schema([
                    TextInput::make('company_name')
                        ->label('Company Name')
                        ->required()
                        ->maxLength(255),

                    RichEditor::make('description')
                        ->label('Description')
                        ->required()
                        ->columnSpanFull(),

                    FileUpload::make('logo')
                        ->label('Logo')
                        ->image()
                        ->directory('about')
                        ->columnSpanFull(),
                ]),

            Section::make('Contact Information')
                ->schema([
                    TextInput::make('address')
                        ->label('Address')
                        ->maxLength(500),

                    TextInput::make('phone')
                        ->label('Phone')
                        ->tel()
                        ->maxLength(50),

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255),

                    TextInput::make('whatsapp')
                        ->label('WhatsApp')
                        ->maxLength(50),
                ]),

            Section::make('Social Media')
                ->schema([
                    TextInput::make('facebook')
                        ->label('Facebook')
                        ->url()
                        ->maxLength(255),

                    TextInput::make('instagram')
                        ->label('Instagram')
                        ->url()
                        ->maxLength(255),

                    TextInput::make('twitter')
                        ->label('Twitter/X')
                        ->url()
                        ->maxLength(255),

                    TextInput::make('youtube')
                        ->label('YouTube')
                        ->url()
                        ->maxLength(255),

                    TextInput::make('linkedin')
                        ->label('LinkedIn')
                        ->url()
                        ->maxLength(255),
                ]),

            Section::make('Location')
                ->schema([
                    TextInput::make('map_url')
                        ->label('Google Maps Link')
                        ->url()
                        ->placeholder('https://www.google.com/maps/place/...')
                        ->helperText('Paste the Google Maps link. Coordinates will be auto-extracted.')
                        ->columnSpanFull(),

                    TextInput::make('latitude')
                        ->label('Latitude')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->helperText('Auto-filled from Google Maps link'),

                    TextInput::make('longitude')
                        ->label('Longitude')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->helperText('Auto-filled from Google Maps link'),
                ]),

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
