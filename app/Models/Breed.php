<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Breed extends Model {
    //
    protected $table = 'breed';
    protected $fillable = ['order', 'name', 'active', 'etc'];

    public function ads() {
        return $this->hasMany( 'App\Models\Ads', 'id_breed', 'id' );
    }
}
