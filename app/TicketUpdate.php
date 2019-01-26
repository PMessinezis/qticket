<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class TicketUpdate extends Model 
{

    protected $table = 'ticketupdates';
    public $timestamps = true;
    protected $touches=['ticket'];
    protected $appends=['update'];
 

    const fields2check=[
                    'status_id' => [ 'field'  => 'status_id',   'label' => 'Status',       'cast' => 'int'    , 'model' => 'App\Status', 'showAs' => 'name'],
                    'vendorOpened' => [ 'field'  => 'vendorOpened' , 'label' => 'Date Vendor ticket opened',     'cast' => 'date']  ,
                    'category_id' => [ 'field'  => 'category_id',   'label' => 'Category',     'cast' => 'int'    , 'model' => 'App\Category', 'showAs' => 'name'],
                    'subcategory_id' => [ 'field'  => 'subcategory_id',   'label' => 'Sub-Category',     'cast' => 'int'    , 'model' => 'App\Subcategory', 'showAs' => 'name'],
                    'priority' => [ 'field'  => 'priority'],
                    'assignedVendor_id' => [ 'field'  => 'assignedVendor_id', 'label' => 'Vendor', 'cast' => 'int'    , 'model' => 'App\Vendor', 'showAs' => 'name'],
                    'onBehalfOf_uid' => [ 'field' => 'onBehalfOf_uid' , 'label' => 'on behalf of' , 'model' => 'App\User', 'showAs' => 'name' ] , 
                    'vendorRef' => [ 'field'  => 'vendorRef',    ],
                    'vendorClosed' => [ 'field'  => 'vendorClosed' ,  'label' => 'Date Vendor ticket closed',      'cast' => 'date'] ,
                    'resolution' => [ 'field'  => 'resolution'],
                    'rootCause_id' => [ 'field'  => 'rootCause_id',  'label' => 'Root Cause',      'cast' => 'int'   , 'model' => 'App\RootCause', 'showAs' => 'name'],
                    'closedDateTime' => [ 'field'  => 'closedDateTime' ,     'cast' => 'date'] ,
                    'parentTicket_id' => [ 'field'  => 'parentTicket_id',     'cast' => 'int'   , 'model' => 'App\Ticket', 'showAs' => 'refid'],
                    'assignedGroup_id' => [ 'field'  => 'assignedGroup_id',  'label' => 'Assigned to Group',  'cast' => 'int'   , 'model' => 'App\Group', 'showAs' => 'name'],
                    'assignedResolver_id' => [ 'field'  => 'assignedResolver_id', 'label' => 'Assigned to Resolver', 'cast' => 'int'   , 'model' => 'App\Resolver', 'showAs' => 'lastname'],
                  ];

    public function ticket()
    {
        return $this->belongsTo('App\Ticket');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updatedBy_uid');
    }



    public function getChanges(){
        $c=$this->changeslog;
        if ($c) {
            return json_decode($c);
        } else return null;
    }


    public function setChanges($obj){
        if ($obj) {
            $c=json_encode($obj);
            $this->changeslog=$c;
        } else {
            $this->changeslog=null;            
        }
        // dd($obj,$c,$this->changeslog,json_last_error(),json_last_error_msg());
    }


    public function getUpdateAttribute()
    {

        $u=Auth::User();
        $chobj=$this->getChanges();
         // dd($chobj);
        $chg='';
        if ($chobj) {
            foreach($chobj as $key => $field) {
                $f=$field->field;
                $label=$f;
                $rec=self::fields2check[$f] ;
                if (isset($rec)) {
                    $model=isset($rec['model']) ? $rec['model'] : null ;
                    $show=isset($rec['showAs']) ? $rec['showAs'] : null;
                    $label=isset($rec['label']) ? $rec['label'] : $f;
                }
                $model= $model ? $model : (isset($field->model) ? $field->model : null) ;
                $show=  $show  ? $show  : (isset($field->showAs) ? $field->showAs : null);
                
                $old=$field->old;
                $v=$field->new;
                if ($model) $f=class_basename($model);
                if ($chg) $chg .= ' , '  ;
                if ($v ) {
                  if (is_object($v)) $v=var_export($v,true);
                  if ( $model && $show) {
                      $r=$model::find($v);
                      if ($r) {
                        $v=$r->$show; } else { $v='NULL';}
                  } 
                  $chg .= ucwords($label)  . ': '  .  $v ;
                } else {
                  $chg .= ucwords($label)  . ': NULL' ;              
                }
            }
        }
        if ($chg) $chg .= '<br>';
        if ( ($u->isResolver || !$this->resolverNote) && ($this->comment>'') ) {
            $c= $chg . ( $this->resolverNote ? '<i style="color:blue">' : '' ) . nl2br($this->comment) . ( $this->resolverNote ? '</i>' : '' ) ;
        } else { 
            $c='';
        }
        $c .= $this->attachment ?  ( $c ? '<br>' : '' ) . $this->attachment->htmlAnchor() : '' ;
        return $c;
    }

    public function attachment()
    {
        return $this->belongsTo('App\Attachment');
    }


}