<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;


  
class Type extends Model 
{
  use CrudTrait;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

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