<?php

namespace App\Http\Middleware;

use Closure;


class CheckForMaintenanceMode 
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
        if(($user and !currentUser()->hasRole('amatai'))){
            \Auth::logout();
            return redirect('/mantenimiento')     ;    
        }

        return $next($request);
    }
}
