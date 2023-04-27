<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Sede;
use App\LogSession;
use Illuminate\Foundation\Auth\AuthenticatesUsers; 
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    use AuthenticatesUsers ; 

    public function __construct()
    {
        try {
         // $this->middleware('guest', ['only'=>'index']);
        } catch (TokenMismatchException $th) {
           dd($th);
        }
      
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('myforms.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     private function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
    
    public function store(LoginRequest $request)
    { 
        Session::flash('message-information', ' Crédenciales de autenticación incorrectas');      
           
        if(is_numeric($request['user_name'])){
            $clave = 'idnumber'; 
        }else{
            $emailErr=0;
            $email = $this->test_input($request['user_name']);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $clave = 'email';
            }else{                        
                Session::flash('message-danger', ' Crédenciales de autenticación incorrectas');
                return Redirect::back();
            }
        }      
        if(Auth::attempt([$clave => $request['user_name'], 'password' => $request['password'] ])){

            if (Auth::user()->active) LogSession::create(['user_id'=>Auth::user()->id]);
            //Asignar sede
           
            if(count(Auth::user()->sedes)<=0){
                if(count(Sede::all())==1){
                    $sede = Sede::first();
                    Auth::user()->sedes()->attach($sede->id_sede);
                    session(["sede"=>$sede]);
                }elseif(count(Sede::all())>1){
                    if(Auth::user()->hasRole("solicitante")){
                        $solicitud=currentUser()->solicitudes()->whereIn('type_status_id',[162,165])->first();
                        if($solicitud){
                            $sede = $solicitud->sedes()->first();
                            if($sede){
                                Auth::user()->sedes()->attach($sede->id_sede);
                                session(["sede"=>$sede]);
                            }                           
                        }                      
                    }else{
                        return Redirect::to('dashboard');
                    }
                   
                }              
            }elseif(count(Auth::user()->sedes)>=1){
               $sede =  Auth::user()->sedes()->first();            
                session(["sede"=>$sede]);               
            }
            //dd("ss");
            //en caso de ser estudiante lo rediricciona a sus expedientes
            if(Auth::user()->hasRole("estudiante")){
             return Redirect::to('expedientes');
            }

            //en caso de ser estudiante lo rediricciona a sus expedientes
            if(Auth::user()->hasRole("docente")){
             return Redirect::to('expedientes');
            }
            if(Auth::user()->hasRole("solicitante")){
                //dd('');
                if($request->has('solicitud_id') and $request->get('solicitud_id')!=''){
                    return Redirect::to('/oficina/solicitante/solicitud/'.$request->solicitud_id);
                } 
                
                if (currentUser()->solicitudes()->whereIn('type_status_id',[162,165])->first()) {
                    $solicitud=currentUser()->solicitudes()->whereIn('type_status_id',[162,165])->first();
                    return Redirect::to('/oficina/solicitante/solicitud/'.$solicitud->id);
                } else {
                    return Redirect::to('oficina/solicitante');
                }
            }
            return Redirect::to('dashboard');
             



        }

        Session::flash('message-danger', ' Crédenciales de autenticación incorrectas');
         return Redirect::back();



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
