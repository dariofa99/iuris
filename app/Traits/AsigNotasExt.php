<?php
namespace App\Traits;

use App\NotaExt as Nota;
use App\Segmento;
use DB;
use Carbon\Carbon;
trait AsigNotasExt {

 

		public function asignarNotas($request){	

      

      if($request['ntaconocimiento']!=null){

     
		                              Nota::create([                                    
                                    'nota'=>$cadena = str_replace('._','.0',$request['ntaconocimiento']), //cotrte1
                                    'cptnotaid'=>1, //competencia
                                    'orgntsid'=> $request['orgntsid'], //expedientes
                                    'segid'=> $request['segid'],
                                    'perid'=> $request['perid'],//id tabla asignaciones
                                    'tpntid'=> $request['tpntid'],//id tabla procedencia
                                    'estidnumber'=> $request['estidnumber'],                        
                                    'extidnumber'=> $request['extidnumber'],
                                    'tbl_org_id'=> $request['tbl_org_id'],
                                 ]);
                        }
                        if($request['ntaaplicacion']!=null){
 		                              Nota::create([                                    
                                    'nota'=> str_replace('._','.0',$request['ntaaplicacion']), //cotrte1
                                    'cptnotaid'=>2, //competencia
                                    'orgntsid'=> $request['orgntsid'], //expedientes
                                    'segid'=> $request['segid'],//id tabla asignaciones
                                    'tpntid'=> $request['tpntid'],
                                    'perid'=> $request['perid'],//id tabla procedencia
                                    'estidnumber'=> $request['estidnumber'],                        
                                    'extidnumber'=> $request['extidnumber'],
                                    'tbl_org_id'=> $request['tbl_org_id'], 
                                 ]);

                                }

                                if($request['ntaetica']!=null){
		                              Nota::create([
                                    
                                    'nota'=> str_replace('._','.0',$request['ntaetica']), //cotrte1
                                    'cptnotaid'=>3, //competencia
                                    'orgntsid'=> $request['orgntsid'], //expedientes
                                    'segid'=> $request['segid'],//id tabla asignaciones
                                    'tpntid'=> $request['tpntid'],
                                    'perid'=> $request['perid'],//id tabla procedencia
                                    'estidnumber'=> $request['estidnumber'],                           
                                    'extidnumber'=> $request['extidnumber'],
                                    'tbl_org_id'=> $request['tbl_org_id'],
                                 ]); 

                                }

                                if($request['ntaconcepto']!=null){
	                              	Nota::create([
                                    
                                    'nota'=>str_replace('._','.0',$request['ntaconcepto']), //cotrte1
                                    'cptnotaid'=>4, //competencia
                                    'orgntsid'=> $request['orgntsid'], //expedientes
                                    'segid'=> $request['segid'],//id tabla asignaciones
                                    'tpntid'=> $request['tpntid'],
                                    'perid'=> $request['perid'],//id tabla procedencia
                                    'estidnumber'=> $request['estidnumber'],                       
                                    'extidnumber'=> $request['extidnumber'],
                                    'tbl_org_id'=> $request['tbl_org_id'],
                                 ]);
                                }
/////////////////////////////////////////////////////////////////////////////////////////
                                if($request['ntamanaudiencia']!=null){
	                              	Nota::create([
                                    
                                    'nota'=>str_replace('._','.0',$request['ntamanaudiencia']), //cotrte1
                                    'cptnotaid'=>8, //competencia
                                    'orgntsid'=> $request['orgntsid'], //expedientes
                                    'segid'=> $request['segid'],//id tabla asignaciones
                                    'tpntid'=> $request['tpntid'],
                                    'perid'=> $request['perid'],//id tabla procedencia
                                    'estidnumber'=> $request['estidnumber'],                       
                                    'extidnumber'=> $request['extidnumber'],
                                    'tbl_org_id'=> $request['tbl_org_id'],
                                 ]);
                                }

                                if($request['ntaanalisisformulas']!=null){
	                              	Nota::create([
                                    
                                    'nota'=>str_replace('._','.0',$request['ntaanalisisformulas']), //cotrte1
                                    'cptnotaid'=>5, //competencia
                                    'orgntsid'=> $request['orgntsid'], //expedientes
                                    'segid'=> $request['segid'],//id tabla asignaciones
                                    'tpntid'=> $request['tpntid'],
                                    'perid'=> $request['perid'],//id tabla procedencia
                                    'estidnumber'=> $request['estidnumber'],                       
                                    'extidnumber'=> $request['extidnumber'],
                                    'tbl_org_id'=> $request['tbl_org_id'],
                                 ]);
                                }

                                if($request['ntaprespersonal']!=null){
	                              	Nota::create([
                                    
                                    'nota'=>str_replace('._','.0',$request['ntaprespersonal']), //cotrte1
                                    'cptnotaid'=>7, //competencia
                                    'orgntsid'=> $request['orgntsid'], //expedientes
                                    'segid'=> $request['segid'],//id tabla asignaciones
                                    'tpntid'=> $request['tpntid'],
                                    'perid'=> $request['perid'],//id tabla procedencia
                                    'estidnumber'=> $request['estidnumber'],                       
                                    'extidnumber'=> $request['extidnumber'],
                                    'tbl_org_id'=> $request['tbl_org_id'],
                                 ]);
                                }

                                if($request['ntaplanconciliacion']!=null){
	                              	Nota::create([
                                    
                                    'nota'=>str_replace('._','.0',$request['ntaplanconciliacion']), //cotrte1
                                    'cptnotaid'=>9, //competencia
                                    'orgntsid'=> $request['orgntsid'], //expedientes
                                    'segid'=> $request['segid'],//id tabla asignaciones
                                    'tpntid'=> $request['tpntid'],
                                    'perid'=> $request['perid'],//id tabla procedencia
                                    'estidnumber'=> $request['estidnumber'],                       
                                    'extidnumber'=> $request['extidnumber'],
                                    'tbl_org_id'=> $request['tbl_org_id'],
                                 ]);
                                }

                                if($request['ntaredaccacta']!=null){
	                              	Nota::create([                                    
                                    'nota'=>str_replace('._','.0',$request['ntaredaccacta']), //cotrte1
                                    'cptnotaid'=>10, //competencia
                                    'orgntsid'=> $request['orgntsid'], //expedientes
                                    'segid'=> $request['segid'],//id tabla asignaciones
                                    'tpntid'=> $request['tpntid'],
                                    'perid'=> $request['perid'],//id tabla procedencia
                                    'estidnumber'=> $request['estidnumber'],                       
                                    'extidnumber'=> $request['extidnumber'],
                                    'tbl_org_id'=> $request['tbl_org_id'],
                                 ]);
                                }

                                if($request['ntapuntualidad']!=null){
	                              	Nota::create([                                    
                                    'nota'=>str_replace('._','.0',$request['ntapuntualidad']), //cotrte1
                                    'cptnotaid'=>6, //competencia
                                    'orgntsid'=> $request['orgntsid'], //expedientes
                                    'segid'=> $request['segid'],//id tabla asignaciones
                                    'tpntid'=> $request['tpntid'],
                                    'perid'=> $request['perid'],//id tabla procedencia
                                    'estidnumber'=> $request['estidnumber'],                       
                                    'extidnumber'=> $request['extidnumber'],
                                    'tbl_org_id'=> $request['tbl_org_id'],
                                 ]);
                                }
     
		 	

  }
  
