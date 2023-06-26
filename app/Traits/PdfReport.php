<?php
namespace App\Traits;

use Illuminate\Http\Request;
use App\AudienciaConciliacion;
use App\PdfReporteAditionalData;
use Carbon\Carbon;

trait PdfReport
{
    public function getBody($reporte, $conciliacion)
    {
        if ($conciliacion != null) {
            $json = json_decode($reporte->report_keys);
            $bodytag = $reporte->reporte;
            if (count($json) > 0) {
                // dd($json);
                foreach ($json as $key => $data) {
                    
                    foreach (
                        $conciliacion
                            ->usuarios()
                            ->where('tipo_usuario_id', $data->user_type)
                            ->orderBy('conciliacion_has_user.created_at', 'desc')
                            ->get()
                        as $key_2 => $user
                    ) {
                        
                        if ($data->table == 'users') {
                            $label = $data->short_name;
                            $value = $user->$label;
                            if ($label == 'estado_civil') {
                                $value = $user->estado_civil->ref_nombre;
                            }
                        } elseif ($data->table == 'aditional_data') {
                            if ($user->getDataValWShort($data->short_name)) {
                                $value = $user->getDataValWShort($data->short_name)->value . ' ' . $user->getDataValWShort($data->short_name)->value_is_other;
                            }
                        }
                        if (isset($value)) {
                            $bodytag = str_replace($data->name, $value, $bodytag);
                        }
                    }
                    if ($data->table == 'conc_hechos_preten') {
                        // dd($data );
                        $id = $data->short_name == 'hechos' ? 206 : 207;
                        $hechos = $conciliacion
                            ->hechos_pretensiones()
                            ->where('tipo_id', $id)
                            ->get();
                        if (count($hechos) > 0) {
                            $hechos_cadena = "<ul class='list_hp'>";
                            foreach ($hechos as $key => $hp) {
                                $hechos_cadena .= '<li>' . $hp->descripcion . '</li>';
                            }
                            $hechos_cadena .= '</ul>';
                            $bodytag = str_replace($data->name, $hechos_cadena, $bodytag);
                            //  $bodytag .= $hechos_cadena;
                           
                        }
                    } elseif ($data->table == 'conciliacion_audiencias') {
                        $audiencia = AudienciaConciliacion::where('id_conciliacion', $conciliacion->id)->first();
                        $diaActual = $data->name;
                        if ($audiencia) {
                            $diaActual = $audiencia->getFecha();
                        }
                        $bodytag = str_replace($data->name, $diaActual, $bodytag);
                    } elseif ($data->table == 'pdf_reportes' and $reporte->id!=null) {
                        $ref_data = getAditionalDataByShortName($data->short_name, 'pdf_reportes');
                        if ($ref_data) {
                            $personalized_data = PdfReporteAditionalData::where([
                                'reference_data_id' => $ref_data->id,
                                'reference_data_option_id' => $ref_data->options()->first()->id,
                                'reporte_id' => $reporte->id,
                            ])->first();
                            if ($personalized_data) {
                                $bodytag = str_replace($data->name, $personalized_data->value, $bodytag);
                            }
                        }
                    }elseif($data->table == 'conciliaciones'){
                        if($data->short_name == 'fecha_hora_radicado'){                            
                            $fecha_ra = Carbon::parse($conciliacion->fecha_radicado);
                            $hora_ra = $fecha_ra->toTimeString();
                            if($hora_ra > '18:00:00' and $hora_ra < '23:59:59'){
                                
                                if($fecha_ra->dayOfWeek == 5){
                                    $fecha_ra = $fecha_ra->addDay('3');   
                                    $fecha_ra->setTimeFromTimeString('08:00:00')   ;                          
                                    
                                }elseif($fecha_ra->dayOfWeek==6){
                                    $fecha_ra = $fecha_ra->addDay('2');   
                                    $fecha_ra->setTimeFromTimeString('08:00:00')   ;                          
                                    
                                }elseif($fecha_ra->dayOfWeek==0){
                                    $fecha_ra = $fecha_ra->addDay('1');   
                                    $fecha_ra->setTimeFromTimeString('08:00:00')   ;                          
                                    
                                }else{
                                    $fecha_ra = $fecha_ra->addDay('1');   
                                    $fecha_ra->setTimeFromTimeString('08:00:00')   ;                          
                                    
                                }
                                                              
                            }elseif($hora_ra >= '00:00:00' and $hora_ra < '08:00:00'){
                                $fecha_ra->setTimeFromTimeString('08:00:00')   ;                          
                                 
                            }else{
                               
                            }
                            $fecha_ra = getLongDateWithHour($fecha_ra);
                            $bodytag = str_replace($data->name,$fecha_ra, $bodytag);
                        }

                        if($data->short_name == 'numero_radicado'){
                            $bodytag = str_replace($data->name, $conciliacion->num_conciliacion, $bodytag);
                        }

                        if($data->short_name == 'mes_anio_actual'){
                            $fecha = getMonthAndYear(date('Y-m-d'));
                            $bodytag = str_replace($data->name, $fecha, $bodytag);
                        }
                        
                    }
                }
            }
        } else {
            $bodytag = $reporte->reporte;
        }
        return $bodytag;
    }

    public function setConfig(Request $request)
    {
        return $config = [
            'tipo_papel' => $request->tipo_papel,
            'top' => $request->top,
            'right' => $request->right,
            'bottom' => $request->bottom,
            'left' => $request->left,
            'margin_string' => $request->top . 'px ' . $request->right . 'px ' . $request->bottom . 'px ' . $request->left . 'px',
        ];
    }

    public function setEncaConfig(Request $request)
    {
        return $config = [
            'encabezado_align' => $request->encabezado_align,
            'encab_width' => $request->encab_width,
            'encab_height' => $request->encab_height,
        ];
    }

    public function setPieConfig(Request $request)
    {
        return $config = [
            'pie_align' => $request->pie_align,
            'pie_width' => $request->pie_width,
            'pie_height' => $request->pie_height,
        ];
    }

    public function hasValuesPersonalized($reporte)
    {
        $json = json_decode($reporte->report_keys);
        $edited = false;
        $dats = [];
        if (count($json) > 0) {
            foreach ($json as $key => $data) {
                if ($data->user_type == 'personalized') {
                    $edited = true;
                    break;
                }
            }
        }
        return $edited;
    }

    public function hasEmptyValuesPersonalized($reporte)
    {
        $json = json_decode($reporte->report_keys);
        $edited = false;
        $dats = [];
        if (count($json) > 0) {
            foreach ($json as $key => $data) {
                if ($data->user_type == 'personalized') {
                    $ref_data = getAditionalDataByShortName($data->short_name, 'pdf_reportes');
                    if ($ref_data) {
                        $old_data = PdfReporteAditionalData::where([
                            'reference_data_id' => $ref_data->id,
                            'reference_data_option_id' => $ref_data->options()->first()->id,
                            'reporte_id' => $reporte->id,
                        ])->first();
                        if (!$old_data) {
                            $edited = true;
                            break;
                        }
                    }
                }
            }
        }
        return $edited;
    }
}
