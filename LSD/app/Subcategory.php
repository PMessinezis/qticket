<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model 
{

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