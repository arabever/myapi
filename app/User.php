<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Airlock\HasApiTokens;
use App\GetSubNewUser;
use App\TempCurrentUser;
use App\FirstSubSuccessScrap;
use App\EmptyUser;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function submissions()
    {
        return $this->hasMany('App\Submission');
    }
    public function initialSub()
    {
        return $this->hasOne('App\GetSubNewUser', 'user_id');
    }
    public function firstSub() // first submission in success scrap
    {
        return $this->hasOne('App\FirstSubSuccessScrap', 'user_id');
    }
    public function temp() // temp record will be created if current user can't complete his submissions and store last submission before start last fail update
    {
        return $this->hasOne('App\TempCurrentUser', 'user_id');
    }
    public function empty() // has No submissions
    {
        return $this->hasOne('App\EmptyUser', 'user_id');
    }

}
