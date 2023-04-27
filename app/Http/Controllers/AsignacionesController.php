<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\User;
use Storage;
use App\Expediente;
use App\Asigna_docen_est;

class AsignacionesController extends Controller
{
public function __construct(){
    $this->middleware('auth');
}

    public function index(){

       $docentes = [];
      if (currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin')) {
       $asignaciones = Asigna_docen_est::orderBy('id','asc')->paginate(200);
       $docentes = $this->getDocentes();
      }else{

       $asignaciones = Asigna_docen_est::where(['asgedidnumberdocen'=>currentUser()->idnumber,'confirmado'=>true])->paginate(10);
               //$user->asignacionesDocente;
      }
 
//dd($docentes);
       $active_asig = 'active';
        return view('myforms.frm_estudiantes_asig_list',compact('asignaciones','active_asig','docentes'));

          

    }

    public function showExpEstu($id, Request $request){
        $user = User::where('idnumber',$id)->first(); 
        $numpaginate = 20;
        if (!empty($request->get('tipo_busqueda'))) {
            /*$expedientes= Expediente::Criterio($request->data,$request->tipo_busqueda)->orderBy(DB::raw("FIELD(expestado,'4','1','2','3')"))->orderBy(DB::raw("created_at"), 'desc')->paginate($numpaginate); 
*/
            if (is_null($request->dataIni)) {
              //si la consulta no es rango
             $expedientes= Expediente::Criterio($request->data,$request->tipo_busqueda)->orderBy(DB::raw("created_at"), 'desc')->orderBy(DB::raw("FIELD(expestado,'2','1','4','3')"))->paginate($numpaginate);

            $numEx= Expediente::Criterio($request->data,$request->tipo_busqueda)->count();
            }else{
//rango
              $expedientes= Expediente::RangoFechas($request->dataIni,$request->dataFin)->orderBy(DB::raw("created_at"), 'desc')->orderBy(DB::raw("FIELD(expestado,'2','1','4','3')"))->paginate($numpaginate);

            $numEx= Expediente::RangoFechas($request->dataIni,$request->dataFin)->count();
            }
                     
          }else{
            //Por defecto docente
          $expedientes= Expediente::orderBy(DB::raw("FIELD(expestado,'2','1','4','3')"))->orderBy(DB::raw("created_at"), 'desc')->paginate($numpaginate);
          $numEx= Expediente::orderBy('created_at', 'desc')->count(); 
          }  


//dd($expedientes);

        return view('myforms.frm_expediente_estudiante_list',compact('user','expedientes'));

    }

      public function getDocentes(){

      $users = DB::table('users')
           ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
           ->leftjoin('roles' , 'role_user.role_id','=','roles.id')
           ->where ('role_id', '4' )
           ->where ('users.active', true)            
           ->select('users.active','users.id','users.idnumber',            
             DB::raw('CONCAT(users.name," ",users.lastname) as full_name'))->orderBy('users.created_at', 'desc')->get();

      return ($users);
     }

     public function updateDocenteAsignado(Request $request,$id){

      $asignaciones = Asigna_docen_est::find($id);
      $asignaciones->asgedidnumberdocen = $request->idnumber;
      $asignaciones->save();
      return response()->json($id);
     }

}
