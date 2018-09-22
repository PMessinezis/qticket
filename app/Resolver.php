<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;


  
class Resolver extends Model 
{
  use CrudTrait;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $table = 'resolvers';
    protected $appends = array(
         'lastname', 'listname', 'fullname'
     );

    public $timestamps = true;

    public function getUserWithUIDAttribute()
    {
        return $this->user->name . '  ('. $this->user->uid . ')';
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_uid','uid');
    }

    public function tickets()
    {
        return $this->hasMany('App\Ticket', 'assignedResolver_id');
    }


    public function isMemberOf($g) {
        $s=$this->groups->firstWhere('name',$g);
        return isset($s);
    }

    public function isAdmin() {

        $qticketAdminGroup=config('qticket.admins');
        
        $uc=Group::where('name', $qticketAdminGroup)->count();
        if ($uc) {
            $hdGroup=Group::where('name',$qticketAdminGroup)->first();
            if ($hdGroup) $uc=$hdGroup->resolvers()->count();
        }
        return  $uc ? $this->isMemberOf($qticketAdminGroup) : $this->user->id==1;
    }

    public function isReviewer() {

        $qticketReviewersGroup=config('qticket.reviewers');
        
        $uc=Group::where('name',$qticketReviewersGroup)->count();
        if ($uc) {
            $hdGroup=Group::where('name',$qticketReviewersGroup)->first();
            if ($hdGroup) $uc=$hdGroup->resolvers()->count();
        }
        return  $uc ? $this->isMemberOf($qticketReviewersGroup) : false ;
    }


    public function getLastnameAttribute(){
        return User::find($this->user_uid)->lastname;
    }

 
    public function getListnameAttribute(){
        $u= User::find($this->user_uid);
        return $u->lastname . ' ' . $u->email;
    }

    public function getFullnameAttribute(){
        $u= User::find($this->user_uid);
        return $u->fullname;
    }

}