<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Support\AdminAccess;
use App\Models\Post;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ReviewQueueWidget extends TableWidget
{
    protected static ?int $sort = -1;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return AdminAccess::hasAnyRole(['Super Admin']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Berita Menunggu Review')
            ->description('Daftar naskah yang perlu diperiksa sebelum diterbitkan.')
            ->query(fn (): Builder => Post::query()
                ->with(['category', 'author', 'unit'])
                ->where('status', 'review')
                ->latest('updated_at'))
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(55),
                TextColumn::make('author.name')
                    ->label('Penulis')
                    ->placeholder('-'),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->placeholder('-'),
                TextColumn::make('unit.name')
                    ->label('Unit')
                    ->placeholder('-')
                    ->limit(28),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since()
                    ->sortable(),
            ])
            ->recordUrl(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record]))
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultPaginationPageOption(5);
    }
}
