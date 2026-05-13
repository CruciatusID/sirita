<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(60)
                    ->description(function ($record): HtmlString {
                        $editUrl = e(PostResource::getUrl('edit', ['record' => $record]));
                        $links = [
                            "<a href=\"{$editUrl}\" class=\"text-xs font-medium text-primary-600 hover:underline\">Edit</a>",
                        ];

                        if ($record->status === 'published' && filled($record->slug)) {
                            $portalUrl = e(route('posts.show', $record));
                            $links[] = "<a href=\"{$portalUrl}\" target=\"_blank\" rel=\"noopener noreferrer\" class=\"text-xs font-medium text-primary-600 hover:underline\">Lihat di Portal</a>";

                            $storyUrl = e(route('admin.posts.story', $record));
                            $links[] = "<a href=\"{$storyUrl}\" target=\"_blank\" rel=\"noopener noreferrer\" class=\"text-xs font-medium text-primary-600 hover:underline\">Story IG</a>";
                        }

                        return new HtmlString(implode('<span class="mx-1 text-gray-300">|</span>', $links));
                    })
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('author.name')
                    ->label('Penulis')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('editor.name')
                    ->label('Editor')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'review' => 'warning',
                        'draft' => 'gray',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('Terbit')
                    ->dateTime()
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('views')
                    ->label('Dibaca')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('unit_id')
                    ->label('Unit Kerja')
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('user_id')
                    ->label('Penulis')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('editor_user_id')
                    ->label('Editor')
                    ->relationship('editor', 'name')
                    ->searchable()
                    ->preload(),
                Filter::make('published_at')
                    ->label('Tanggal Terbit')
                    ->form([
                        DatePicker::make('published_from')
                            ->label('Dari'),
                        DatePicker::make('published_until')
                            ->label('Sampai'),
                    ])
                    ->query(function ($query, array $data): void {
                        $query
                            ->when(
                                filled($data['published_from'] ?? null),
                                fn ($query) => $query->whereDate('published_at', '>=', $data['published_from']),
                            )
                            ->when(
                                filled($data['published_until'] ?? null),
                                fn ($query) => $query->whereDate('published_at', '<=', $data['published_until']),
                            );
                    }),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
