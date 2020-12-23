<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model {
    //
    protected $table = 'chat';
    protected $fillable = [
        'id_room',
        'id_user_snd',
        'message',
        'attach_file',
        'message_type',
        'read_status',
        'created_at',
        'updated_at'
    ];

    public function sender() {
        return $this->hasOne( 'App\Models\User', 'id', 'id_user_snd' );
    }

    public function room() {
        return $this->hasOne( 'App\Models\Room', 'id', 'id_room' );
    }
}