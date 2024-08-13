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
        if(($user->confirm_token!='')){
           Session::flash('message-danger', 'Se ha enviado un mensaje al correo registrado para realizar la confirmación y verificación de tu cuenta.');
            Auth::logout();
           return redirect('/validar/cuenta/mensaje?token='.$user->confirm_token.'&email='.$user->email);             
        }

        return $next($request);
    }
}
