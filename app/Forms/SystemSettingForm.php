<?php

namespace App\Forms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class SystemSettingForm
{
    public static function schema(): array
    {
        return [
            // Favicon Section
            Placeholder::make('favicon_heading')
                ->label('')
                ->content(fn () => view('components.form-section-heading', [
                    'title' => 'Favicon',
                    'description' => 'Browser tab icon for your application.',
                ])),

            FileUpload::make('favicon')
                ->label('Favicon')
                ->image()
                ->directory('settings')
                ->imageEditor()
                ->helperText('Recommended size: 32x32 or 64x64 pixels. Supports .ico, .png, .svg'),

            // SEO Metadata Section
            Placeholder::make('seo_heading')
                ->label('')
                ->content(fn () => view('components.form-section-heading', [
                    'title' => 'SEO Metadata',
                    'description' => 'Search engine optimization settings.',
                ])),

            TagsInput::make('meta_keywords')
                ->label('Meta Keywords')
                ->placeholder('Add keyword and press Enter')
                ->separator(',')
                ->helperText('Press Enter to add keywords.'),

            TextInput::make('meta_author')
                ->label('Meta Author')
                ->placeholder('Author Name')
                ->helperText('Default author name for SEO meta tags.'),

            // Analytics Section
            Placeholder::make('analytics_heading')
                ->label('')
                ->content(fn () => view('components.form-section-heading', [
                    'title' => 'Analytics & Tracking',
                    'description' => 'Third-party analytics integration.',
                ])),

            Textarea::make('google_analytics_code')
                ->label('Google Analytics Code')
                ->placeholder('<script>...</script>')
                ->rows(5)
                ->helperText('Paste your Google Analytics or Tag Manager tracking code here. This will be injected in the <head> tag.'),
        ];
    }
}
