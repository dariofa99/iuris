<?php
namespace App\Traits;

use App\Nota;
use App\Segmento;
use DB;
use Carbon\Carbon;
trait AsigNotas {

		public function asignarNotas($request){	

if ($request['orgntsid']!=3) {
  
		Nota::create([
                                    
                                    'nota'=>$cadena = str_replace('._','.0',$request['ntaconocimiento']), //cotrte1
                                    'cptnotaid'=>1, //competencia
                                    'orgntsid'=> $request['orgntsid'], //expedientes
                                    'segid'=> $request['segid'],
                                    'perid'=> $request['perid'],//id tabla asignaciones
                                    'tpntid'=> $request['tpntid'],//id tabla procedencia
                                    'estidnumber'=> $request['estidnumber'],                                    
                                    'expidnumber'=> $request['expidnumber'],
                                    'docidnumber'=> $request['docidnumber'],
                                    'tbl_org_id'=> $request['tbl_org_id'],
                                 ]);
		Nota::create([
                                    
                                    'nota'=> str_replace('._','.0',$request['ntaaplicacion']), //cotrte1
                                    'cptnotaid'=>2, //competencia
                                    'orgntsid'=> $request['orgntsid'], //expedientes
                                    'segid'=> $request['segid'],//id tabla asignaciones
                                    'tpntid'=> $request['tpntid'],
                                    'perid'=> $request['perid'],//id tabla procedencia
                                  'estidnumber'=> $request['estidnumber'],                                    
                                    'expidnumber'=> $request['expidnumber'],
                                    'docidnumber'=> $request['docidnumber'],
                                    'tbl_org_id'=> $request['tbl_org_id'], 
                                 ]);
}

		Nota::create([
                                    
                                    'nota'=> str_replace('._','.0',$request['ntaetica']), //cotrte1
                                    'cptnotaid'=>3, //competencia
                                    'orgntsid'=> $request['orgntsid'], //expedientes
                                    'segid'=> $request['segid'],//id tabla asignaciones
                                    'tpntid'=> $request['tpntid'],
                                    'perid'=> $request['perid'],//id tabla procedencia
                                 'estidnumber'=> $request['estidnumber'],                                    
                                    'expidnumber'=> $request['expidnumber'],
                                    'docidnumber'=> $request['docidnumber'],
                                    'tbl_org_id'=> $request['tbl_org_id'],
                                 ]);
		Nota::create([
                                    
                                    'nota'=>str_replace('._','.0',$request['ntaconcepto']), //cotrte1
                                    'cptnotaid'=>4, //competencia
                                    'orgntsid'=> $request['orgntsid'], //expedientes
                                    'segid'=> $request['segid'],//id tabla asignaciones
                                    'tpntid'=> $request['tpntid'],
                                    'perid'=> $request['perid'],//id tabla procedencia
                                    'estidnumber'=> $request['estidnumber'],                                    
                                    'expidnumber'=> $request['expidnumber'],
                                    'docidnumber'=> $request['docidnumber'],
                                    'tbl_org_id'=> $request['tbl_org_id'],
                                 ]);
		
     
		 	

  }
  
