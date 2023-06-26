<?php

namespace App\Http\Controllers;
use Redirect;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Excel; 
use Input;
use App\User;
use Carbon\Carbon; 

class ExcelusuariosController extends Controller
{

protected $delimiter  = ';';

    public function getImport()
    {
        return view('myforms.frm_myusers_importar');
    }
    


    public function postImport()
    {

     Excel::load(Input::file('file'), function($reader) {
         


       $reader->each(function($vinculado){
        $date = Carbon::now();
        
         
         User::create([
              'id' => $vinculado->id,
              'acive' => $vinculado->active,
              'idnumber' => $vinculado->idnumber,
              'name' => $vinculado->name,
              'lastname' => $vinculado->lastname,
              'email' => $vinculado->email,
              'password' => bcrypt($vinculado->password),
              'accesofvir' => $vinculado->accesofvir,
              'description' => $vinculado->description,
              'institution' => $vinculado->institution,              
              'datecreated' => $date = $date->format('Y-m-d'),
              'tel1' => $vinculado->tel1,
              'tel2' => $vinculado->tel2,
              'idrol' => $vinculado->idrol
        ]);


        
       });

      });
   

   return Redirect::to('users');

    }   


    
}
