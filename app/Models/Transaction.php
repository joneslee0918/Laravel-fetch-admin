<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    protected $table = 'transaction';
    protected $fillable = [
        'id_customer',
        'type',
        'id_type',
        'amount',
        'description',
        'created_at',
        'updated_at'
    ];

    public function user() {
        return $this->hasOne( 'App\Models\User', 'customer_id', 'id_customer' );
    }
}
