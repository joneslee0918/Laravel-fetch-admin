<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boost extends Model {
    //
    protected $table = 'boost';
    protected $fillable = ['id_ads', 'type', 'started_at', 'expired_at'];

    public function ads() {
        return $this->hasOne( 'App\Models\Ads', 'id', 'id_ads' );
    }
}