<?php

namespace App\Http\Middleware;


use App\Mail\ValidateAccount as MailValidateAccount;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class ValidateAccount
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
    $user->role;
    if (count($user->role) > 0 and $user->active == false and $user->confirm_token == "") {
        $user->confirm_token = str_replace("/", "", bcrypt(\Str::random(5)));
        $user->save();
        //Session::flash('message-danger', 'Error! Recuerda escribir un correo electrónico valido, ya que se enviará una confirmación.');
        Mail::to($user->email)->send(new MailValidateAccount($user,$user->email));
        //return redirect('users/' . $user->id . '/edit');
    }
    return $next($request);
  }
}
