<?php

namespace App\Filament\Resources\Units\Schemas;

use App\Filament\Support\SlugFields;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UnitForm
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
                Select::make('type')
                    ->label('Jenis')
                    ->options([
                        'Kantor' => 'Kantor',
                        'KUA' => 'KUA',
                        'Madrasah' => 'Madrasah',
                        'Seksi' => 'Seksi',
                        'Bimas' => 'Bimas',
                        'Penyelenggara' => 'Penyelenggara',
                    ])
                    ->required(),
                Textarea::make('address')
                    ->label('Alamat')
                    ->rows(3),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}
