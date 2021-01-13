<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasApiTokens, Notifiable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'avatar',
        'name',
        'email',
        'password',
        'phonenumber',
        'device_token',
        'iphone_device_token',
        'customer_id',
        'terms',
        'active',
        'role',
        'is_social'
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'password',
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

    public function meta() {
        return $this->hasMany( 'App\Models\UserMeta', 'id_user', 'id' );
    }

    public function ads() {
        return $this->hasMany( 'App\Models\Ads', 'id_user', 'id' );
    }

    public function follower() {
        return $this->hasMany( 'App\Models\Follower', 'id_user', 'id' );/*users who follow this user*/
    }

    public function following() {
        return $this->hasMany( 'App\Models\Follower', 'id_follow_user', 'id' );/*users who this user followed*/
    }

    public function review() {
        return $this->hasMany( 'App\Models\review', 'id_user', 'id' );
    }
}
