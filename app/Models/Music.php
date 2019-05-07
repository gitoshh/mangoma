<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        'title', 'originalName', 'extension', 'location', 'uniqueName'
    ];

    /**
     * @var array
     */
    public static $rules = [
        'title'         => 'required|string',
        ];
}