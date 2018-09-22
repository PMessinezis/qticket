<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Source extends Model 
{

	use CrudTrait;

    protected $table = 'sources';
    public $timestamps = true;

	protected $guarded = [
	    'id',
	    'created_at',
	    'updated_at',
	    'deleted_at'
	];

    public function tickets()
    {
        return $this->hasMany('App\Ticket');
    }

}