<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model {
    //
    protected $table = 'chat';
    protected $fillable = [
        'id_ads',
        'id_user_snd',
        'id_user_rcv',
        'message',
        'attach_file',
        'message_type',
        'read_status',
        'last_seen_time',
        'created_at',
        'updated_at'
    ];

    public function sender() {
        return $this->hasOne( 'App\Models\User', 'id', 'id_user_snd' );
    }

    public function receiver() {
        return $this->hasOne( 'App\Models\User', 'id', 'id_user_rcv' );
    }

    public function ads() {
        return $this->hasOne( 'App\Models\Ads', 'id', 'id_ads' );
    }
}
