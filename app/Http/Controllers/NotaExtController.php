<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\NotaExt;
use App\Expediente;
use App\Requerimiento;
use DB;
use App\User;
use App\AsignacionCaso;
use App\Actuacion;
use App\Periodo;
use App\Segmento;
use Excel; 

class NotaExtController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->id = 3030;
        $request->corte = 1;
        $request->periodo = 0;

        $notas = DB::table('notas')->where(['estidnumber'=>$request->id,'segid'=>1])->get();
        $not_exp = [];
        $not_act = [];
        $not_req = [];

        $nota_conocimiento = [];
        $nota_aplicacion = [];
        $nota_etica = [];
        
        
        foreach ($notas as $key => $nota) {


            if($nota->cptnotaid=='1') $nota_conocimiento[]=$nota;
            if($nota->cptnotaid=='2') $nota_aplicacion[]=$nota;
        }


      // dd($nota_conocimiento);












      /*  $asignaciones = AsignacionCaso::join('users','users.idnumber','=','asignacion_caso.asigest_id')
        ->where(['asignacion_caso.asigest_id'=>$request->id])
        ->groupBy('asignacion_caso.asigexp_id')
        ->select('asignacion_caso.asigexp_id')
        ->get();
        $expedientes=[];
        $notas=[];
        foreach ($asignaciones as $key => $asignacion) {            
            $notas_act=[];
            $notas_req=[];
            foreach ($asignacion->expediente->actuacion()
            ->where(['actidnumberest'=>$request->id,'actestado_id'=>104])
            ->get() as $key_2 => $act) {
               $notas_act[] = $act->get_nota_corte('etica'); 
            }
           // $notas['notas_act'] =  $notas_act;
            foreach ($asignacion->expediente->requerimientos()->where('reqidest',$request->id)->get() as $key_2 => $req) {
                $notas_req[] = $req->get_nota_corte('etica');
             }
            // $notas['notas_req'] =  $notas_req;
             $notas [$asignacion->expediente->expid] = [
                 'notas_caso'=>$asignacion->expediente->get_notas_caso(),
                 'notas_req'=>$notas_req,
                 'notas_act'=>$notas_act,                  
             ];
           //dd($asignacion->expediente->actuacion[0]->get_nota_corte('etica'));
        }
    
        dd($notas);

        foreach ($notas as $expediente => $notas) {
         dd($notas);
        }*/
        
        $periodos = Periodo::all();
        
        return view('myforms.frm_notas_list',compact('periodos'));
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
    public function store(Request $request)
    {
       // return response()->json($request->all());
        if($request->ajax()){
        $user =  User::where('idnumber',$request->estidnumber)->first();
        $user->origen = $request->origen;
        $response = 0;
        //dd($user->get_nota_corte('conocimiento',$request->oficina_id));
          if(count($user->get_nota_corte_ext('conocimiento',$request->oficina_id))>0 and 
          $user->get_nota_corte_ext('conocimiento',$request->oficina_id)['nota'] == 0 and 
         $user->get_nota_corte_ext('conocimiento',$request->oficina_id)['id'] == 0){
           
            //return response()->json(['oficina'=>$request->all()]);
            $data = [
                'ntaaplicacion'=>$request->has('ntaaplicacion') ? $request->ntaaplicacion:null,
                'ntaconocimiento'=>$request->has('ntaconocimiento') ? $request->ntaconocimiento:null,
                'ntaetica'=>$request->has('ntaetica') ? $request->ntaetica:null,
                'ntaconcepto'=>$request->has('ntaconcepto') ? $request->ntaconcepto:null,

                'ntamanaudiencia'=>$request->has('ntamanaudiencia') ? $request->ntamanaudiencia:null,
                'ntaanalisisformulas'=>$request->has('ntaanalisisformulas') ? $request->ntaanalisisformulas:null,
                'ntapuntualidad'=>$request->has('ntapuntualidad') ? $request->ntapuntualidad:null,
                'ntaprespersonal'=>$request->has('ntaprespersonal') ? $request->ntaprespersonal:null,
                'ntaplanconciliacion'=>$request->has('ntaplanconciliacion') ? $request->ntaplanconciliacion:null,
                'ntaredaccacta'=>$request->has('ntaredaccacta') ? $request->ntaredaccacta:null,
                'orgntsid'=>$request->orgntsid,
                'segid'=>$request->segid,
                'perid'=>$request->perid,
                'tpntid'=>$request->has('definitiva') ? 1 : $request->tpntid,
                'estidnumber'=>$request->estidnumber,
                'extidnumber'=>auth()->user()->idnumber, 
                'tbl_org_id'=>$request->oficina_id,
              ];
            //$oficina->ntaconocimiento = $request->ntaconocimiento;
    
                
            $user->asignarNotas($data);
            $response = 1;
          }      
         
        return response()->json($response);
       }     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function searchNotas(Request $request)
    {
        if($request->ajax()){
      /*   $notas = DB::table('notas')
        ->join('cptonotas as cp','cp.id','=','notas.cptnotaid')
        ->join('segmentos','segmentos.id','=','notas.segid')
        ->join('periodo','periodo.id','=','notas.perid')
        ->select(
            'notas.nota','notas.cptnotaid','notas.segid','notas.perid','notas.estidnumber',
            'cp.cpntnombre as concepto','segmentos.segnombre as segmento','periodo.prddes_periodo'
            )
        ->where([
            'estidnumber'=>$request->idnumber,
           // 'segid'=>$request->segmento_id,
            'notas.perid'=>$request->periodo_id        
        ])->get(); */

        $notas = DB::select(DB::raw("SELECT notas.estidnumber, UPPER(users.name) as nombre,
         UPPER(users.lastname) as apellido, referencias_tablas.ref_nombre as curso,
         AVG(if(cptnotaid='1',nota,null)) AS nota_conocimiento, 
         AVG(if(cptnotaid='2',nota,null)) AS nota_aplicacion, 
         AVG(if(cptnotaid='3',nota,null)) AS nota_etica,
         (ROUND(((AVG(if(cptnotaid='1',nota,null))+AVG(if(cptnotaid='2',nota,null))+AVG(if(cptnotaid='3',nota,null)))/3),1)) notafinal
         FROM notas, expedientes, users, referencias_tablas
         WHERE segid = $request->segmento_id AND exptipoproce_id!='3' AND notas.`expidnumber`=`expedientes`.`expid`
         AND notas.`estidnumber`=`users`.`idnumber` AND users.`cursando_id`=`referencias_tablas`.`id`
         GROUP BY estidnumber"));

    $notas= json_decode( json_encode($notas), true);
    
//return response()->json($notas);
Excel::create('notas', function($excel) use($notas) {
        
    $excel->sheet('Informacion', function($sheet) use($notas) {
  
       $sheet->row(1,['cedula','nombres','apellidos','curso','nota_conocimiento','nota_aplicacion','nota_etica','nota_final']);
       $sheet->fromArray($notas,null,'A2',false,false);           
       }); 

      
      


    })->store('xlsx','exports/',true);       

    $data = [
            'success' => true,
            'path'=>'/exports/notas.xlsx'
    ];

return response()->json($data); 

        $segmentos = Segmento::where('perid',$request->periodo_id)->get();    
         


        $not_exp = [];
        $not_act = [];
        $not_req = [];

        $nota_conocimiento = [];
        $nota_aplicacion = [];
        $nota_etica = [];
        $notasR=[];
         
         foreach ($segmentos as $key => $segmento) {
                $not = [];
                foreach ($notas as $key_1 => $nota) {
                 if($segmento->id == $nota->segid and $nota->cptnotaid!=4)  $not[] = $nota;
                }
            $notasR[$segmento->segnombre]=$not;
           
        }
       // return response()->json($notasR); 
        $notas = [];
        foreach ($notasR as $key => $notaR) {
          
            if(count($notaR)>0){
                foreach ($notaR as $key_2 => $nota) {
                    if($nota->cptnotaid==1)  $nota_conocimiento[] = $nota->nota;
                    if($nota->cptnotaid==2)  $nota_aplicacion[] = $nota->nota;
                    if($nota->cptnotaid==3)  $nota_etica[] = $nota->nota;
                 } 
                 $notas[$key] = [
                    'conocimiento'=>$nota_conocimiento,
                    'etica'=>$nota_etica,
                    'aplicacion'=>$nota_aplicacion,
        
                   ];
            }else{
                $notas[$key] = [
                    'conocimiento'=>[],
                    'etica'=>[],
                    'aplicacion'=>[],        
                   ];
            }
           

           
        }

            return response()->json($notas);
        }
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $user =  User::where('idnumber',$request->idnumber)->first();
        $user->origen = $request->origen;
        

        $notas = $user->get_notas_ext($request->oficina_id,$request->notas);

        return response()->json($notas);

       // dd($user->get_nota_corte('conocimiento',1));
          if(count($user->get_nota_corte_ext('conocimiento',$request->oficina_id))>0 and 
          $user->get_nota_corte_ext('conocimiento',$request->oficina_id)['nota'] == 0 and 
          $user->get_nota_corte_ext('conocimiento',$request->oficina_id)['id'] == 0){
            return response()->json(['notas'=>false]);
          }else{
            $notas = $user->get_notas_ext($request->oficina_id);
            if((currentUser()->hasRole("amatai"))){
                $notas['can_edit'] = true;        
            }else{
                $notas['can_edit'] = false;        
            }
            return response()->json(['notas'=>$notas]);
          }
        return response()->json($request->all());

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
     //   return response()->json($request->all());

        if ($request->ajax()) {
            if($request->nota_aplicacionid!=null){
               // return response()->json($nota);
                $nota = NotaExt::find($request->nota_aplicacionid);
                $nota->nota = str_replace('._','.0',$request['nota_aplicacion']);
                $nota->extidnumber = auth()->user()->idnumber;
                if($request->has('definitiva'))  $nota->tpntid = 1;
                $nota->save();
            }
            if($request->nota_conocimientoid!=null){
                // return response()->json($nota);
                 $nota = NotaExt::find($request->nota_conocimientoid);
                 $nota->nota = str_replace('._','.0',$request['nota_conocimiento']);
                 $nota->extidnumber = auth()->user()->idnumber;
                 if($request->has('definitiva'))  $nota->tpntid = 1;
                 $nota->save();
             }
             if($request->nota_eticaid!=null){
                // return response()->json($nota);
                 $nota = NotaExt::find($request->nota_eticaid);
                 $nota->nota = str_replace('._','.0',$request['nota_etica']);
                 $nota->extidnumber = auth()->user()->idnumber;
                 if($request->has('definitiva'))  $nota->tpntid = 1;
                 $nota->save();
             }
            if($request->nota_conceptoid!=null){
                $nota = NotaExt::find($request->nota_conceptoid);
                $nota->nota = $request->nota_concepto;
                $nota->extidnumber = auth()->user()->idnumber;
                if($request->has('definitiva'))  $nota->tpntid = 1;
                $nota->save();
            }
            

            return response()->json($nota);
        }
    }

    public function updateNota(Request $request)
    {
        
       // return response()->json($request->all());
        
        if ($request->ajax()) {
            $expediente = Expediente::find($request->exp_id);
            foreach ($request->nota as $key_1 => $nota_r) {
                foreach ($request->nota_id as $key_2 => $nota_id) {
                    if($key_1==$key_2){                      
                        $nota = Nota::find($nota_id);
                        $nota->nota = $nota_r;
                        $nota->docidnumber = \Auth::user()->idnumber;
                        $nota->tpntid = $request->tipo_nota_id;
                        if($nota->tbl_org_id==null) $nota->tbl_org_id = $request->tbl_org_id;

                        $nota->save();
                        $ex_id = $nota->expidnumber;
                    }
                }
                
            }    
            $final = number_format($expediente->get_nota_corte('final')['nota'],1,'.','.');

            $response = [
                "nota_final"=>$final,
                'user_name'=>\Auth::user()->name,
                "notas_caso"=>view("myforms.frm_calificacion_create_ajax_ln",compact('expediente'))->render(),
            ];

            return response()->json($response);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //return response()->json($request->all());
            $nota = DB::table('notas_ext')
            ->where('estidnumber',$request->idnumber)
            ->where('tbl_org_id',$request->tbl_org_id)
            ->delete(); 
      

        return response()->json($nota);
    }
}