  public function get_nota_corte($concepto,$segmento=0){

    if ($segmento!=0){
        $segmento = Segmento::join('sede_segmentos as sg','sg.segmento_id','=','segmentos.id')			
        ->where('sg.sede_id',session('sede')->id_sede)
        ->where(['segmentos.id'=>$segmento])->first();
    }else{
        $segmento = Segmento::join('sede_segmentos as sg','sg.segmento_id','=','segmentos.id')			
        ->where('sg.sede_id',session('sede')->id_sede)
        ->where('estado',true)->first();  
    }    
    $promedio = '';
    $nota = [];
    $n_conocimiento = [];
    $n_etica = []; 
    $n_aplicacion = [];
    $n_concepto = [];
    
    $response = [
      'nota'=>0,
      'id'=>0,
      'tipo'=>0,
      'tipo_id'=>0,
      'docidnumber'=>0, 
      'docevname'=>0, 
      'estidnumber'=>0,
      'periodo'=>0,
      'segmento'=>0, 
      'segmento_id'=>0, 
      'created_at'=>0, 
      'updated_at'=>0, 
                    
    ];

    
    if ($segmento) { 
      if($this->origen==1){
        $notas =  $this->notas()
        ->where(['estidnumber'=>$this->expidnumberest,'orgntsid'=>$this->origen,'segid'=>$segmento->segmento_id])
        ->get();
      //  dd($segmento->segmento_id,'si')  ;
      }else{
        $notas =  Nota::where(['orgntsid'=>$this->origen,'tbl_org_id'=>$this->id])->get();
        //return $notas;
      }
      
      if(count($notas)>0){
        foreach ($notas as $key => $nota) {
          // if ($nota->segid==$segmento->id) {
             $data = [
                   'nota'=>$nota->nota,
                   'id'=> $nota->id,
                   'tipo'=>$nota->tipo_nota->tpntnombre,
                   'tipo_id'=>$nota->tipo_nota->id,
                   'docidnumber'=>$nota->docidnumber, 
                   'docevname'=>$nota->docente_eva->name.' '.$nota->docente_eva->lastname, 
                   'estidnumber'=>$nota->estidnumber,
                   'periodo'=>$nota->periodo->prddes_periodo,
                   'segmento'=>$nota->segmento->segnombre, 
                   'segmento_id'=>$nota->segmento->id,  
                   'created_at'=>Carbon::parse($nota->created_at), 
                  'updated_at'=>Carbon::parse($nota->updated_at),                 
                 ];
//dd($notas);and $nota->estidnumber==$this->expidnumberest
             if ($nota->cptnotaid==1){
                   $n_conocimiento[] = $data;
             } 
              if ($nota->cptnotaid==2){
               $n_aplicacion[] =  $data;
              } 
              if ($nota->cptnotaid==3){
               $n_etica[] =  $data;
              }
              if ($nota->cptnotaid==4){
               $n_concepto[] =  $data;
              }      
          // }
       }
      }
      
      
      switch ($concepto) {
            case 'conocimiento':
              if (count($n_conocimiento)>0) {
                 // $promedio = $this->get_promedio($n_conocimiento);
                $response = [
                'nota'=>$n_conocimiento[0]['nota'],
                'id'=>$n_conocimiento[0]['id'],
                'tipo'=>$n_conocimiento[0]['tipo'],
                'tipo_id'=>$n_conocimiento[0]['tipo_id'],
                'docidnumber'=>$n_conocimiento[0]['docidnumber'], 
                'docevname'=>$n_conocimiento[0]['docevname'], 
                'estidnumber'=>$n_conocimiento[0]['estidnumber'],
                'periodo'=>$n_conocimiento[0]['periodo'],
                'segmento'=>$n_conocimiento[0]['segmento'],
                'segmento_id'=>$n_conocimiento[0]['segmento_id'], 
                'created_at'=>$n_conocimiento[0]['created_at'], 
                'updated_at'=>$n_conocimiento[0]['updated_at'],           
                  ];
              }
            break;
            case 'aplicacion':
            if (count($n_aplicacion)>0) {
              // $promedio = $this->get_promedio($n_aplicacion);
              $response = [
                'nota'=>$n_aplicacion[0]['nota'],
                'id'=>$n_aplicacion[0]['id'],
                'tipo'=>$n_aplicacion[0]['tipo'],
                'tipo_id'=>$n_aplicacion[0]['tipo_id'],
                'docidnumber'=>$n_aplicacion[0]['docidnumber'],
                'docevname'=>$n_aplicacion[0]['docevname'],
                'estidnumber'=>$n_aplicacion[0]['estidnumber'],
                'periodo'=>$n_aplicacion[0]['periodo'],
                'segmento'=>$n_aplicacion[0]['segmento'],  
                'segmento_id'=>$n_aplicacion[0]['segmento_id'], 
                'created_at'=>$n_aplicacion[0]['created_at'], 
                'updated_at'=>$n_aplicacion[0]['updated_at'], 

              ];
            }
            break;
            case 'etica':
            if (count($n_etica)>0) {
               //$promedio = $this->get_promedio($n_etica);
                $response = [
                    'nota'=>$n_etica[0]['nota'],
                    'id'=>$n_etica[0]['id'],
                    'tipo'=>$n_etica[0]['tipo'],
                    'tipo_id'=>$n_etica[0]['tipo_id'],
                    'docidnumber'=>$n_etica[0]['docidnumber'],
                    'docevname'=>$n_etica[0]['docevname'],
                    'estidnumber'=>$n_etica[0]['estidnumber'],
                    'periodo'=>$n_etica[0]['periodo'],
                'segmento'=>$n_etica[0]['segmento'], 
                'segmento_id'=>$n_etica[0]['segmento_id'], 
                'created_at'=>$n_etica[0]['created_at'], 
                'updated_at'=>$n_etica[0]['updated_at'],
                ];
            }
              break;
            case 'concepto':
            if (count($n_concepto)>0) {
              // $promedio = $this->get_promedio($n_etica);
                $response = [
                    'nota'=>$n_concepto[0]['nota'],
                    'id'=>$n_concepto[0]['id'],
                    'tipo'=>$n_concepto[0]['tipo'],
                    'tipo_id'=>$n_concepto[0]['tipo_id'],
                    'docidnumber'=>$n_concepto[0]['docidnumber'],
                    'docevname'=>$n_concepto[0]['docevname'],
                    'estidnumber'=>$n_concepto[0]['estidnumber'],
                    'periodo'=>$n_concepto[0]['periodo'],
                  'segmento'=>$n_concepto[0]['segmento'], 
                  'segmento_id'=>$n_concepto[0]['segmento_id'], 
                  'created_at'=>$n_concepto[0]['created_at'], 
                  'updated_at'=>$n_concepto[0]['updated_at'],
                ];
            }
              break; 
              case 'final':
                $promedio1 = $this->get_promedio($n_etica);
                $promedio2 = $this->get_promedio($n_aplicacion);
                $promedio3 = $this->get_promedio($n_conocimiento);
                $final = []; 
                $final[] = ['nota'=>$promedio1];
                $final[] = ['nota'=>$promedio2];
                $final[] = ['nota'=>$promedio3];
                $promedio = $this->get_promedio($final);
                $response = [
                    'nota'=>$promedio,
                    'id'=>00,
                    'tipo'=>00,
                    'tipo_id'=>00,
                    'docidnumber'=>00,
                    'docevname'=>00,
                    'estidnumber'=>00,
                    'periodo'=>0,
                    'segmento'=>0, 
                    'segmento_id'=>0, 
                    'created_at'=>0, 
                    'updated_at'=>0,
                ];
                break;                      
        }  
      
    }

   return $response;
  }

