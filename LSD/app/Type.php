<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model 
{

    protected $table = 'types';
    public $timestamps = true;

    public function categories()
    {
        return $this->hasMany('App\Category');
    }

    public function tickets()
    {
        return $this->hasManyThrough('App\Ticket', 'App\Category');
    }

}