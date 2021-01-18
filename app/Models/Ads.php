<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model {
    //
    protected $table = 'ads';
    protected $fillable = [
        'id_user',
        'id_category',
        'id_breed',
        'gender',
        'age',
        'unit',
        'price',
        'lat',
        'long',
        'short_location',
        'long_location',
        'description',
        'status',
        'likes',
        'views',
        'created_at',
        'updated_at'
    ];

    public function user() {
        return $this->hasOne( 'App\Models\User', 'id', 'id_user' );
    }

    public function category() {
        return $this->hasOne( 'App\Models\Category', 'id', 'id_category' );
    }

    public function breed() {
        return $this->hasOne( 'App\Models\Breed', 'id', 'id_breed' );
    }

    public function meta() {
        return $this->hasMany( 'App\Models\AdsMeta', 'id_ads', 'id' );
    }

    public function boost() {
        return $this->hasMany( 'App\Models\Boost', 'id_ads', 'id' );
    }
}
