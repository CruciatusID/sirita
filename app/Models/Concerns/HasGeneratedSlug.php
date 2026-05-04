<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasGeneratedSlug
{
    protected static function bootHasGeneratedSlug(): void
    {
        static::saving(function (Model $model): void {
            if (filled($model->slug)) {
                $model->slug = Str::slug($model->slug);

                return;
            }

            $model->slug = static::makeUniqueSlug((string) $model->{$model->slugSourceColumn()});
        });
    }

    protected function slugSourceColumn(): string
    {
        return 'name';
    }

    protected static function makeUniqueSlug(string $source): string
    {
        $base = Str::slug($source) ?: Str::random(8);
        $slug = $base;
        $suffix = 2;

        while (static::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
