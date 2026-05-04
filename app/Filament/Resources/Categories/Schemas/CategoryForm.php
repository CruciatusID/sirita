<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Filament\Support\SlugFields;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parent_id')
                    ->label('Kategori Induk')
                    ->relationship(
                        'parent',
                        'name',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->whereNull('parent_id')
                            ->where('is_active', true)
                            ->orderBy('name'),
                        ignoreRecord: true,
                    )
                    ->searchable()
                    ->preload(),
                SlugFields::source(
                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(255),
                ),
                SlugFields::slug(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}
