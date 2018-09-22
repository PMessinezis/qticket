<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model 
{

    protected $table = 'vendors';
    public $timestamps = true;

    public function tickets()
    {
        return $this->hasMany('App\Ticket', 'assignedVendor_id');
    }

}