<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model 
{

    protected $table = 'attachments';
    public $timestamps = true;

    public function ticket()
    {
        return $this->belongsTo('App\Ticket');
    }

    public function uploadedByUser()
    {
        return $this->hasOne('App\User', 'uploadedByUser_id');
    }

}