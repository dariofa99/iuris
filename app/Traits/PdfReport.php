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
                foreach ($json as $key => $data) {
                    // $data = $json[12];

                    if ($data->table == 'users') {
                        $user = $conciliacion->getUser($data->data_type);

                        if ($user->id != null) {
                            $data_us = pdfReportsDataValues()['users'];
                            $table_name = obtenerTableName($data_us, $data->short_name);
                            if ($table_name == 'tipodoc_id') {
                                $value = $user->tipo_doc->ref_nombre;
                            } elseif ($table_name == 'genero_id') {
                                $value = $user->genero->ref_nombre;
                            } elseif ($table_name == 'estadocivil_id') {
                                $value = $user->estado_civil->ref_nombre;
                            } elseif ($table_name == 'tipopers_id') {
                                $value = $user->tipo_persona->ref_nombre;
                            } elseif ($data->short_name == 'tarjeta_profesional') {
                                $value = $user->codigo_estudiantil;
                            } else {
                                $value = $user->$table_name;
                            }
                            //dd($value);
                            if (isset($value) and $value != '') {
                                $bodytag = str_replace($data->data_text, $value, $bodytag);
                            }
                        }
                    } else if ($data->table == 'users_aditional_data') {
                        $user = $conciliacion->getUser($data->data_type);

                        if ($user and $user->getDataValWShort($data->short_name)) {
                            $value = $user->getDataValWShort($data->short_name)->value;

                            if (isset($value) and $value != '') {
                                $bodytag = str_replace($data->data_text, $value, $bodytag);
                            }
                        }
                    } else if ($data->table == 'conc_hechos_pretensiones') {
                        // dd($data );
                        $id = $data->short_name == 'hechos' ? 206 : (($data->short_name == 'acuerdos') ? 208 : 207);
                        $hechos = $conciliacion
                            ->hechos_pretensiones()
                            ->where('tipo_id', $id)
                            ->get();
                        if (count($hechos) > 0) {
                            $hechos_cadena = "<ul class='list_hp' style='text-align:justify'>";
                            foreach ($hechos as $key => $hp) {
                                $hechos_cadena .= "<li style='padding:2px;margin-bottom:2px'> " . $hp->descripcion . '</li>';
                            }
                            $hechos_cadena .= '</ul>';
                            $bodytag = str_replace($data->data_text, $hechos_cadena, $bodytag);
                            //  $bodytag .= $hechos_cadena;

                        }
                    } elseif ($data->table == 'conciliacion_audiencias') {
                        $audiencia = AudienciaConciliacion::where('id_conciliacion', $conciliacion->id)->first();
                        $diaActual = $data->name;
                        if ($audiencia) {
                            $diaActual = $audiencia->getFecha();
                        }
                        dd($audiencia);
                        $bodytag = str_replace($data->data_text, $diaActual, $bodytag);
                    } elseif ($data->table == 'pdf_reportes' and $reporte->id != null) {
                        $ref_data = getAditionalDataByShortName($data->short_name, 'pdf_reportes');
                        if ($ref_data) {
                            $personalized_data = PdfReporteAditionalData::where([
                                'reference_data_id' => $ref_data->id,
                                'reference_data_option_id' => $ref_data->options()->first()->id,
                                'reporte_id' => $reporte->id,
                            ])->first();
                            if ($personalized_data) {
                                $bodytag = str_replace($data->data_text, $personalized_data->value, $bodytag);
                            }
                        }
                    } elseif ($data->table == 'conciliaciones') {
                        if ($data->short_name == 'fecha_hora_radicado') {
                            $fecha_ra = Carbon::parse($conciliacion->fecha_radicado);
                            $hora_ra = $fecha_ra->toTimeString();
                            if ($hora_ra > '18:00:00' and $hora_ra < '23:59:59') {

                                if ($fecha_ra->dayOfWeek == 5) {
                                    $fecha_ra = $fecha_ra->addDay('3');
                                    $fecha_ra->setTimeFromTimeString('08:00:00');
                                } elseif ($fecha_ra->dayOfWeek == 6) {
                                    $fecha_ra = $fecha_ra->addDay('2');
                                    $fecha_ra->setTimeFromTimeString('08:00:00');
                                } elseif ($fecha_ra->dayOfWeek == 0) {
                                    $fecha_ra = $fecha_ra->addDay('1');
                                    $fecha_ra->setTimeFromTimeString('08:00:00');
                                } else {
                                    $fecha_ra = $fecha_ra->addDay('1');
                                    $fecha_ra->setTimeFromTimeString('08:00:00');
                                }
                            } elseif ($hora_ra >= '00:00:00' and $hora_ra < '08:00:00') {
                                $fecha_ra->setTimeFromTimeString('08:00:00');
                            } else {
                            }
                            $fecha_ra = getLongDateWithHour($fecha_ra);
                            $bodytag = str_replace($data->data_text, $fecha_ra, $bodytag);
                        }

                        if ($data->short_name == 'numero_radicado') {
                            $bodytag = str_replace($data->data_text, $conciliacion->num_conciliacion, $bodytag);
                        }                      

                        if ($data->short_name == 'mes_anio_actual') {
                            $fecha = getMonthAndYear(date('Y-m-d'));
                            $bodytag = str_replace($data->data_text, $fecha, $bodytag);
                        }
                    }else if($data->table=='audiencias'){
                        if ($data->short_name == 'fecha_hora_audiencia') {
                            $audiencia = AudienciaConciliacion::where('id_conciliacion', $conciliacion->id)->first();
                            $diaActual = $data->name;
                            if ($audiencia) {
                                $diaActual = $audiencia->getFecha();
                            }
                            $bodytag = str_replace($data->data_text, $diaActual, $bodytag);
                        }
                    }
                }
               // dd($json);
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
