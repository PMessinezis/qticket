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
        return $this->fullname ;
    }

    public function getListnameAttribute(){
        return $this->fullname . " ($this->email)" ;
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

 
    public  function refreshFromLDAP(){
        $ld=$this->ldapinfo();
        if ($ld->count()) {
            $this->firstname=( $ld['givenname'] ?? '' );
            $this->lastname=( $ld['sn'] ?? '' );
            $this->employeeid=( $ld['employeeid'] ?? '' );
            $this->title=( $ld['title'] ?? '' );
            $this->description=( $ld['description'] ?? '' );
            if ($this->description=='') {
                $this->description=( $ld['displayname'] ?? '' );
            }
            $this->email=( $ld['mail'] ?? '' );        
            $this->phone1=( $ld['telephonenumber'] ?? '' );  
            $this->phone2=( $ld['mobile'] ?? '' );
            $this->tmhma=( $ld['department'] ?? '' );
            $adr= $ld['streetaddress'] ?? '' ;
            $city= $ld['l'] ?? '' ;
            if ($adr != '' && $city != '') $adr=$adr . ', ';
            $this->topothesia=  $adr . $city;      
            $this->save();
        }
        return $this;
    }


    public static function fromLDAP($auser){
        $user=self::where('uid',$auser)->first();
        if (! isset($user) ) {
            $user=new User;
            $user->uid=$auser;
            $user->isTempEntry=true;
        }
        $user->refreshFromLDAP();
        return $user;
    }


    public static function refreshAllfromLDAP(){
        $Users= self::all();
        foreach ($Users as $u ) {
            $u->refreshFromLDAP();
        }
    }



   
}
