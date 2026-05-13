<?php

namespace App\Models;

use App\Models\Concerns\HasGeneratedSlug;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasGeneratedSlug;

    protected $fillable = ['title', 'slug', 'content', 'status', 'seo_title', 'seo_description'];

    protected function slugSourceColumn(): string
    {
        return 'title';
    }
}
