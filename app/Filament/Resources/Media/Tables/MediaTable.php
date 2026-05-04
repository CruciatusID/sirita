<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('filename')
                    ->label('Nama File')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mime_type')
                    ->label('MIME')
                    ->searchable(),
                TextColumn::make('size')
                    ->label('Ukuran')
                    ->formatStateUsing(fn (?int $state): string => $state ? number_format($state / 1024, 1).' KB' : '-')
                    ->sortable(),
                TextColumn::make('uploader.name')
                    ->label('Pengunggah')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
