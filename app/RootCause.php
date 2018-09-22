<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;



class RootCause extends Model 
{
    use CrudTrait;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $table = 'rootcauses';
    public $timestamps = true;

    public function tickets()
    {
        return $this->hasMany('App\Ticket', 'rootCause_id');
    }

}