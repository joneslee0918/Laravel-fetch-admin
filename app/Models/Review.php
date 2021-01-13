<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model {
    protected $table = 'review';
    protected $fillable = [
        'id_user',
        'id_review_user',
        'review',
        'score',
        'created_at',
        'updated_at'
    ];

    public function reviewer() {
        return $this->hasOne( 'App\Models\User', 'id', 'id_review_user' );
    }
}
