<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model {
    //
    protected $table = 'app_setting';
    protected $fillable = ['meta_key', 'meta_value'];
}
