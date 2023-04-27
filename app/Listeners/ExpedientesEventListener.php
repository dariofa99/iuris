<?php

namespace App\Listeners;

use App\Events\Event;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Expediente;
use App\Auditoria;

class ExpedientesEventListener
{  
    

    public function updated(Expediente $request){

        

        $expediente = Expediente::find($request->id);
        $auditoria = new Auditoria();
        $auditoria->host = $this->getRealIP();
        $auditoria->user_id = \Auth::user()->id;
        $auditoria->data = json_encode($expediente);
        $auditoria->exp_id = $expediente->id;
        $auditoria->save();      

    }



    function getRealIP(){

            if (isset($_SERVER["HTTP_CLIENT_IP"]))
            {
                return $_SERVER["HTTP_CLIENT_IP"];
            }
            elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
            {
                return $_SERVER["HTTP_X_FORWARDED_FOR"];
            }
            elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
            {
                return $_SERVER["HTTP_X_FORWARDED"];
            }
            elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
            {
                return $_SERVER["HTTP_FORWARDED_FOR"];
            }
            elseif (isset($_SERVER["HTTP_FORWARDED"]))
            {
                return $_SERVER["HTTP_FORWARDED"];
            }
            else
            {
                return $_SERVER["REMOTE_ADDR"];
            }
}


}
