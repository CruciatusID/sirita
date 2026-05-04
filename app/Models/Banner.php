<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'image', 'link', 'status', 'order'])]
class Banner extends Model
{
    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }
}
