<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public function getPosts()
    {
        return $this->hasMany('App\Models\Post', 'user_id');
    }

    public function getComments()
    {
        return $this->hasMany('App\Models\Comment', 'owner_id');
    }

    public function getFollowers()
    {
        return $this->hasMany('App\Models\Follower', 'user_id');
    }

    public function getBlockedUsers()
    {
        return $this->hasMany('App\Models\Comment','user_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'api_token',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
