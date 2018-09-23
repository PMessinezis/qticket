<?php

namespace App;

use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\Crypt;


class User extends Authenticatable
{
    use Notifiable;

    use CrudTrait;


    protected $table = 'users';
    public $timestamps = true;
    public $incrementing = false;
    public $primaryKey = 'uid';
    protected $appends = array('isResolver', 'isAdmin' , 'name', 'listname' , 'fullname', 'firstlastname');
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
  
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getFullnameAttribute(){
        if ($this->lastname && $this->firstname) {
            $n=$this->lastname . ', ' . $this->firstname;
        } else {
            $n=$this->lastname . $this->firstname;
        }
        return $n;
    }

    public function getFirstlastnameAttribute(){
        $n=$this->firstname . ' ' . $this->lastname;
        return $n;
    }
    
    public function getNameAttribute(){
        return $this->fullname . " ($this->uid)";
    }

    public function getListnameAttribute(){
        return $this->fullname . " ($this->uid) " . $this->email;
    }

    public function resolver()
    {
        return $this->hasOne('App\Resolver','user_uid','uid');
    }

    public function isAdmin()
    {
        if ( $this->resolver ) {
            return $this->resolver()->first()->isAdmin();
        } else {
            return $this->id==1 || User::all()->count()==0;
        }
    }

    public function isReviewer()
    {
        if ( $this->resolver ) {
            return $this->resolver->isReviewer();
        } else {
            return false;
        }
    }

    public function getIsResolverAttribute()
    {
        if ( $this->resolver ) {
            return true; //$this->resolver->isActive ; //&& $this->id!=1;
        } else {
            return false;
        }
    }

    public function getIsAdminAttribute()
    {
         return $this->isAdmin();
    }

        public function getIsReviewerAttribute()
    {
         return $this->isReviewer();
    }

    public function ldapinfo()
    {
        return ldapinfo($this->uid);
    }

}
