<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['title', 'image', 'link', 'status', 'order'];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }
}
