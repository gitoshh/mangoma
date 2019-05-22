<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Cashier\Billable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable, EntrustUserTrait, Billable;

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

    protected $dates = [
        'trial_ends_at',
        'subscription_ends_at'
    ];

    /**
     * @var array
     */
    public static $userRules = [
            'email'    => 'required|unique:users,email',
            'password' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%@*&S]).*$/',
            'name'     => 'required|string',
        ];

    /**
     * @var array
     */
    public static $loginRules = [
        'email'    => 'required|email',
        'password' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%@*&]).*$/',
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
