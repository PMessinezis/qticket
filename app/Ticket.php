<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Carbon\Carbon;
use Auth;
use Mail;
use App\Mail\TicketOpened;
use App\Mail\TicketClosed;
use App\Mail\TicketUpdatedByUser;

class Ticket extends Model 
{

    use CrudTrait;

    protected $table = 'tickets';
    public $timestamps = true;
    protected $dates=['openedDateTime', 'closedDateTime', 'vendorOpenedDate', 'vendorClosedDate', 'updated_at'];
    protected $appends=['openedOn', 'statusText' , 'onBehalfOfName', 'assignedGroupName', 'refid' , 'lastUpdate', 'vendorOpened', 'vendorClosed'];
    public static $snakeAttributes = false;
    public $assignedGroup_id_Changed = false;
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    public function source()
    {
        return $this->belongsTo('App\Source');
    }

    public function requestedBy()
    {
        return $this->belongsTo('App\User', 'requestedBy_uid', 'uid');
    }

    public function onBehalfOf()
    {
        return $this->belongsTo('App\User', 'onBehalfOf_uid','uid');
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

    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }

    public function updates()
    {
        return $this->hasMany('App\TicketUpdate')->orderBy('id','desc');
    }

    public function lastUpdatedByResolver()
    {
        return $this->belongsTo('App\User', 'lastUpdatedByResolver_uid', 'uid');
    }



    public function addUpdate($comment, $attachment_id, $changes , $NEWTICKET=false){
        $user=Auth::user();
        $resolverUser=null;

        $helpdeskEmail= trim(config('qticket.helpdeskEmail'));
        $originalAssignedGroup_id=$this->getOriginal('assignedGroup_id');

        if ( $this->assignedResolver_id || $this->lastUpdatedByResolver_uid ) {
            $resolverUser= $this->assignedResolver_id ? $this->assignedResolver->user : $this->lastUpdatedByResolver ;
        } 
        
        $userTicket =  ( $this->onBehalfOf_uid==$user->uid )  ;

        $update=new TicketUpdate;
        $now=Carbon::now();
        $update->updatedBy_uid=$user->uid;
        $update->updatedDateTime=$now;
        $update->comment=$comment;
        if ($changes) $update->setChanges($changes);
        if ($attachment_id) $update->attachment_id=$attachment_id;
        $update->ticket_id=$this->id;

        $update->save();
        $this->updated_at=$now; 
        $this->save(['timestamps' => false]); 
        $this->fresh();
        
        $TICKETCLOSED=false;

    
        $f=collect($changes)->first(function ($rec, $key) {
            return $rec['field'] == 'status_id';
        });
        if ($f) {
            $ns=$f['new'];
            $terminalStatuses=Status::where('isTerminal', '1')->get()->pluck('id');
            $ts=$terminalStatuses->first(function ($s, $key) use ($ns) {
                return $s == $ns ;
            });
            if ($ns==$ts) {
                $TICKETCLOSED=true;
            }
        }
    
        $qticket='qticket@quant.gr';
        $DL=qticketReviewersEmails() ;
        $bccAll=false;

        // if ticket assigned to a group with active notifications, then inform group members too
        if ( $this->assignedGroup_id && (  ($originalAssignedGroup_id !=  $this->assignedGroup_id) || $this->assignedGroup_id_Changed) 
              && $this->assignedGroup->notifyMembers ){
                $DL= groupMembersEmails($this->assignedGroup_id) ;
                $bccAll=true;
        }
// if ($user->id != 1 ) {
        if ($NEWTICKET) {


            // if not opened on my behalf , inform the one it was opened on behalf of - if he/she has email address
            if ( ($this->requestedBy_uid != $this->onBehalfOf_uid) && $this->onBehalfOf_uid ) {
              $to=trim($this->onBehalfOf->email);
              if ($to) Mail::to($to)->bcc( $DL )->send(new TicketOpened($this,false,$update));
            } else { // just send to reviewers
              Mail::to( $DL )->send(new TicketOpened($this, true,$update));
            }




        } else if ($TICKETCLOSED) {
            $to=trim($this->onBehalfOf->email);
            if ($to) {
                $tem=new TicketClosed($this, $user, $comment,  $update, "Ticket $this->statustext : ");
                if ($bccAll) {
                    Mail::to($to)->bcc($DL)->send($tem);
                } else {
                    Mail::to($to)->send($tem);
                }
            }
        } else  {

// If update by requester and requester = resolver : update onbhalf
// If update by onbehalf and request not resolver : update requester
// If update by any other update on behalf and if requester not resolver also requester

            if ( ( (! $user->isResolver) || $userTicket ) && $resolverUser && ($resolverUser->uid  != $user->uid) ) {
                // ticket updated by (a) user - inform resolver - cc helpdesk - bcc reviewers, and if enabled assigned group members 
                $to=trim($resolverUser->email);
                if ($to) {
                    $tem=new TicketUpdatedByUser($this, $user, $comment,  $update, 'User update: ');
                     if ($bccAll) {
                        Mail::to($to)->cc( [$helpdeskEmail,$this->onBehalfOf->email]  )->bcc($DL)->send($tem);
                    } else {
                        Mail::to($to)->cc( [$helpdeskEmail,$this->onBehalfOf->email]  )->send($tem);
                    }
                }
            } else if ($user->isReviewer) {
                // ticket updated by a reviewer - inform resolver
                $to='';
                if ( $resolverUser && ($resolverUser->uid  != $user->uid) ) {
                    $to=trim($resolverUser->email);
                }
                $tem=new TicketUpdatedByUser($this, $user, $comment,  $update, 'Ticket Reviewed: ');
                if ($to) {
                    Mail::to($to)->cc([$helpdeskEmail ,$user->email],$this->onBehalfOf->email)->bcc($DL)->send($tem);
                } else {        
                    Mail::to($helpdeskEmail )->cc( [$user->email,$this->onBehalfOf->email] )->bcc($DL)->send($tem);
                } 
            } else {  // any other update
                $to=trim($this->onBehalfOf->email);
                if ($to) {
                    $tem=new TicketUpdatedByUser($this, $user, $comment,  $update, 'Ticket update : ');
                    if ($bccAll) {
                        Mail::to($to)->bcc($DL)->send($tem);
                    } else {
                        Mail::to($to)->send($tem);
                    }
                } else {
                    $tem=new TicketUpdatedByUser($this, $user, $comment,  $update, 'Ticket update : ');
                    Mail::to($DL)->send($tem);
                }
            }
    }
    }

