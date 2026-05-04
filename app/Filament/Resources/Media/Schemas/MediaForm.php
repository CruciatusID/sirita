<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('filename')
                    ->label('Nama File')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('path')
                    ->label('File')
                    ->directory('media')
                    ->required(),
                TextInput::make('mime_type')
                    ->label('MIME Type')
                    ->maxLength(255),
                TextInput::make('size')
                    ->label('Ukuran')
                    ->numeric()
                    ->default(0),
                Hidden::make('uploaded_by')
                    ->default(fn () => auth()->id()),
            ]);
    }
}
