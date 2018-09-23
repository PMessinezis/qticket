<?php 

namespace App\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use Illuminate\Http\Request;
use App\Ticket;
use App\TicketUpdate;
use App\Attachment;
use App\Status;
use Auth;
use Carbon\Carbon;

use Backpack\CRUD\app\Http\Requests\CrudRequest as StoreRequest;
use Backpack\CRUD\app\Http\Requests\CrudRequest as UpdateRequest;



class TicketController extends CrudController  
{

  public function setup() {
        $this->crud->setModel('App\Ticket');
        $this->crud->setRoute(config('backpack.base.route_prefix')  . '/ticket');
        $this->crud->setEntityNameStrings('ticket', 'tickets');
        $this->crud->setFromDb();
  }

  public function store(StoreRequest $request)
  {
    return parent::storeCrud();
  }


  public function ticketSearch(Request $request){
    $keyword=$request->input('keywords');
    if ($keyword){ 
      $k='%' . $keyword . '%';
      $tq=Ticket::where('title','like', $k )->orwhere('description', 'like', $k);
      $tq=$tq->with('updates')->orWhereHas('updates', function($q) use ( $k ){
          $q->where('comment', 'like', $k)->orwhere('changeslog', 'like', $k); })->orWhere('vendorRef', 'like', $k) ;
      if (strtoupper(substr(trim($keyword),0,2))=='QT') {
        if (strlen(trim($keyword))==7) {
          $ref=substr(trim($keyword),2,5);
          if (is_numeric($ref)){
            $id=$ref + 0 ;
            $tq=$tq->orWhere('id',$id);
          }
        }

      }

      $UIDS=array_column(\DB::select("select uid from users where lastname like  '" . $k . "' or email like  '" . $k . "'"),'uid');
      if (isset($UIDS) && count($UIDS)){
        $tq=$tq->orWhere(function ($query) use ($UIDS) {
                $query->whereIn('onBehalfOf_uid', $UIDS );
            });        
      }

      $tq=$tq->orWhere('clientInfo','like', $k);
      $tq=$tq->orWhere('onBehalfOf_uid', $keyword);

      return $tq->orderBy('updated_at','asc')->orderBy('id','asc')->get();
    } else {
      return Ticket::where('title','')->get() ;
    }
  }    