    public function getOpenedOnAttribute(){
        return isset($this->created_at) ? ( $this->created_at < Carbon::today() ?  $this->created_at->format('d-m-Y') :  $this->created_at->diffForHumans() ) : '';
    }

    public function getLastUpdateAttribute(){
        return isset($this->updated_at) ? ( $this->updated_at < Carbon::today() ?  $this->updated_at->format('d-m-Y') :  $this->updated_at->diffForHumans() ) : '';
    }


    public function getOnBehalfOfNameAttribute(){
        if ($this->requestedBy_uid != $this->onBehalfOf_uid ) {
            return $this->onBehalfOf->name ;
        } else {
            return $this->requestedBy->name ;
        }
    }


    public function getStatusTextAttribute(){
        return  (isset($this->status)) ? $this->status->name : 'OPEN';
    }

    public function getAssignedGroupNameAttribute(){
        $s=(isset($this->assignedGroup)) ? $this->assignedGroup->name : '';
        $s.= (isset($this->assignedResolver)) ?  ($s ? '/' : '') .$this->assignedResolver->lastname : '';
        return  $s;
    }

    public function getRefidAttribute(){
        return  'QT' . str_pad($this->id,5,"0",STR_PAD_LEFT);
    }

    public function setVendorOpenedAttribute($vd){ 
        return $this->vendorOpenedDate = new Carbon($vd);
    }

    public function getVendorOpenedAttribute(){
        return $this->vendorOpenedDate ? $this->vendorOpenedDate->format('Y-m-d') : ''; 
    }

    public function setVendorClosedAttribute($vd){ 
        return $this->vendorClosedDate = new Carbon($vd);
    }

    public function getVendorClosedAttribute(){
        return $this->vendorClosedDate ? $this->vendorClosedDate->format('Y-m-d') : ''; 
    }


}