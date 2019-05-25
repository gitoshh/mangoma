<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    protected $table = 'album';

    protected $fillable = [
        'title','releaseDate', 'artistes',
    ];

    public static $rules = [
        'title'       => 'required|string',
        'releaseDate' => 'required| string',
        'artistes'    => 'string',
    ];

    /**
     * The products that belong to the shop.
     */
    public function music(): HasMany
    {
        return $this->hasMany(Music::class);
    }
}
