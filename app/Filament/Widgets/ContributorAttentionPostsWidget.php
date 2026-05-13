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

class ContributorAttentionPostsWidget extends TableWidget
{
    protected static ?int $sort = -1;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return AdminAccess::hasAnyRole(['Kontributor'])
            && ! AdminAccess::hasAnyRole(AdminAccess::EDITORIAL);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Naskah Perlu Ditindaklanjuti')
            ->description('Draft belum terkirim dan naskah ditolak akan muncul di sini.')
            ->query(fn (): Builder => Post::query()
                ->with(['category'])
                ->where('user_id', auth()->id())
                ->whereIn('status', ['draft', 'rejected'])
                ->latest('updated_at'))
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(60),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->placeholder('-'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
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
