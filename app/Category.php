<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Backpack\CRUD\CrudTrait;




class Category extends Model 
{
    use CrudTrait;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $table = 'categories';
    public $timestamps = true;

    public function tickets()
    {
        return $this->hasMany('App\Ticket');
    }

    public function type()
    {
        return $this->belongsTo('App\Type');
    }

    public function defaultGroup()
    {
        return $this->belongsTo('App\Group','defaultGroup_id');
    }

}