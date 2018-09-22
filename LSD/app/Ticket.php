<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model 
{

    protected $table = 'tickets';
    public $timestamps = true;

    public function source()
    {
        return $this->belongsTo('App\Source');
    }

    public function requestedBy()
    {
        return $this->belongsTo('App\User', 'requestedBy_id');
    }

    public function onBehalfOf()
    {
        return $this->belongsTo('App\User', 'onBehalfOf_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Subcategory', 'subcategory_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function parent()
    {
        return $this->belongsTo('App\Ticket', 'parentTicket_id');
    }

    public function assignedGroup()
    {
        return $this->belongsTo('App\Group', 'assignedGroup_id');
    }

    public function assignedResolver()
    {
        return $this->belongsTo('App\Resolver', 'assignedResolver_id');
    }

    public function assignedVendor()
    {
        return $this->belongsTo('App\Vendor', 'assignedVendor_id');
    }

    public function rootCause()
    {
        return $this->belongsTo('App\RootCause', 'rootCause_id');
    }

}