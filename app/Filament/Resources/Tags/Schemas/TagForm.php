<?php

namespace App\Filament\Resources\Tags\Schemas;

use App\Filament\Support\SlugFields;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SlugFields::source(
                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(255),
                ),
                SlugFields::slug(),
            ]);
    }
}
