<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    //
    protected $table = 'category';
    protected $fillable = ['order', 'name', 'active', 'etc'];

    public function ads() {
        return $this->hasMany( 'App\Models\Ads', 'id_category', 'id' );
    }
}
