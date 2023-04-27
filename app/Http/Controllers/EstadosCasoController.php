<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expediente;
use App\User;
use DB;
use App\EstadoCaso;
use Session;
use Carbon\Carbon;
use App\Segmento;

class EstadosCasoController extends Controller
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

       if ($request->ajax()) {
        //  return response()->json(($request->all()));
            $expediente = Expediente::where('expid',$request->expid)->first();
            $estudiante_id = $expediente->estudiante->idnumber;
            $date = Carbon::now()->format('Y-m-d');
            $acts =  $expediente->verifyNotAct($date);
            $reqs =  $expediente->verifyNotReq($date);
             
            $user_id = \Auth::user()->idnumber;  

            // return response()->json($acts);  

            $estadoCaso = New EstadoCaso();
            $estadoCaso->comentario = $request->comentario;
            $estadoCaso->useridnumber = $user_id; 
            $estadoCaso->expidnumber = $request->expid;
            $estadoCaso->ref_estado_id = $request->new_expestado;
            $estadoCaso->ref_motivo_estado_id = $request->motivo_estado;            
            $expediente->expestado_id = $request->new_expestado;
            $role = '';
            if (!currentUser()->hasRole('estudiante')) $role = 'docente';               
            if ($request->new_expestado == 2) {                
                if ((count($acts) <= 0 and count($reqs)<=0) || $expediente->exptipoproce_id == 1) {
                    if (count($expediente->notas)>0) { 
                        $nota = $expediente->get_nota_corte('conocimiento');                 
                        if (count($nota)>0 and ($nota['tipo_id'])==0 and count($expediente->get_has_nota_final())<=0){
                            $response=[
                                'mensaje'=>'El Caso NO tiene notas asignadas',
                                'guardado'=>false,
                                'exp'=>$expediente,
                                'role'=>$role, 
                            ]; 
                            return response()->json(($response)); 
                        }
                    if ($nota['tipo_id'] == 1 || count($expediente->get_has_nota_final())>0) {
                        $estadoCaso->save();
                        $expediente->save(); 
                          $response=[
                            'mensaje'=>'El Caso fue actualizo con éxito',
                            'guardado'=>true,
                            'exp'=>$expediente,
                            'role'=>$role,
                        ];
                        }else{
                            $response=[
                            'mensaje'=>'NO se puede cerrar el caso porque tiene asignadas las notas como parciales, si desea cerrarlo debe cambiar las notas asignadas como DEFINITIVAS',
                            'guardado'=>false,
                            'exp'=>$expediente,
                            'role'=>$role,   
                            ]; 
                        }
                                           
                         
                }else{
                    $response=[
                    'mensaje'=>'El Caso no tiene notas asignadas',
                    'guardado'=>false,
                    'exp'=>$expediente,
                ];
               }

                }else{
                    $mensaje = '';
                    if (count($reqs)>0) $mensaje .= 'Hay '.count($reqs).' requerimientos que requieren ser revisados <br>';
                    if (count($acts)>0) $mensaje .= 'Hay '.count($acts).' actuaciones que requieren ser revisadas';

                     $response=[
                    'mensaje'=> $mensaje,
                    'guardado'=>false,
                    'exp'=>$expediente,
                ];
 
                }



            }else{

                if (currentUser()->hasRole('estudiante')) {
                    $expediente->exphechos = $request->hechos;
                    $expediente->exprtaest = $request->exp_resp_est;   
                    if($expediente->exptipoproce_id ==  1) {
                    if($expediente->expestado_id != 5 AND $expediente->expestado_id != 2){

                        if($expediente->getDocenteAsig()->name=='Sin asignar'){
                          
                          $asignacion_caso = $expediente->getAsignacion();
                          $expediente->asigDocente($asignacion_caso);
                        }
                      }  
                    }             
                }
               // dd($reqs);
              
                if ((count($acts) > 0 || count($reqs) > 0) and $request->new_expestado == 4){
                    if($expediente->exptipoproce_id != 1) {
                            $mensaje = '';
                            if (count($reqs)>0) $mensaje .= 'Hay '.count($reqs).' requerimientos que requieren ser revisados <br>';
                            if (count($acts)>0) $mensaje .= 'Hay '.count($acts).' actuaciones que requieren ser revisadas';

                            $response=[
                            'mensaje'=> $mensaje,
                            'guardado'=>false,
                            'exp'=>$expediente,
                        ];
                        return response()->json(($response));
                    } else {
                        $estadoCaso->save();
                        $expediente->save();
                        $response=[
                            'mensaje'=>'El Caso fue actualizo con éxito',
                            'guardado'=>true,
                            'exp'=>$expediente,
                            'role'=>$role,
                        ];
                        return response()->json(($response));
                    }
                }else{
                    if ($request->new_expestado == 3){                       
                        $nota = $expediente->get_has_nota_final();
                        if(count($nota)>0){
                            $response=[
                                'mensaje'=> 'El caso ya fue evaluado con una nota final, debe eliminar...',
                                'guardado'=>false,
                                'exp'=>$expediente,
                            ];
                            return response()->json(($response)); 
                        }

                         
                    }
                    if ($request->new_expestado == 5){                       
                        $nota = $expediente->get_has_nota_final(); 
                        if(count($nota)<=0){
                            $segmento = Segmento::where('estado', 1)->first();
                            if($segmento){
                                $data = [
                                    'ntaaplicacion'=>0,
                                    'ntaconocimiento'=>0,
                                    'ntaetica'=>0,
                                    'ntaconcepto'=>'Evaluado por el sistema - Cambio de estado por administrador',
                                    'orgntsid'=>'1',
                                    'segid'=>$segmento->id,
                                    'perid'=>$segmento->perid,
                                    'tpntid'=>'1',
                                    'expidnumber'=>$expediente->expid,
                                    'estidnumber'=>$expediente->expidnumberest,
                                    'docidnumber'=>\Auth::user()->idnumber, 
                                    'tbl_org_id'=>$expediente->id, 
                                  ]; 
                                $expediente->asignarNotas($data);
                            }else{
                                $response=[
                                    'mensaje'=> 'No se puede asignar notas, debe activar un corte',
                                    'guardado'=>false,
                                    'exp'=>$expediente,
                                ];
                                return response()->json(($response)); 
                            }
                          
                            
                        }

                         
                    }


                $estadoCaso->save();
                $expediente->save();
                $response=[
                    'mensaje'=>'El Caso fue actualizo con éxito',
                    'guardado'=>true,
                    'exp'=>$expediente,
                    'role'=>$role,
                ];
                }
            


            }


          return response()->json(($response));
       }
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
     public function search(Request $request)
    {
        $estadoCaso = EstadoCaso::find($request->id);
        $estadoCaso->estado;
        $estadoCaso->motivo;
        $estadoCaso->user;

        return response()->json($estadoCaso);







    }
}