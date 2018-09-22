<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model 
{

    protected $table = 'groups';
    public $timestamps = true;

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function resolvers()
    {
        return $this->belongsToMany('App\Resolver');
    }

    public function tickets()
    {
        return $this->hasMany('App\Ticket', 'assignedGroup_id');
    }

}