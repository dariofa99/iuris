<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        $user = Auth::user();
        if(count($user->roles)>0 and $user->hasRole("amatai") and !$user->active){
            Session::flash('message-danger', 'La fecha de matrículas ya venció, deberá comunicarse con dirección general para habilitar el proceso.');
             Auth::logout();
            return redirect('/login');             
         }else if(count($user->roles)<=0){
            Session::flash('message-danger', 'No tiene un rol asignado.');
            Auth::logout();
           return redirect('/login'); 
         }

        if(($user->confirm_token!='' and !$user->active)){
           Session::flash('message-danger', 'Se ha enviado un mensaje al correo registrado para realizar la confirmación y verificación de tu cuenta.');
           Auth::logout();
           return redirect('/validar/cuenta/mensaje?token='.$user->confirm_token.'&email='.$user->email);             
        }

        return $next($request);
    }
}