  public function tickets(Request $request){
    $me=Auth::user();
    $whereRaw = '';
    $usersWhere='';
    $statusWhere='';
    $afterWhere='';
    $terminalMode=false;
    $activeMode =false;
    $allMode=false;
    $keywords=$request->input('keywords');
    $current_id=$request->input('currentid');
    $status=$request->input('status');
    $after=$request->input('after');
    $terminalStatuses=Status::where('isTerminal', '1')->get()->pluck('id');
    $terminalStatuses=$terminalStatuses->implode(',');
    if ( ! isset($after) || ! $after)  {
      $statusWhere= "  ( ( closedDateTime is NULL ) " .
         ( $terminalStatuses ? "and ( status_id not in ( " . $terminalStatuses . " ) ) " : "" )
         . " ) " ;
    }
    if ($status) {
      $activeMode   =   $status == 'active';
      $terminalMode =   $status == 'terminal';
      $allMode      =   $status == 'statusall';
      if ($allMode) {
        $statusWhere=' ( TRUE ) ';
      } else if ($activeMode ) {
        $statusWhere = $terminalStatuses ? "  ( Not ( status_id in ( " . $terminalStatuses . " ) ) ) " : " " ;
      } else if ($terminalMode) {
        $statusWhere = "  ( ( closedDateTime is NOT NULL ) or ( status_id in ( " . $terminalStatuses . " ) )  ) " ;
      } else {
        $statusWhere = "  ( status_id = " . $status . " ) " ;        
      }
    } ;

    $category=$request->input('category');
    $catwhere ='';
    if ($category) {
      $catwhere = '  AND ( category_id=' . $category . ' ) ';
    }

    $group=$request->input('group');
    if ($group && $me->isResolver ) {
      if (is_array($group) ) {
          $group=implode(',',$group);
          $usersWhere=" ( assignedGroup_id in ( 0 , $group ) ) ";
      } else {
        $group=intval($group);
        if ($group == 999 ) {
            $usersWhere=' ( TRUE ) ';
        } else if ($group == 998 ) {
            $usersWhere=' (  assignedResolver_id = ' . $me->resolver->id  . ' ) ';
        } else { 
            $usersWhere=" ( assignedGroup_id = $group ) ";
        }
      }
    } else {

      // Query : Where [ user / resolver related ]  AND [ status related ] AND [ after Related ]
      // if I am a resolver returned also assigned to me 

      $usersWhere='  ( ( requestedBy_uid= "' . $me->uid . '" )  or ( onBehalfOf_uid= "' . $me->uid . '") ';

      if (isset($me->resolver->id)) {
        $myGroups=$me->resolver->groups->pluck('id');
        $myGroups=$myGroups->implode(',');
        $usersWhere .= ' or ( assignedResolver_id = ' . $me->resolver->id  . ' ) or  ( assignedGroup_id IN  (' . $myGroups . ') )';
      }

      $usersWhere .= ' ) ';

    }


    $whereRaw="  $usersWhere " .  ($statusWhere ? (' AND ' . $statusWhere) : ' ')  . $catwhere ;

    if (isset($after) && $after) {
        $after=new Carbon($after);
        if ($current_id) {
          $whereRaw = " ( $whereRaw AND ( updated_at  >= '" . $after->toDateTimeString() . "' ) ) OR ( id= $current_id )  ";          
          
        } else {
          $whereRaw .= " AND ( updated_at  >= '" . $after->toDateTimeString() . "' ) ";
        }
    }

    // dd($whereRaw);
    $tq=Ticket::whereRaw($whereRaw);

    $tq->orderBy('created_at','desc')->orderBy('id','asc');
     // if ($me->uid=='u96484') {
     //  dd($tq->toSql(),$tq->getBindings());
     // };
    if ($keywords) {
      return $this->ticketSearch($request); // change toreturn not raw but query - then get sql and bindings and combine to make new whereraw
        //       $sql=$tq->toSql();
        // $b=$tq->getBindings();
        // $wi=strpos($sql,' where ');
        // $sql=substr($sql,0,$wi+6 ) . ' ( ' . substr($sql,$wi+7,strlen($sql)) . ' ) and ( ' . $whereRaw . ' ) ';

    } else {
      return $tq->get();
    }
  }


  public function ticketGet(Ticket $ticket){
    $ticket->load('requestedBy', 'onBehalfOf', 'assignedGroup', 'assignedResolver', 'category', 'status', 'assignedVendor' , 'rootCause',
     'updates.updatedBy',  'updates.attachment', 'attachments.uploadedByUser');
    return $ticket;
  }


  public function ticketReopen(Ticket $ticket, Request $request){
    $id=Status::where('name','Open')->first()->id;
    if ($id) {
      $ticket->status_id=$id;
      $ticket->addUpdate("Ticket Re-Opened",null,null);
    }
    return $ticket;
  }
  
  public function ticketClose(Ticket $ticket, Request $request){
    $id=Status::where('name','Closed')->first()->id;
    if ($id) {
      $ticket->status_id=$id;
      $ticket->addUpdate("Ticket Closed",null,null);
    }
    return $ticket;
  }
  
  public function ticketCancel(Ticket $ticket, Request $request){
    $id=Status::where('name','Cancelled')->first()->id;
    if ($id) {
      $ticket->status_id=$id;
      $ticket->addUpdate("Ticket Cancelled",null,null);
    } 
    return $ticket;
  }
  

public function handleFile($ticket, $request) {
        $file=$request->file('file');
        $a=new Attachment;        
        $a->originalName=$file->getClientOriginalName();
        $a->fileExt=$file->guessExtension();
        $a->mimeType=$file->getMimeType();
        $a->size=$file->getClientSize();
        if ($a->mimeType=='application/CDFV2-unknown') {
          $a->mimeType=$file->getClientMimeType();
          // if (a->fileExt=='') a-1>fileExt=$file->getExtension();
          if ($a->fileExt=='') $a->fileExt=$file->getClientOriginalExtension();
          $postfix=Auth::user()->uid . date("YmdHisB") . $ticket->refid ;
          $newPath='attachments/' . $postfix . '.' . $a->fileExt; 
          copy($file->getPathname() , storage_path('app/' . $newPath));
          $a->filename=$newPath;
        } else {
          $a->fileName=$file->store('attachments');
        }
        $a->ticket_id=$ticket->id;
        $a->uploadedByUser_uid=Auth::user()->uid;
        $a->save();
        return $a->id;
  }

