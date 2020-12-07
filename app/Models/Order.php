<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $table = 'order';
    protected $fillable = [
        'id_ads',
        'id_order_user',
        'name',
        'email',
        'phonenumber',
        'description',
        'status',
        'etc',
        'created_at',
        'updated_at'
    ];

    public function orderUser() {
        return $this->hasOne( 'App\Models\User', 'id', 'id_order_user' );
    }

    public function ads() {
        return $this->hasMany( 'App\Models\Ads', 'id', 'id_ads' );
    }
}
