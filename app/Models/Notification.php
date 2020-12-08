<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';
    protected $fillable = [
        'id_snd_user',
        'id_rcv_user',
        'id_type',
        'title',
        'body',
        'type',
        'read_status',
        'created_at',
        'updated_at'
    ];

    public function ads() {
        return $this->hasOne( 'App\Models\Ads', 'id', 'id_type' );
    }

    public function sender() {
        return $this->hasOne( 'App\Models\User', 'id', 'id_snd_user' );
    }

    public function receiver() {
        return $this->hasOne( 'App\Models\Ads', 'id', 'id_rcv_user' );
    }
}
