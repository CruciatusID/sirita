<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Support\MediaImageSelect;
use App\Filament\Support\RichContentEditor;
use App\Filament\Support\SlugFields;
use App\Models\Media;
use App\Models\Post;
use App\Models\Unit;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Database\Eloquent\Builder;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(fn () => auth()->id()),
                SlugFields::source(
                    TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(255),
                ),
                SlugFields::slug(),
                Textarea::make('excerpt')
                    ->label('Ringkasan')
                    ->rows(3),
                RichContentEditor::make('content', 'Isi Berita', 'media')
                    ->required()
                    ->columnSpanFull(),
                MediaImageSelect::make('featured_image', 'Gambar Utama')
                    ->live()
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        if (blank($state)) {
                            return;
                        }

                        $media = Media::where('path', $state)->first();

                        if ($media?->caption) {
                            $set('featured_image_caption', $media->caption);
                        }
                    }),
                TextInput::make('featured_image_caption')
                    ->label('Keterangan Gambar')
                    ->placeholder('Otomatis terisi dari Media, tapi bisa diubah...')
                    ->afterStateHydrated(function (TextInput $component, $state, ?Post $record) {
                        if (blank($state) && $record?->featured_image) {
                            $mediaCaption = Media::where('path', $record->featured_image)->value('caption');
                            if ($mediaCaption) {
                                $component->state($mediaCaption);
                            }
                        }
                    })
                    ->maxLength(255),
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship(
                        'category',
                        'name',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->where('is_active', true)
                            ->with('parent')
                            ->orderBy('parent_id')
                            ->orderBy('name'),
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record): string => $record->full_name)
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('unit_id')
                    ->label('Unit Kerja')
                    ->relationship(
                        'unit',
                        'name',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->where('is_active', true)
                            ->orderBy('type')
                            ->orderBy('name'),
                    )
                    ->searchable()
                    ->preload(),
                Select::make('tags')
                    ->label('Tag')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        SlugFields::source(
                            TextInput::make('name')
                                ->label('Nama Tag')
                                ->required()
                                ->maxLength(255),
                        ),
                        SlugFields::slug(),
                    ]),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'review' => 'Menunggu Review',
                        'published' => 'Terbit',
                        'rejected' => 'Ditolak',
                    ])
                    ->default('draft')
                    ->required(),
                DateTimePicker::make('published_at')
                    ->label('Tanggal Terbit')
                    ->default(now()),
                TextInput::make('seo_title')
                    ->label('SEO Title')
                    ->maxLength(255),
                Textarea::make('seo_description')
                    ->label('SEO Description')
                    ->rows(3),
                MediaImageSelect::make('og_image', 'Gambar Share'),
            ]);
    }
}
