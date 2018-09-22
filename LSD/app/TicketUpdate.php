<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketUpdate extends Model 
{

    protected $table = 'ticketUpdates';
    public $timestamps = true;

    public function ticket()
    {
        return $this->belongsTo('App\Ticket');
    }

    public function fromStatus()
    {
        return $this->belongsTo('App\Status', 'fromStatus_id');
    }

    public function toStatus()
    {
        return $this->belongsTo('App\Status', 'toStatus_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updatedBy_id');
    }

}