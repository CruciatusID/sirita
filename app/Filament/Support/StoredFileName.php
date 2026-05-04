<?php

namespace App\Filament\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class StoredFileName
{
    public static function uniqueFromUpload(TemporaryUploadedFile $file, string $directory, string $disk = 'public'): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'file');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $baseName = Str::slug($originalName) ?: Str::random(8);
        $fileName = "{$baseName}.{$extension}";
        $suffix = 2;

        while (Storage::disk($disk)->exists(trim("{$directory}/{$fileName}", '/'))) {
            $fileName = "{$baseName}-{$suffix}.{$extension}";
            $suffix++;
        }

        return $fileName;
    }
}
