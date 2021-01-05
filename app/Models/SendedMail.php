<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendedMail extends Model
{
    //
    protected $table = 'sended_mail';
    protected $fillable = ['userid', 'title', 'content'];
    
    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'userid');
    }
}