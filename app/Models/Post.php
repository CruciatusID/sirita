<?php

namespace App\Models;

use App\Models\Concerns\HasGeneratedSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable([
    'title',
    'slug',
    'excerpt',
    'content',
    'featured_image',
    'featured_image_caption',
    'category_id',
    'user_id',
    'editor_user_id',
    'unit_id',
    'status',
    'published_at',
    'seo_title',
    'seo_description',
    'og_image',
    'views',
    'likes_count',
    'shares_count',
])]
class Post extends Model
{
    use HasGeneratedSlug, LogsActivity;

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    protected function slugSourceColumn(): string
    {
        return 'title';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editor_user_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getFeaturedImageCaptionAttribute($value): ?string
    {
        if (filled($value)) {
            return $value;
        }

        if (blank($this->featured_image)) {
            return null;
        }

        return Media::where('path', $this->featured_image)->value('caption');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
}
