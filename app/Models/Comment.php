<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'comment','rating', 'userId', 'musicId',
    ];

    public static $rules = [
        'comment' => 'string',
        'rating'  => 'integer',
    ];

    /**
     * The products that belong to the shop.
     */
    public function music(): BelongsTo
    {
        return $this->belongsTo(Music::class);
    }

}