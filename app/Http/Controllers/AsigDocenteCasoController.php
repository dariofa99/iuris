<?php

namespace App\Http\Controllers;

use App\AsigDocenteCaso;
use Illuminate\Http\Request;
use App\Expediente;
use App\AsignacionCaso;
use App\Notifications\SolicitudDocenteCaso;
use App\User;

class AsigDocenteCasoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $asignacion_caso =  AsignacionCaso::where([
            'asigest_id'=>$request->exp_idnumberest,
            'asigexp_id'=>$request->expid
        ])->first();
 //return response()->json(['error'=>$asignacion_caso]);
          try {
            $asignacion = new AsigDocenteCaso();
            $asignacion->docidnumber = \Auth::user()->idnumber;
            $asignacion->asig_caso_id = $asignacion_caso->id;
            $asignacion->user_created_id = \Auth::user()->idnumber;
            $asignacion->user_updated_id = \Auth::user()->idnumber;
            $asignacion->save();
            $user = \Auth::user();
            $user->casos;
        return response()->json(count($user->casos));
              } catch (\ErrorException $e) {
                  $messaje = "Error en la asignacion, consulte con el administrador";
                  $messaje= $e->getMessage();
          return response()->json(['error'=>$messaje]);
        }
        
    }

   
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request,$id)
    {
     // return response()->json($request->all());
        $expediente = Expediente::find($id);
        $asig = $expediente->asignaciones()
        ->where('asigest_id',$expediente->estudiante->idnumber)
        ->where('activo',1)
        ->first();
       // return response()->json($asig->id);
        try {
            $asig_doc =  AsigDocenteCaso::where(['asig_caso_id'=>$asig->id,'activo'=>1])->first();

            if($request->tipo_cambio==1){                
                $asig_doc->docidnumber = $request->new_docente_id;    
                $asig_doc->cambio_docidnumber = null;             
            }elseif($request->tipo_cambio==0){
                $asig_doc->cambio_docidnumber = $request->new_docente_id; 
                $notify = User::where('idnumber',$request->new_docente_id)->first();
                $notify->expid = $expediente->expid;
                $notify->notify(new SolicitudDocenteCaso($notify));


            }elseif ($request->tipo_cambio==2) {
                $asig_doc->cambio_docidnumber = null; 
            }elseif ($request->tipo_cambio==3) {
                $asig_doc->activo = 0; 
                if(currentUser()->hasRole('docente')){
                    $new_asig_doc =  new AsigDocenteCaso();
                    $new_asig_doc->activo = 1;
                    $new_asig_doc->docidnumber = \Auth::user()->idnumber;
                    $new_asig_doc->asig_caso_id =  $asig_doc->asig_caso_id;
                    $new_asig_doc->user_created_id = \Auth::user()->idnumber;
                    $new_asig_doc->user_updated_id = \Auth::user()->idnumber;
                    $new_asig_doc->save();
                }
                
            }elseif($request->tipo_cambio==4) {
               
                    $new_asig_doc =  new AsigDocenteCaso();
                    $new_asig_doc->activo = 1;
                    $new_asig_doc->docidnumber = $request->new_docente_id;
                    $new_asig_doc->asig_caso_id =  $asig->id;
                    $new_asig_doc->user_created_id = \Auth::user()->idnumber;
                    $new_asig_doc->user_updated_id = \Auth::user()->idnumber;
                    $new_asig_doc->save();
                
                
            }elseif($request->tipo_cambio==5) {
                $del = $expediente->getAsignacion()->asig_docente->delete();
               return response()->json(['eliminado'=>1]);           
            
        }

            $asig_doc->user_updated_id = \Auth::user()->idnumber;
            $asig_doc->save();

            

           




        } catch (\Throwable $th) {
            return response()->json(['error'=>$th->getMessage()]);
        }
        
       
       
       
    }

    
    public function destroy($id)
    {
        //
    }

    
}
