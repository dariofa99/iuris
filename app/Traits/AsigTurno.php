<?php
namespace App\Traits;
use App\Turno;
use DB;
use App\Periodo;
use App\Asigna_docen_est;
trait AsigTurno {

public function asignarTurno($request){	
    $consulta="";
    $horario=[];
    if ($request->cursando_id == '114') {///// 4a
        $h1=strval(rand(112, 113));
        $horario=[$h1,''];
        if ($horario[0] == '112') {$horario[1] = '113';} elseif ($horario[0] == '113') {$horario[1] = '112';}
        $consulta="`trnid_horario` = ".$horario[0]." OR `trnid_horario` = ".$horario[1];
    } elseif ($request->cursando_id == '115') {///// 4b
        $h1=strval(rand(110, 111));
        $horario=[$h1,''];
        if ($horario[0] == '110') {$horario[1] = '111';} elseif ($horario[0] == '111') {$horario[1] = '110';}
        $consulta="`trnid_horario` = ".$horario[0]." OR `trnid_horario` = ".$horario[1];
    } elseif ($request->cursando_id == '116') {///// 5a
        $horario=['119'];
        $consulta="`trnid_horario` = 119";
    } elseif ($request->cursando_id == '117') {///// 5b
        $horario=['118'];
        $consulta="`trnid_horario` = 118";
    } 
    if ($consulta != "") {
        $cupos = DB::select(
            DB::raw("SELECT CONCAT(`trnid_horario`,'_',`trnid_color`) AS 'hc', COUNT(CONCAT(`trnid_horario`,'_',`trnid_color`)) AS 'chc' FROM `turnos` WHERE $consulta AND `trnid_color`<> 120 GROUP BY hc ORDER BY chc ASC")
            );
        $cupos_color_total = DB::table('turnos')
            ->select('trnid_color as id')
            ->where('trnid_color','<>',120)
            ->groupBy('trnid_color')
            ->orderByRaw('COUNT(`trnid_color`) ASC')
            ->get();          
        $colores_total = DB::table('referencias_tablas')
            ->select('id')
            ->where(['categoria'=>'color','tabla_ref'=>'turnos'])
            ->where('id','<>',120)
            ->inRandomOrder()
            ->get();
            
            if (isset($cupos_color_total[0])) {
                if (count($cupos_color_total) < 5) {
                    foreach ($colores_total as $key1 => $colorest_id) {
                        foreach ($cupos_color_total as $key2 => $cupost_id) { 
                            if ($colorest_id->id == $cupost_id->id){
                                unset($colores_total[$key1]);
                            }
                        }
                    }
                    foreach ($cupos_color_total as $key => $cupost_id) { 
                        $colores_total->push($cupos_color_total[$key]);
                    }
                    $colores=$colores_total;
                } else {
                    $colores = $cupos_color_total;
                }                
            } else {
                $colores = $colores_total;
            }
           
        if (isset($cupos[0])) {    
            if ((count($horario)==1 && count($cupos)==5) ||  (count($horario)==2 && count($cupos)==10)) {
                foreach ($cupos as $key => $value) {
                    if ($cupos[0]->chc != $value->chc) {
                        unset($cupos[$key]);
                    }
                }
                
                if(count($cupos) > 1){
                    foreach ($colores as $key => $colores_id) {
                        foreach ($cupos as $key => $value) {
                            $r_consulta=$value->hc;
                            $r_consulta=explode("_", $r_consulta);
                            if ($r_consulta[1]==$colores_id->id) {
                                $trnid_horario=$r_consulta[0];
                                $trnid_color=$colores_id->id;
                                break 2;
                            }
                        }
                    }
                } else {
                    $r_consulta=$cupos[0]->hc;
                    $r_consulta=explode("_", $r_consulta);
                    $trnid_horario=$r_consulta[0];
                    $trnid_color=$r_consulta[1];
                }
            } else {
               
                $con = 0;
                foreach ($horario as $key => $horario_id) {
                    foreach ($colores as $key => $colores_id) {
                        $concat_var = $horario_id."_".$colores_id->id;
                        if (isset($cupos[$con])) {
                            if (array_search($concat_var, array_column($cupos, 'hc'))===false) {
                                $trnid_horario=$horario_id;
                                $trnid_color=$colores_id->id;
                                break 2;
                            }
                        } else {
                            if (array_search($concat_var, array_column($cupos, 'hc'))===false) {
                                $trnid_horario=$horario_id;
                                $trnid_color=$colores_id->id;
                                break 2;
                            }
                        }
                    $con++;
                    }
                }
            }
        } else {
            $trnid_horario=$horario[0];
            $trnid_color=$colores->first()->id;
        }
            $periodo = Periodo::join('sede_periodos as sp','sp.periodo_id','=','periodo.id')
            ->where('sp.sede_id',session('sede')->id_sede)
            ->where('estado',true)->first();
            if ($periodo) {
              $turno = [
                'trnid_estudent'=>currentUser()->idnumber,
                'trnid_color'=>$trnid_color,
                'trnid_horario'=>$trnid_horario,
                'trnid_oficina'=>1,
                'trnid_dia'=>143,
                'trnid_periodo'=>$periodo->id,
                'trnusercreated'=>currentUser()->idnumber,
                'trnuserupdated'=>currentUser()->idnumber,

                ];
             
                $this->createTurno($turno);
                return true;
            }
        return false;    
    }
}

    public function createTurno($turno){
           Turno::create($turno);

    }

    

}