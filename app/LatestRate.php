<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LatestRate extends Model
{
    public function currency()
    {
        return $this->hasOne('App\Currency', 'code', 'code');
    }
}