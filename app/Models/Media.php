<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

#[Fillable(['filename', 'caption', 'path', 'mime_type', 'size', 'uploaded_by'])]
class Media extends Model
{
    protected static function booted(): void
    {
        static::saving(function (Media $media): void {
            if (blank($media->filename) && filled($media->path)) {
                $media->filename = basename($media->path);
            }

            if (blank($media->uploaded_by) && auth()->check()) {
                $media->uploaded_by = auth()->id();
            }

            if (blank($media->path) || (! Storage::disk('public')->exists($media->path))) {
                return;
            }

            $fullPath = Storage::disk('public')->path($media->path);
            $mimeType = Storage::disk('public')->mimeType($media->path) ?: null;

            if ($media->isDirty('path') && filled($mimeType) && str_starts_with($mimeType, 'image/')) {
                ImageOptimizer::optimize($fullPath);
            }

            $media->mime_type = $mimeType;
            $media->size = Storage::disk('public')->size($media->path);
        });
    }

    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
