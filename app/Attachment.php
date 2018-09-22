<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Log;

class Attachment extends Model 
{

    protected $table = 'attachments';
    public $timestamps = true;
    protected $appends=['fileLink', 'uploadedByUserName'];

    public function ticket()
    {
        return $this->belongsTo('App\Ticket');
    }

    public function uploadedByUser()
    {
        return $this->belongsTo('App\User', 'uploadedByUser_uid');
    }

    public function getUploadedByUserNameAttribute()
    {
        return $this->uploadedByUser_uid ? $this->uploadedByUser->fullname : '';
    }

    public function getFileLinkAttribute(){
        return  $this->htmlLink();
    }

    public function htmlLink(){
        return '/attached/' . $this->id . '/'. $this->originalName ;
    }

    public function htmlAnchor(){
        $u=url( $this->htmlLink());
        // Log::error("Attachment URL", [ 'h' => $this->htmlLink() , 'u' => $u] );
        // // if (Auth::user()->uid=='u96484') {
        // //     dd($u);
        // // }
        return '<a href="' . $u . '" target="_blank" > <i class="fas fa-paperclip" aria-hidden="true"></i>'  . $this->originalName  . '</a>';
    }

    public function download(){

        $headers = [
              'Content-Type' => $this->mimeType,
           ];
          return response()->file(storage_path('app/' . $this->filename , $headers));
    }

}