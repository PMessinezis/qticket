<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model 
{

    protected $table = 'categories';
    public $timestamps = true;

    public function tickets()
    {
        return $this->hasMany('App\Ticket');
    }

    public function type()
    {
        return $this->belongsTo('App\Type');
    }

}