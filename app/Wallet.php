<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $primaryKey = null;

    public $incrementing = false;
    
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}