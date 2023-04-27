<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Hash;


class Front
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

        $user = \Auth::user();
       
            if (!$user->active) {
                \Auth::logout();
              Session::flash('message-danger', 'Tu cuenta no esta activa, comunicate con el administrador del sistema.');
              return redirect()->back();
            } 
          
        
        return $next($request);
       
        

        
    }
}
