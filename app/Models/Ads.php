<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model {
    //
    protected $table = 'ads';
    protected $fillable = [
        'id_user',
        'id_category',
        'id_breed',
        'age',
        'gender', 'price',
        'location'.
        'lat',
        'long',
        'description',
        'status'
    ];
}
