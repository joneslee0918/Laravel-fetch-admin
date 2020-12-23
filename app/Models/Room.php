<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model {
    //
    protected $table = 'room';
    protected $fillable = ['id_ads', 'id_user_sell', 'id_user_buy'];

    public function ads() {
        return $this->hasOne( 'App\Models\Ads', 'id', 'id_ads' );
    }

    public function buyer() {
        return $this->hasOne( 'App\Models\User', 'id', 'id_user_buy' );
    }

    public function seller() {
        return $this->hasOne( 'App\Models\User', 'id', 'id_user_sell' );
    }

    public function message() {
        return $this->hasMany( 'App\Models\Chat', 'id_room', 'id' );
    }
}
