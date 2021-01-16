<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model {
    //
    protected $table = 'subscription';
    protected $fillable = ['id_user', 'type', 'started_at', 'expired_at'];

    public function user() {
        return $this->hasOne( 'App\Models\User', 'id', 'id_user' );
    }
}