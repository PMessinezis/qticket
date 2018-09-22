<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resolver extends Model 
{

    protected $table = 'resolvers';
    public $timestamps = true;

    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function tickets()
    {
        return $this->hasMany('App\Ticket', 'assignedResolver_id');
    }

}