<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Music extends Model
{
    /**
     * @var string
     */
    protected $table = 'music';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'originalName', 'extension', 'location', 'uniqueName', 'artistes',
    ];

    /**
     * @var array
     */
    public static $rules = [
        'title' => 'required|string',
        ];

    /**
     * The products that belong to the shop.
     */
    public function albums(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }
}
