<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Genre extends Model
{
    protected $fillable = [
        'name',
    ];

    public static $rules = [
        'name' => 'string|required|unique:genres',
    ];

    public function music(): HasMany
    {
        return $this->hasMany(Music::class, 'genreId');
    }

}