  public function ticketPost(Ticket $ticket, Request $request){
   
    $reply = (object) array();
    
    $fields2check = TicketUpdate::fields2check;
    $changes=[];
    
    // if (Auth::User()->id==1) {
    //   dd($request->all());
    // }


    if ($ticket){
      $comment='';
      $userComment=$request->input('newComment');
      $attachment_id=0;
      $dirty=false;
    
 // dd($request->all());

      foreach ($fields2check as $k => $field) {
          // return response(var_dump($field), 500);
          $f=$field['field'];
          $cast=isset($field['cast']) ? $field['cast'] : null ;
          if ($request->has($f) && $ticket->$f != $request->input($f)){
            $field['old']=$ticket->$f;
            $v=$request->input($f);
            if ($cast=='date') {
              $d = new Carbon($v);
              $ticket->$f=$d;
              $field['new']=$v;
            } elseif ($cast=='int') {
              $i = intval($v);
              $ticket->$f=$i;
              $field['new']=$i;
            } else {
              $ticket->$f=$v;
              $field['new']=$v;
            }
            $changes[]=$field;
            $dirty=true;
          }
      }


      if ($userComment) {
          if ( $comment ) $comment .= '<br>';
          $comment .=  htmlspecialchars($userComment);  
          $dirty=true;
      }

      if ($request->hasFile('file')) {
        $attachment_id=$this->handleFile($ticket, $request) ;
        $dirty=true;
      }

      if ($dirty &&  ($comment || $attachment_id || count($changes) ) ) {
        // IMPORTANT the following call will take care of saving model !!!
        $ticket->addUpdate($comment , $attachment_id, $changes);
        $reply->status='OK';
        $reply->ticket = $this->ticketGet($ticket);
      }

    }
    return response()->json( $reply);
  }


  public function getAttachment(Attachment $attachment, Request $request)
  {
      return $attachment->download();
  }



  public function new(Request $request)
  {
    $reply = (object) array();
    $t=new Ticket;
     // dd($request->all());
    $t->requestedBy_uid=Auth::User()->uid;
    $t->onBehalfOf_uid=$request->input('onBehalfOf_uid');
    if (! $t->onBehalfOf_uid) {
         $t->onBehalfOf_uid=$request->input('requestedBy_uid');
    }
    $t->title=$request->input('title');
    $t->category_id=$request->input('category_id');
    $t->description=$request->input('description');
    $t->description=$request->input('description');
    $t->status_id=1;
    $t->openedDateTime=date('Y-m-d H:i:s');
    $t->status_id=\App\Status::where('name','Open')->first()->id;
    $ip=$_SERVER['REMOTE_ADDR'];
    if (strstr($ip, ', ')) {
      $ips = explode(', ', $ip);
      $ip = $ips[0];
    }
    $dns=gethostbyaddr($ip);
    if ($dns!=$ip) {
      $dot=strpos($dns, ".");
      if($dot) $dns=substr($dns,0,$dot);
    } 
    $t->clientInfo= ($ip==$dns) ? $ip : "$dns [$ip]";
    $t->save();
    $t->priority=$request->input('isCritical') ? 'High' : 'Normal';
    if ($t->category && $t->category->defaultGroup) {
      $t->assignedGroup_id=$t->category->defaultGroup->id;
    } elseif ($hd=\App\Category::where('name','HelpDesk')->first()) { 
      $t->assignedGroup_id=$hd->id;
    } else {
      $t->assignedGroup_id=1;      
    }
    $t->assignedGroup_id_Changed=true;
    $t->save(); 
    $attachment_id=null;
    if ($request->hasFile('file')) {
        $attachment_id=$this->handleFile($t, $request) ;
    }

    $t->addUpdate('Ticket Opened' , $attachment_id,null , true);  // this will now also take care of the TicketOpened email

    $reply->id=$t->id;
    $reply->status='OK';
    $reply->message='All OK';
    return response()->json($reply);
  }

  public function update(UpdateRequest $request)
  {
    return parent::updateCrud();
  }

  public function getStaticData(){
    return getStaticData();
  }


}


?>