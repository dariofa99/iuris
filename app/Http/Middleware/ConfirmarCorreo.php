<?php

namespace App\Http\Middleware;

use Closure;
use Session;
class ConfirmarCorreo
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
        if(($user->confirm_token!='')){
           Session::flash('message-danger', 'Recuerda confirmar tu correo electrónico, Se ha enviado un mensaje al correo registrado para realizar la confirmación.');
             return redirect('users/'.$user->id.'/edit');             
        }

        return $next($request);
    }
}