  function get_promedio($notas){
    $totaln = count($notas);
    $promedio=0;
    if($totaln>0){
       $suma = 0;     
        foreach ($notas as $key => $nota) {
          //dd($nota['nota']);
            $suma += $nota['nota'];
        }
        $promedio = $suma / $totaln;
    }
   
  
    return $promedio;
  
  }
   
  public function get_notas(){
    $notas = [
        "nota_conocimiento"=> number_format($this->get_nota_corte('conocimiento')['nota'],1,'.','.'),
        "nota_conocimientoid"=>$this->get_nota_corte('conocimiento')['id'],
        "nota_etica"=>number_format($this->get_nota_corte('etica')['nota'],1,'.','.'),
        'nota_eticaid'=>$this->get_nota_corte('etica')['id'],
        "nota_aplicacion"=>number_format($this->get_nota_corte('aplicacion')['nota'],1,'.','.'),
        "nota_aplicacionid"=>$this->get_nota_corte('aplicacion')['id'],
        'nota_concepto'=>$this->get_nota_corte('concepto')['nota'],
        'nota_conceptoid'=>$this->get_nota_corte('concepto')['id'],
        "nota_final"=>number_format($this->get_nota_corte('final')['nota'],1,'.','.'),
        "can_edit"=>true,
        "encontrado"=>true,
        "segmento"=>$this->get_nota_corte('etica')['segmento'],
        "periodo"=>$this->get_nota_corte('etica')['periodo'],
        "tipo"=>$this->get_nota_corte('etica')['tipo'],
        "tipo_id"=>$this->get_nota_corte('etica')['tipo_id'],
        "segmento_id"=>$this->get_nota_corte('etica')['segmento_id'],
        "docevname"=>$this->get_nota_corte('etica')['docevname'],
        'created_at'=>$this->get_nota_corte('etica')['created_at'],
        'updated_at'=>$this->get_nota_corte('etica')['updated_at'],
    ];
    if ($notas['nota_etica']==0 and $notas['nota_eticaid']==0) {
       $notas["encontrado"] = false;
    }
    if (\Auth::user()->idnumber != $this->get_nota_corte('etica')['docidnumber'] || currentUser()->hasRole('estudiante') || $notas['nota_concepto'] == 'Evaluado por sistema (fecha límite vencida)') {
        $notas["can_edit"] = false;
     }

    if (currentUser()->hasRole('amatai') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('diradmin')) {
      $notas["can_edit"] = true;
   }
   if ($notas['nota_concepto'] == 'Evaluado por sistema (fecha límite vencida)') {
      $notas["docevname"] = 'Sistema. Fecha de evaluación: '. $notas["created_at"];
 }

    return $notas;
}

} 