<?php



function ldapcheckfixdates($v){
    if (is_numeric($v) and  strlen($v)>12 ) {
        $win_secs = (int)($v / 10000000);
        $unix_timestamp = (int)($win_secs - 11644473600);
        $v= date('d M, Y H:m:s A T', $unix_timestamp);
    } 
    return $v;
}


function array_map_assoc($array){
  $r = array();
  foreach ($array as $key=>$value)
    $r[$key] = $value;
  return $r;
}

function ldapinfo($uid){
    $ldapinfo=collect([]);
    if (function_exists('ldap_connect')) {
        $auser=$uid;
        $ldap=ldap_connect(config('qticket.LDAP_DOMAIN'));
        ldap_set_option ($ldap, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        $encrypted= config('qticket.LDAP_PASSWORD');
        $decrypted = Crypt::decryptString($encrypted);
        $ldapbind = ldap_bind($ldap, config('qticket.LDAP_USER'), $decrypted);
        if ($ldapbind) {                 
           $ldapFilter="(&(objectCategory=person)(objectClass=user)(sAMAccountName=$auser))" ;
           $fields=array("*");
           $res=ldap_search($ldap,config('qticket.LDAP_ROOT'),$ldapFilter, $fields);
           $entries=ldap_get_entries($ldap,$res);
           for ($i=0 ; $i < $entries["count"] ; $i++) {
                $r=$entries[$i];
                for ($j =0; $j< $r["count"] ; $j++){
                    $atr=$r[$j] ;
                    $valcount=$r[$atr]["count"];
                    if ($valcount==1) {
                        $v=$r[$atr][0];
                        $v=ldapcheckfixdates($v);
                        if ($ldapinfo->get($atr)) {
                            dd($ldapinfo, $atr, $v);
                        }
                        $ldapinfo->put($atr, $v); 
                    } else {
                        $v=collect([]); 
                        
                        for ($x=0 ; $x<$valcount ; $x++) {
                            $v->push(ldapcheckfixdates($r[$atr][$x]));
                        }
                        if ($ldapinfo->get($atr)) {
                            dd($ldapinfo, $atr, $v);
                        }
                        $ldapinfo->put($atr, $v);
                    }    
                }
           }
        }   
    }     
    return $ldapinfo;
}


function myURL($s){
    $root=isset($_SERVER['HTTP_REALROOT']) ? $_SERVER['HTTP_REALROOT'] : '' ;
    // echo $s, $_SERVER['HTTP_REALROOT'];
    if ($root) {
        $root=rtrim($root,'/');
        $s=ltrim($s,'/');
        return $root . '/' . $s;
    } else {
        return $s;
    }
}


function groupMembersEmails($group_id){
    $g=\App\Group::where('id', $group_id)->first() ;
    $e=[];
    if ($g) {
        $rs=$g->resolvers;
        foreach($rs as $r){
            $u=$r->user;
            array_push($e,trim($u->email));
        }
    }
    return $e;
}


function qticketReviewersEmails(){
    $g=\App\Group::where('name', config('qticket.reviewers'))->first() ;
    if ($g) {
        return groupMembersEmails($g->id);
    } else {
        return [];
    }
}



function curl_download($Url){
 
    // is cURL installed yet?
    if (!function_exists('curl_init')){
        die('Sorry cURL is not installed!');
    }
 
    // OK cool - then let's create a new cURL resource handle
    $ch = curl_init();
 
    // Now set some options (most are optional)
 
    // Set URL to download
    curl_setopt($ch, CURLOPT_URL, $Url);
 
    // Set a referer
    // curl_setopt($ch, CURLOPT_REFERER, "http://www.example.org/yay.htm");
 
    // User agent
    curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
 
    // Include header in result? (0 = yes, 1 = no)
    curl_setopt($ch, CURLOPT_HEADER, 0);
 
    // Should cURL return or print out the data? (true = return, false = print)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
    // Timeout in seconds
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
 
    // Download the given URL, and return output
    $output = curl_exec($ch);
 
    // Close the cURL resource, and free system resources
    curl_close($ch);
 
    return $output;
}


function newTicket($title, $description,$isCritical ) {
    $id=0;
    $user = \App\User::where('uid','qticket')-> first();
    Auth::login($user,true);
    $t=new \App\Ticket;
    $t->requestedBy_uid='qticket';
    $t->onBehalfOf_uid='qticket';
    $t->title=$title;
    $t->description=$description;
    $t->status_id=1;
    $t->openedDateTime=date('Y-m-d H:i:s');
    $t->status_id=\App\Status::where('name','Open')->first()->id;
    $t->save();
    $t->priority=$isCritical ? 'High' : 'Normal';
    if ($t->category && $t->category->defaultGroup) {
      $t->assignedGroup_id=$t->category->defaultGroup->id;
    } elseif ($hd=\App\Category::where('name','HelpDesk')->first()) { 
      $t->assignedGroup_id=$hd->id;
    } else {
      $t->assignedGroup_id=1;      
    }
    $t->save(); 
    $id=$t->id;
    $t->addUpdate('Ticket Opened' , null,null , true);  // this will now also take care of the TicketOpened email
    return $id;

}

function readCRSensors(){
    $RET="";
    $xmlstr=curl_download('http://guest:P%40ssw0rd%21@10.102.8.73/data.xml');  
    if ($xmlstr>"") { 
        // $RET .= "<pre>" .  $xmlstr . "</pre> <br> " . PHP_EOL ; 
        try { 
            $xml=simplexml_load_string($xmlstr);

            $alarms=collect([]);
            $fields=collect([]);
            
            $c=0;
            foreach ($xml->alarms->children() as $a) {
                $aa=collect([]);
                foreach ($a->attributes()  as $k=>$v) {
                   $aa->put($k,(string)$v);
                }
                $alarms->put($c++,$aa);
            }
               
            foreach ($xml->devices->device->children() as $f) {
                $ff=collect([]);
                foreach ($f->attributes()  as $k=>$v) {
                    if ($k=='key') { 
                        $n=(string)$v;
                    } else {
                        $ff->put($k,(string)$v);
                    }
                }
                $fields->put($n,$ff);
            }
               
            foreach($alarms as $c => $alarm) {
                $fn=$alarm->get("field");
                $RET .= $c . " $fn  <br> " . PHP_EOL ;
                $f=$fields->get($fn);
                $v=(float)$f->get('value');
                $op=$alarm->get('limtype');
                $threshold=(float)$alarm->get('limit');
                $n=$f->get('niceName');

                $alarmCode="";

                if ($op == 'High') {
                    if ( $v <= $threshold) {
                        $RET .=  "$n is OK ($v LEQ $threshold) $op " . "  <br>" . PHP_EOL  ;
                    } else {
                        $alarmCode=$fn . "_" . $op ;
                        $alarmText="Computer Room Sensors : $n value ($v) is ABOVE threshold of ($threshold) ! ";
                    }
                } else {
                    if ( $v >= $threshold) {
                        $RET .=  "$n is OK ($v GEQ  $threshold) $op" . "  <br>" . PHP_EOL ;
                    } else {
                        $alarmCode=$fn . "_" . $op ;
                        $alarmText="Computer Room Sensors : $n value ($v) is BELOW threshold of ($threshold) ! ";
                    }            
                }
                if ($alarmCode>"") {
                    $RET .=  $alarmText . "  <br>" . PHP_EOL;
                    Log::error($alarmText);
                    $terminalStatuses=\App\Status::where('isTerminal', '1')->get()->pluck('id');
                    // $terminalStatuses=$terminalStatuses->implode(',');
                    $codqtickets= \App\Alarm::where('code', $alarmCode )->whereHas('ticket',function ($query) use ($terminalStatuses){
                        $query->whereNotIn('status_id', $terminalStatuses);
                    })->orderBy('id','desc')->get();
                    if ( (! $codqtickets) || ($codqtickets->count()<1)) { // no open tickets
                        $A = new \App\Alarm;
                        $A->code = $fn . "_" . $op ;
                        $A->info = $alarmText;
                        $A->ticket_id=newTicket("Computer Room Sensors Alarm (automated qticket) : $fn $op", $alarmText,true);
                        $A->save();
                    } else {
                         Log::error('open ticket already exists ');
                    }
                }
            } 
        }
        catch (Exception $e) { 
            $RET .=  "oops <br>" . PHP_EOL;
            dd($e);
        }
    } 
    return $RET;
}



function getStaticData(){
    $u=Auth::User() ? Auth::User()->load('resolver') : null ;
    $res = collect([
     
    'user'=> $u,  
    'categories'=>App\Category::all(),
    'statuses'=>App\Status::all(),
    'groups'=>App\Group::where("isActive",true)->orderBy("name")->get(),
    'resolvers'=>App\Resolver::where("isActive", true)->with('user')->get(),
    'vendors'=>App\Vendor::all(),
    'rootcauses'=> App\RootCause::all(),
    'users'=> App\User::orderBy('lastname')->get(),
    
     ])
    ;
    return $res ;
  }