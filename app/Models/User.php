<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @var array
     */
    public static $userRules = [
            'email'    => 'required|unique:users,email',
            'password' => 'required|min:6',
            'name'     => 'required|string',
        ];

    /**
     * @var array
     */
    public static $loginRules = [
        'email'    => 'required|email',
        'password' => 'required|min:6',
    ];

    /**
     * The playlist that belong to the user.
     *
     * @return BelongsToMany
     */
    public function playlist(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class);
    }
}
