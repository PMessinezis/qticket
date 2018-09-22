<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Source extends Model 
{

    protected $table = 'sources';
    public $timestamps = true;

    public function tickets()
    {
        return $this->hasMany('App\Ticket');
    }

}