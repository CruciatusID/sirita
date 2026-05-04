<?php

namespace App\Filament\Support;

use App\Models\Media;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaImageSelect
{
    public static function make(string $name, string $label, string $directory = 'media'): Select
    {
        return Select::make($name)
            ->label($label)
            ->options(fn (): array => self::imageOptions())
            ->allowHtml()
            ->searchable()
            ->preload()
            ->createOptionModalHeading("Upload {$label} Baru")
            ->createOptionForm([
                TextInput::make('filename')
                    ->label('Nama Tampilan')
                    ->helperText('Opsional. Jika dikosongkan, sistem memakai nama file.')
                    ->maxLength(255),
                FileUpload::make('path')
                    ->label('File Gambar')
                    ->disk('public')
                    ->image()
                    ->directory($directory)
                    ->getUploadedFileNameForStorageUsing(
                        fn (TemporaryUploadedFile $file): string => StoredFileName::uniqueFromUpload($file, $directory),
                    )
                    ->imageEditor()
                    ->required(),
            ])
            ->createOptionUsing(function (array $data): string {
                $path = (string) $data['path'];

                $media = Media::create([
                    'filename' => filled($data['filename'] ?? null) ? $data['filename'] : basename($path),
                    'path' => $path,
                    'uploaded_by' => auth()->id(),
                ]);

                return $media->path;
            });
    }

    public static function imageOptions(): array
    {
        return Media::query()
            ->where(function ($query): void {
                $query
                    ->where('mime_type', 'like', 'image/%')
                    ->orWhere('path', 'like', '%.jpg')
                    ->orWhere('path', 'like', '%.jpeg')
                    ->orWhere('path', 'like', '%.png')
                    ->orWhere('path', 'like', '%.webp')
                    ->orWhere('path', 'like', '%.gif');
            })
            ->orderByDesc('created_at')
            ->get()
            ->mapWithKeys(fn (Media $media): array => [
                $media->path => self::imageOptionLabel($media),
            ])
            ->all();
    }

    public static function imageOptionLabel(Media $media): string
    {
        $url = e(Storage::disk('public')->url($media->path));
        $filename = e($media->filename);

        return <<<HTML
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <img src="{$url}" alt="{$filename}" style="height:3rem;width:4.5rem;object-fit:cover;border-radius:0.375rem;border:1px solid rgb(229 231 235);" />
            <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{$filename}</span>
        </div>
        HTML;
    }
}
