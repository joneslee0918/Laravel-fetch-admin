<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdsMeta extends Model
{
    //
    protected $table = "ads_meta";
    protected $fillable = ['id_ads', 'meta_key', 'meta_value'];
}
