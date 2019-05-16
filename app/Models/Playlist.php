<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Playlist extends Model
{
    protected $table = 'playlist';

    protected $fillable = [
        'title',
        'creator',
    ];

    public static $rules = [
        'title' => 'required|string'
    ];

    /**
     * The users that belong to the playlist.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * The music that belong to the playlist.
     *
     * @return BelongsToMany
     */
    public function music(): BelongsToMany
    {
        return $this->belongsToMany(Music::class);
    }
}