<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;



class Vendor extends Model 
{

    use CrudTrait;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $table = 'vendors';
    public $timestamps = true;

    public function tickets()
    {
        return $this->hasMany('App\Ticket', 'assignedVendor_id');
    }

}