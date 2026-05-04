<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Filament\Support\SlugFields;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SlugFields::source(
                    TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(255),
                ),
                SlugFields::slug(),
                RichEditor::make('content')
                    ->label('Konten')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Terbit',
                    ])
                    ->default('draft')
                    ->required(),
                TextInput::make('seo_title')
                    ->label('SEO Title')
                    ->maxLength(255),
                Textarea::make('seo_description')
                    ->label('SEO Description')
                    ->rows(3),
            ]);
    }
}
