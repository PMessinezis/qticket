<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;




class Subcategory extends Model 
{
    use CrudTrait;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $table = 'subcategories';
    public $timestamps = true;

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function tickets()
    {
        return $this->hasMany('App\Ticket', 'subcategory_id');
    }

}