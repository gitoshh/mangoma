<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Favourite extends Model
{
    protected $table = 'favourites';

    protected $fillable = [
        'music_id',
        'user_id',
    ];

    /**
     * @return HasMany
     */
    public function music(): HasMany
    {
        return $this->hasMany(Music::class, 'id');
    }
}