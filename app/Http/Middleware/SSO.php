<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;
use URL; 

class SSO
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $user = User::where('uid','u8888')-> first();
        if (isset($_SERVER['HTTP_REALROOT']))  URL::forceRootUrl($_SERVER['HTTP_REALROOT']);
        // dd($_SERVER);

        $auser=''; 
        if (isset($_SERVER['REMOTE_USER'])) {
            // dd($_SERVER['REMOTE_USER']);
            $domuser=$_SERVER['REMOTE_USER'];
            $domuserparts=explode('\\', $domuser);
            $auser=$domuserparts[1];

            if ($auser=='pmessinezis') {
            // // below is to "impersonate" another user and check the behavior of the system for that user
                // $auser='mkleftogianni';
            }

            $user = User::where('uid',$auser)-> first();
        } 
        if ( ! User::all()->count() ) {
            if ($auser=='') {
                $auser=  'root';
            }
        }
        if ( (User::all()->count()==1) && (User::find($auser)->uid==$auser) ) {
            # $auser='root';
            $user = User::where('uid',$auser)->first();
        }
 
        if (! isset($user) && isset($auser) && $auser!='') {
            if ( ! User::all()->count() ) {
                $user=User::fromLDAP($auser);
            } else {
                try {
                    $user=User::fromLDAP($auser);
                }
                catch (\Exception $e) {
                    $user=null;
                }
            }
        }
        if (isset($user)) {
        // dd($user);
            if(Auth::check() && (Auth::id()==$user->uid)){
                
            } else { 
                Auth::login($user, true); 
            }
            $uri=$request->path();
            if (starts_with($uri,'admin/') || $uri=='admin') {
                if ($user->isAdmin()) {
                    return $next($request);
                }  else { 
                    return redirect('/');
                }
            } else {
                return $next($request);
            }
        } else { dd("$auser you are not logged on as a valid user") ; }
    }
}
