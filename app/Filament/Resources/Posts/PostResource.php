<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Support\AdminAccess;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Resources\Posts\Schemas\PostForm;
use App\Filament\Resources\Posts\Tables\PostsTable;
use App\Models\Post;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $modelLabel = 'Berita';

    protected static ?string $pluralModelLabel = 'Berita';

    protected static ?string $navigationLabel = 'Berita';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return AdminAccess::hasAnyRole(AdminAccess::CONTRIBUTORS);
    }

    public static function form(Schema $schema): Schema
    {
        return PostForm::configure($schema);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['category.parent', 'author', 'editor']);
    }

    public static function getTabs(): array
    {
        $counts = Post::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $total = Post::query()->count();

        return [
            'all' => Tab::make('Semua')
                ->badge((string) $total)
                ->modifyQueryUsing(fn (Builder $query) => $query),
            'draft' => Tab::make('Draft')
                ->badge((string) ($counts['draft'] ?? 0))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'draft')),
            'review' => Tab::make('Review')
                ->badge((string) ($counts['review'] ?? 0))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'review')),
            'published' => Tab::make('Terbit')
                ->badge((string) ($counts['published'] ?? 0))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'published')),
            'rejected' => Tab::make('Ditolak')
                ->badge((string) ($counts['rejected'] ?? 0))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected')),
        ];
    }

    public static function table(Table $table): Table
    {
        return PostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
