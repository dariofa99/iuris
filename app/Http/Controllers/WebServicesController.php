<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\WebService;
use App\User;
use Session;
use Auth;
use Redirect;
use Validator;
use App\Mail\ConfirmarCorreo;
use App\Sede;
use Illuminate\Support\Facades\Mail;

class WebServicesController extends Controller
{

    public function pruebaSocket(Request $request)
    {
        dd("sockete :)");
    }

    public function index(Request $request)
    {
      
        //dd($request->all());
        //1085924683 cedula de prueba
        //2110522222 codigo de pruebas
        $user = User::where('idnumber',$request->idnumber)->first();

        if(!$user || $user==null){
            $users = DB::connection('prueba')
            ->select("select cedula from akademico.consultoriosj
            where cod_alumno = ? and cedula = ?", [$request->codigo,$request->idnumber]);
               if(count($users)>0){
                /*$messages = [
                    'email' => 'El email no es valido o ya existe.',
                    'password' => 'La contraseña debe tener minimo 6 caracteres.',
                ];*/
                 $validator = Validator::make($request->all(), [
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:6|confirmed',
                    ]);

                if ($validator->fails()) {
                    return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
                }
                $user = User::create([
                    'name' => 'Nombre',
                    'lastname' => 'Apellido',
                    'idnumber' => $users[0]->cedula,
                    'email' => $request->email,
                    'password' => ($request['password']),
                    'tipodoc_id' => '1',
                    'genero_id' => '6',
                    'estrato_id' => '9',
                    'estadocivil_id' =>'16',
                    'cursando_id' => '1',
                    'datecreated' =>Date('Y-m-d'),
                    'usercreated' => $users[0]->cedula,
                    'userupdated' => $users[0]->cedula,
                ]);
                $user->roles()->attach(6); 
                $user->confirm_token = (str_random(50));
                Mail::to($request->email)->send(new ConfirmarCorreo($user)); 
                Auth::login($user);
                if($request->has('sede_id')){
                    $sede = Sede::find($request->sede_id);
                    Auth::user()->sedes()->attach($sede->id_sede);
                    session(["sede"=>$sede]);
                } 
                return Redirect::to('expedientes');                
            }else{
                Session::flash('message-danger', 'Al parecer todavia no estas matriculado a Consultorios, verifica que tu código y número de documento sean los correctos, si crees que esto es un error comunícate con el administrador o verifica en ocara.');   
            }
        }else{
           Session::flash('message-danger', 'Al parecer ya tienes una cuenta, si crees que esto es un error comunícate con el administrador.');
        }
        return redirect()->back()
                ->withInput();
       
    }

}
