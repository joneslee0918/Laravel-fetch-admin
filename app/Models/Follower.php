<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model {
    protected $table = 'follower';
    protected $fillable = [
        'id_user',
        'id_follow_user',
        'etc',
        'created_at',
        'updated_at'
    ];

    public function follower() {
        return $this->hasOne( 'App\Models\User', 'id', 'id_follow_user' );
    }
}
