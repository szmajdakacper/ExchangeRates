<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public function lastrates()
    {
        return $this->belongsTo('App\LatestRate', 'code', 'code');
    }
}