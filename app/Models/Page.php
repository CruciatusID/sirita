<?php

namespace App\Models;

use App\Models\Concerns\HasGeneratedSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'slug', 'content', 'status', 'seo_title', 'seo_description'])]
class Page extends Model
{
    use HasGeneratedSlug;

    protected function slugSourceColumn(): string
    {
        return 'title';
    }
}