  public function get_nota_corte_ext($concepto,$tbl_org_id,$segmento=0){

    if ($segmento!=0){
        $segmento = Segmento::join('sede_segmentos as sg','sg.segmento_id','=','segmentos.id')			
        ->where('sg.sede_id',session('sede')->id_sede)
        ->where(['id'=>$segmento])->first();
       
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

    $n_manaudiencia = [];
    $n_analisisformulas = [];
    $n_puntualidad = [];
    $n_prespersonal = [];
    $n_planconciliacion = [];
    $n_redaccacta = [];

    
    $response = [
      'nota'=>0,
      'id'=>0,
      'tipo'=>0,
      'tipo_id'=>0,
      'docidnumber'=>0, 
      'docevname'=>0, 
      'estidnumber'=>0, 
      'estname'=>0,      
      'periodo'=>0,
      'segmento'=>0, 
      'segmento_id'=>0, 
      'created_at'=>0, 
      'updated_at'=>0, 
                    
    ];

    
    if ($segmento) { 
     
      $notas =  $this->notas_ext()
        ->where(['orgntsid'=>$this->origen,'segid'=>$segmento->id,'tbl_org_id'=>$tbl_org_id])
        ->get();
     
      if(count($notas)>0){
        foreach ($notas as $key => $nota) {
          // if ($nota->segid==$segmento->id) {
             $data = [
                   'nota'=>$nota->nota,
                   'id'=> $nota->id,
                   'tipo'=>$nota->tipo_nota->tpntnombre,
                   'tipo_id'=>$nota->tipo_nota->id,
                   'docidnumber'=>$nota->extidnumber, 
                   'docevname'=>$nota->docente_eva->name.' '.$nota->docente_eva->lastname, 
                   'estidnumber'=>$nota->estidnumber, 
                   'estname'=>$nota->estudiante->name.' '.$nota->estudiante->lastname, 
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
              
              if ($nota->cptnotaid==5){
                $n_analisisformulas[] =  $data;
               } 
               if ($nota->cptnotaid==6){
                $n_puntualidad[] =  $data;
               } 

               if ($nota->cptnotaid==7){
                $n_prespersonal[] =  $data;
               } 
               if ($nota->cptnotaid==8){
                $n_manaudiencia[] =  $data;
               } 
               if ($nota->cptnotaid==9){
                $n_planconciliacion[] =  $data;
               } 
               if ($nota->cptnotaid==10){
                $n_redaccacta[] =  $data;
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
                'estname'=>$n_conocimiento[0]['estname'],
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
                'estname'=>$n_aplicacion[0]['estname'],
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
                    'estname'=>$n_etica[0]['estname'],
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
                    'estname'=>$n_concepto[0]['estname'],
                    'periodo'=>$n_concepto[0]['periodo'],
                  'segmento'=>$n_concepto[0]['segmento'], 
                  'segmento_id'=>$n_concepto[0]['segmento_id'], 
                  'created_at'=>$n_concepto[0]['created_at'], 
                  'updated_at'=>$n_concepto[0]['updated_at'],
                ];
            }
              break; 
////////////////////////////////////////////
              case 'manejo_audiencia':
                if (count($n_manaudiencia)>0) {
                  // $promedio = $this->get_promedio($n_etica);
                    $response = [
                        'nota'=>$n_manaudiencia[0]['nota'],
                        'id'=>$n_manaudiencia[0]['id'],
                        'tipo'=>$n_manaudiencia[0]['tipo'],
                        'tipo_id'=>$n_manaudiencia[0]['tipo_id'],
                        'docidnumber'=>$n_manaudiencia[0]['docidnumber'],
                        'docevname'=>$n_manaudiencia[0]['docevname'],
                        'estidnumber'=>$n_manaudiencia[0]['estidnumber'],
                        'estname'=>$n_manaudiencia[0]['estname'],
                        'periodo'=>$n_manaudiencia[0]['periodo'],
                      'segmento'=>$n_manaudiencia[0]['segmento'], 
                      'segmento_id'=>$n_manaudiencia[0]['segmento_id'], 
                      'created_at'=>$n_manaudiencia[0]['created_at'], 
                      'updated_at'=>$n_manaudiencia[0]['updated_at'],
                    ];
                }
                  break; 

                  case 'analisis_formula':
                    if (count($n_analisisformulas)>0) {
                      // $promedio = $this->get_promedio($n_etica);
                        $response = [
                            'nota'=>$n_analisisformulas[0]['nota'],
                            'id'=>$n_analisisformulas[0]['id'],
                            'tipo'=>$n_analisisformulas[0]['tipo'],
                            'tipo_id'=>$n_analisisformulas[0]['tipo_id'],
                            'docidnumber'=>$n_analisisformulas[0]['docidnumber'],
                            'docevname'=>$n_analisisformulas[0]['docevname'],
                            'estidnumber'=>$n_analisisformulas[0]['estidnumber'],
                            'estname'=>$n_analisisformulas[0]['estname'],
                            'periodo'=>$n_analisisformulas[0]['periodo'],
                          'segmento'=>$n_analisisformulas[0]['segmento'], 
                          'segmento_id'=>$n_analisisformulas[0]['segmento_id'], 
                          'created_at'=>$n_analisisformulas[0]['created_at'], 
                          'updated_at'=>$n_analisisformulas[0]['updated_at'],
                        ];
                    }
                      break; 

                      case 'puntualidad':
                        if (count($n_puntualidad)>0) {
                          // $promedio = $this->get_promedio($n_etica);
                            $response = [
                                'nota'=>$n_puntualidad[0]['nota'],
                                'id'=>$n_puntualidad[0]['id'],
                                'tipo'=>$n_puntualidad[0]['tipo'],
                                'tipo_id'=>$n_puntualidad[0]['tipo_id'],
                                'docidnumber'=>$n_puntualidad[0]['docidnumber'],
                                'docevname'=>$n_puntualidad[0]['docevname'],
                                'estidnumber'=>$n_puntualidad[0]['estidnumber'],
                                'estname'=>$n_puntualidad[0]['estname'],
                                'periodo'=>$n_puntualidad[0]['periodo'],
                              'segmento'=>$n_puntualidad[0]['segmento'], 
                              'segmento_id'=>$n_puntualidad[0]['segmento_id'], 
                              'created_at'=>$n_puntualidad[0]['created_at'], 
                              'updated_at'=>$n_puntualidad[0]['updated_at'],
                            ];
                        }
                          break; 

                          case 'presentancion_personal':
                            if (count($n_prespersonal)>0) {
                              // $promedio = $this->get_promedio($n_etica);
                                $response = [
                                    'nota'=>$n_prespersonal[0]['nota'],
                                    'id'=>$n_prespersonal[0]['id'],
                                    'tipo'=>$n_prespersonal[0]['tipo'],
                                    'tipo_id'=>$n_prespersonal[0]['tipo_id'],
                                    'docidnumber'=>$n_prespersonal[0]['docidnumber'],
                                    'docevname'=>$n_prespersonal[0]['docevname'],
                                    'estidnumber'=>$n_prespersonal[0]['estidnumber'],
                                    'estname'=>$n_prespersonal[0]['estname'],
                                    'periodo'=>$n_prespersonal[0]['periodo'],
                                  'segmento'=>$n_prespersonal[0]['segmento'], 
                                  'segmento_id'=>$n_prespersonal[0]['segmento_id'], 
                                  'created_at'=>$n_prespersonal[0]['created_at'], 
                                  'updated_at'=>$n_prespersonal[0]['updated_at'],
                                ];
                            }
                              break; 

                              case 'plantillas':
                                if (count($n_planconciliacion)>0) {
                                  // $promedio = $this->get_promedio($n_etica);
                                    $response = [
                                        'nota'=>$n_planconciliacion[0]['nota'],
                                        'id'=>$n_planconciliacion[0]['id'],
                                        'tipo'=>$n_planconciliacion[0]['tipo'],
                                        'tipo_id'=>$n_planconciliacion[0]['tipo_id'],
                                        'docidnumber'=>$n_planconciliacion[0]['docidnumber'],
                                        'docevname'=>$n_planconciliacion[0]['docevname'],
                                        'estidnumber'=>$n_planconciliacion[0]['estidnumber'],
                                        'estname'=>$n_planconciliacion[0]['estname'],
                                        'periodo'=>$n_planconciliacion[0]['periodo'],
                                      'segmento'=>$n_planconciliacion[0]['segmento'], 
                                      'segmento_id'=>$n_planconciliacion[0]['segmento_id'], 
                                      'created_at'=>$n_planconciliacion[0]['created_at'], 
                                      'updated_at'=>$n_planconciliacion[0]['updated_at'],
                                    ];
                                }
                                  break; 

                                  case 'redaccion_acta':
                                    if (count($n_redaccacta)>0) {
                                      // $promedio = $this->get_promedio($n_etica);
                                        $response = [
                                            'nota'=>$n_redaccacta[0]['nota'],
                                            'id'=>$n_redaccacta[0]['id'],
                                            'tipo'=>$n_redaccacta[0]['tipo'],
                                            'tipo_id'=>$n_redaccacta[0]['tipo_id'],
                                            'docidnumber'=>$n_redaccacta[0]['docidnumber'],
                                            'docevname'=>$n_redaccacta[0]['docevname'],
                                            'estidnumber'=>$n_redaccacta[0]['estidnumber'],
                                            'estname'=>$n_redaccacta[0]['estname'],
                                            'periodo'=>$n_redaccacta[0]['periodo'],
                                          'segmento'=>$n_redaccacta[0]['segmento'], 
                                          'segmento_id'=>$n_redaccacta[0]['segmento_id'], 
                                          'created_at'=>$n_redaccacta[0]['created_at'], 
                                          'updated_at'=>$n_redaccacta[0]['updated_at'],
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
            $suma += $nota['nota'];
        }
        $promedio = $suma / $totaln;
    }
   
  
    return $promedio;
  
  }
   
  public function get_notas_ext($tbl_org_id,$notas){ 


$notas_result=[];
foreach ($notas as $key => $nota) {
  # code...

    $notas_result[$nota] = [
        "nota_$nota"=> number_format($this->get_nota_corte_ext($nota,$tbl_org_id)['nota'],1,'.','.'),  
        "idnota_$nota"=>$this->get_nota_corte_ext($nota,$tbl_org_id)['id'],      
        "can_edit"=>true,
        "encontrado"=>true,
        "segmento"=>$this->get_nota_corte_ext($nota,$tbl_org_id)['segmento'],
        "periodo"=>$this->get_nota_corte_ext($nota,$tbl_org_id)['periodo'],
        "tipo"=>$this->get_nota_corte_ext($nota,$tbl_org_id)['tipo'],
        "tipo_id"=>$this->get_nota_corte_ext($nota,$tbl_org_id)['tipo_id'],
        "segmento_id"=>$this->get_nota_corte_ext($nota,$tbl_org_id)['segmento_id'],
        "docevname"=>$this->get_nota_corte_ext($nota,$tbl_org_id)['docevname'],
        "docevidnumber"=>$this->get_nota_corte_ext($nota,$tbl_org_id)['docidnumber'],
        "estname"=>$this->get_nota_corte_ext($nota,$tbl_org_id)['estname'],
        "estidnumber"=>$this->get_nota_corte_ext($nota,$tbl_org_id)['estidnumber'],
        'created_at'=>$this->get_nota_corte_ext($nota,$tbl_org_id)['created_at'],
        'updated_at'=>$this->get_nota_corte_ext($nota,$tbl_org_id)['updated_at'],
    ];
    
    if ($notas_result[$nota]["nota_$nota"]==0 || $notas_result[$nota]["idnota_$nota"]==0) {
       $notas_result[$nota]["encontrado"] = false;
    }
    if (\Auth::user()->idnumber != $this->get_nota_corte_ext($nota,$tbl_org_id)['docidnumber'] || currentUser()->hasRole('estudiante') || $notas_result[$nota]["nota_$nota"] == 'Evaluado por sistema (fecha límite vencida)') {
        $notas_result[$nota]["can_edit"] = false;
     }

    if (currentUser()->hasRole('amatai') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('diradmin')) {
      $notas_result[$nota]["can_edit"] = true;
   }
   if ($notas_result[$nota]["nota_$nota"] == 'Evaluado por sistema (fecha límite vencida)') {
      $notas_result[$nota]["docevname"] = 'Sistema. Fecha de evaluación: '. $notas_result[$nota]["created_at"];
 }

}


    return $notas_result;

 /*    $notas_result[$nota] = [
      "nota_conocimiento"=> number_format($this->get_nota_corte_ext('conocimiento',$tbl_org_id)['nota'],1,'.','.'),
      "nota_conocimientoid"=>$this->get_nota_corte_ext('conocimiento',$tbl_org_id)['id'],
      "nota_etica"=>number_format($this->get_nota_corte_ext('etica',$tbl_org_id)['nota'],1,'.','.'),
      'nota_eticaid'=>$this->get_nota_corte_ext('etica',$tbl_org_id)['id'],
      "nota_aplicacion"=>number_format($this->get_nota_corte_ext('aplicacion',$tbl_org_id)['nota'],1,'.','.'),
      "nota_aplicacionid"=>$this->get_nota_corte_ext('aplicacion',$tbl_org_id)['id'],
      'nota_concepto'=>$this->get_nota_corte_ext('concepto',$tbl_org_id)['nota'],
      'nota_conceptoid'=>$this->get_nota_corte_ext('concepto',$tbl_org_id)['id'],
      "nota_final"=>number_format($this->get_nota_corte_ext('final',$tbl_org_id)['nota'],1,'.','.'),
      "can_edit"=>true,
      "encontrado"=>true,
      "segmento"=>$this->get_nota_corte_ext('conocimiento',$tbl_org_id)['segmento'],
      "periodo"=>$this->get_nota_corte_ext('conocimiento',$tbl_org_id)['periodo'],
      "tipo"=>$this->get_nota_corte_ext('conocimiento',$tbl_org_id)['tipo'],
      "tipo_id"=>$this->get_nota_corte_ext('conocimiento',$tbl_org_id)['tipo_id'],
      "segmento_id"=>$this->get_nota_corte_ext('conocimiento',$tbl_org_id)['segmento_id'],
      "docevname"=>$this->get_nota_corte_ext('conocimiento',$tbl_org_id)['docevname'],
      'created_at'=>$this->get_nota_corte_ext('conocimiento',$tbl_org_id)['created_at'],
      'updated_at'=>$this->get_nota_corte_ext('conocimiento',$tbl_org_id)['updated_at'],
  ]; */

}

function setOrigen($origen){ 
  $this->origen = $origen;
  return $this;
}

} 