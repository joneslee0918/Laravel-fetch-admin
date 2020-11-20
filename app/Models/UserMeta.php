<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model {
    //
    protected $table = 'user_meta';
    protected $fillable = ['id_user', 'meta_key', 'meta_value'];
}
