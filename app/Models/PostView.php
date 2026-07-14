<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostView extends Model
{
    /**
     * Disable standard updated_at column since we only track creation time.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'ip_address',
        'user_agent',
        'referrer',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
