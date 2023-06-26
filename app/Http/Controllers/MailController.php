<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use Session;
use Redirect;
use App\Notifications\CitacionEstudiantes;
use App\Expediente;

class MailController extends Controller
{
    public function store(Request $request){

        Mail::send('myforms.admin.frm_mail',$request->all(),function($msj){
            $msj->subject('Correo');
            $msj->to('darioj99@gmail.com');


        });

        Session::flash('success','Correcto');
        return redirect()->back();
        dd($request->all());
    }

    public function sendCitacionEstudiante(Request $request){
    $expediente = Expediente::where('expid',$request->exp_id)->first(['expid']);
    //$estudiante = $expediente->estudiante;
    //$estudiante = currentUser();
    $expediente->email = 'darioj99@gmail.com';

    $expediente->notify(new CitacionEstudiantes($expediente));

    // return $expediente->estudiante;
    return $request->all();




        
         Mail::send('myforms.mails.frm_citacio',$request->all(),function($msj){
            $msj->subject('Citación');
            $msj->to('darioj99@gmail.com');  
        });
return 'Ok';
        Session::flash('success','Correcto');
        return redirect()->back();
        
    }
}
