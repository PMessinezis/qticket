<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RootCause extends Model 
{

    protected $table = 'rootCauses';
    public $timestamps = true;

    public function tickets()
    {
        return $this->hasMany('App\Ticket', 'rootCause_id');
    }

}