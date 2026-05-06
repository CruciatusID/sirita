<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Support\AdminAccess;
use App\Models\Post;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class PopularPostsWidget extends TableWidget
{
    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return AdminAccess::hasAnyRole(['Super Admin']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Berita Terpopuler')
            ->description('Diurutkan dari jumlah tayangan terbanyak.')
            ->query(fn (): Builder => Post::query()
                ->with(['category', 'author'])
                ->where('status', 'published')
                ->orderByDesc('views')
                ->latest('published_at'))
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->placeholder('-'),
                TextColumn::make('author.name')
                    ->label('Penulis')
                    ->placeholder('-'),
                TextColumn::make('views')
                    ->label('Dibaca')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('likes_count')
                    ->label('Suka')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('shares_count')
                    ->label('Bagikan')
                    ->numeric()
                    ->sortable(),
            ])
            ->recordUrl(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record]))
            ->defaultPaginationPageOption(5);
    }
}
