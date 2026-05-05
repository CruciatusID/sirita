<?php

namespace App\Filament\Resources\Media\Schemas;

use App\Filament\Support\StoredFileName;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('filename')
                    ->label('Nama Tampilan')
                    ->helperText('Opsional. Jika dikosongkan, sistem memakai nama file.')
                    ->maxLength(255),
                TextInput::make('caption')
                    ->label('Keterangan / Caption')
                    ->maxLength(255),
                FileUpload::make('path')
                    ->label('File Gambar')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                    ->disk('public')
                    ->directory('media')
                    ->getUploadedFileNameForStorageUsing(
                        fn (TemporaryUploadedFile $file): string => StoredFileName::uniqueFromUpload($file, 'media'),
                    )
                    ->maxSize(300)
                    ->required(),
                Placeholder::make('mime_type_preview')
                    ->label('MIME Type')
                    ->content(fn (Get $get): string => self::fileMimeType($get('path')) ?? '-'),
                Placeholder::make('size_preview')
                    ->label('Ukuran')
                    ->content(fn (Get $get): string => self::fileSize($get('path')) ?? '-'),
            ]);
    }

    protected static function fileMimeType(?string $path): ?string
    {
        if (blank($path) || (! Storage::disk('public')->exists($path))) {
            return null;
        }

        return Storage::disk('public')->mimeType($path);
    }

    protected static function fileSize(?string $path): ?string
    {
        if (blank($path) || (! Storage::disk('public')->exists($path))) {
            return null;
        }

        $bytes = Storage::disk('public')->size($path);

        return number_format($bytes / 1024, 1).' KB';
    }
}
