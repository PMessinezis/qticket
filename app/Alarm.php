<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alarm extends Model
{
    public function ticket()
    {
        return $this->belongsTo('App\Ticket');
    }
}
