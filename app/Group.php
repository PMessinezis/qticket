<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;


  

class Group extends Model 
{
  use CrudTrait;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
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