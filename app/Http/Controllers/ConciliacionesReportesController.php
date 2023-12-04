<?php

namespace App\Http\Controllers;

use App\Conciliacion;
use App\ConciliacionEstado;
use App\ConciliacionEstadoReporteGenerado;
use App\ConciliacionPdfTemporal;
use App\ConciliacionReporte;
use App\Mail\Firma;
use App\Mail\RevocarFirma;
use App\PdfReporte;
use App\User;
use App\PdfReporteDestino;
use App\TablaReferencia;
use App\Traits\PdfReport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\PdfReporteAditionalData;

class ConciliacionesReportesController extends Controller
{
    use PdfReport;
    public function getPdfReportForStatus(Request $request)
    {
        $reportes = PdfReporteDestino::whereHas('reporte', function (Builder $query) {
            $query->where('is_copy', 0);
        })
            ->with('reporte')
            ->where($request->except(['_', 'conciliacion_id', 'conc_estado_id']))
            ->get();

        return response()->json($reportes);
    }
    private function getReportes(Request $request)
    {
        $reportes = PdfReporteDestino::whereHas('reporte', function (Builder $query) {
            $query->where('is_copy', 1);
        })
            ->whereHas('temporales', function (Builder $query) use ($request) {
                $query->where('conciliacion_id', $request->conciliacion_id);
            })
            //    ->with('users')
            ->where($request->except(['_', 'conciliacion_id', 'conc_estado_id']))
            ->get();
        // return $reportes;
        $reportes->each(function ($reporte) use ($request) {
            $file = ConciliacionEstadoReporteGenerado::where([
                'status_id' => $request->status_id,
                'conciliacion_id' => $request->conciliacion_id,
                'reporte_id' => $reporte->reporte_id,
            ])->first();
            $reporte->is_created = false;
            if ($file) {
                $reporte->is_created = true;
            }
            $reporte->has_firm = false;
            $reporte->users = $reporte
                ->users()
                ->where([
                    'conciliacion_id' => $request->conciliacion_id,
                    'revocado' => 0,
                ])
                ->get();
            foreach ($reporte->users as $key_ => $user) {
                if ($user->pivot->firmado == 0) {
                    $reporte->has_firm = true;
                    break;
                }
            } 
            $reporte->hasValuesPersonalized = false;
            
          /*   if ($this->hasValuesPersonalized($reporte->reporte)) {
                $reporte->hasEmptyValuesPersonalized = false;
                $reporte->hasValuesPersonalized = true;
                if ($this->hasEmptyValuesPersonalized($reporte->reporte)) {
                    $reporte->hasEmptyValuesPersonalized = true;
                }
            } */
        });
        return $reportes;
    }
    public function getPdfReportesConciliacion(Request $request)
    {

        $conciliacion = Conciliacion::find($request->conciliacion_id);
        $reportes = $this->getReportes($request);
        /* $json = json_decode($reportes[0]->reporte->report_keys);
        return response()->json($json); */
        $view = view('myforms.conciliaciones.componentes.pdf_report_list', compact('reportes', 'conciliacion'))->render();
        $response = [
            'conc_estado_id' => $request->conc_estado_id,
            'data' => $reportes,
            'view' => $view,
        ];
        return response()->json($response);
    }
    public function getFirmantes(Request $request)
    {
        // return response()->json($request->all());

        $reporte_de = PdfReporteDestino::find($request->id);
        $reporte_de->reporte;
        $reporte_de->users = $reporte_de
            ->users()
            ->where([
                'conciliacion_id' => $request->conciliacion_id,
                'revocado' => 0,
            ])
            ->get();
        $ids = [];
        $tipos = [];
        $tipos_estados = [];
        $all_firmas = true;
        $pivots_id = [];
        foreach ($reporte_de->users as $key => $user) {
            // return response()->json($user);
            $ids[] = $user->id;
            $tipos[$user->id . '-' . $user->pivot->tipo_usuario_id] = $user->pivot->tipo_firma_id;
            $tipos_estados[$user->id . '-' . $user->pivot->tipo_usuario_id] = $user->pivot->firmado;
            $pivots_id[$user->id . '-' . $user->pivot->tipo_usuario_id] = $user->pivot->id;
            if ($user->pivot->tipo_firma_id == 209 and $user->pivot->firmado == 0) {
                $all_firmas = false;
            }
        }

        $conciliacion = Conciliacion::find($request->conciliacion_id);

        $view = view('myforms.conciliaciones.componentes.pdf_report_list_firmantes', compact('ids', 'pivots_id', 'reporte_de', 'conciliacion', 'tipos', 'tipos_estados', 'all_firmas'))->render();

        $response = [
            'data' => $reporte_de,
            'view' => $view,
            'tipos' => $tipos,
            'tipos_estados' => $tipos_estados,
            'pivots_id' => $pivots_id,
            'all_firmas' => $all_firmas
        ];
        return response()->json($response);
    }
    function reenviarMails(Request $request)
    {
        // return response()->json($request->all());
        /*   $users = DB::table("pdf_reportes_users")
        ->whereIn('user_id',$request->email_user_id)
        ->where(['conciliacion_id'=>$request->conciliacion_id,
                'pdf_reporte_id'=>$request->estado_id
        ])->get(); */ 
        if ($request->email_user_id) {
            foreach ($request->email_user_id as $key => $us) {
                $user = DB::table('pdf_reportes_users')
                    ->where('user_id', $us)
                    ->where([
                        'conciliacion_id' => $request->conciliacion_id,
                        'pdf_reporte_id' => $request->estado_id,
                        'tipo_usuario_id' => $request->type_user_id[$key],
                        'revocado' => 0,
                        'firmado' => 0
                    ])
                    ->first();

                // return response()->json($user);
                if ($user) {
                    $referencia = TablaReferencia::find($request->type_user_id[$key]);
                    $user_m = User::find($user->user_id);
                    $user_m->token = $user->token;
                    $user_m->codigo = $user->codigo;
                    $user_m->calidad = $referencia->ref_nombre;
                    $user = DB::table('pdf_reportes_users')
                        ->where('user_id', $us)
                        ->where([
                            'conciliacion_id' => $request->conciliacion_id, 
                            'pdf_reporte_id' => $request->estado_id, 
                            'tipo_usuario_id' => $request->type_user_id[$key]
                            ])
                        ->update([
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    Mail::to($user_m)->send(new Firma($user_m));
                }
            }

            return response()->json($user);
        }

        return response()->json($request->all());
    }

    function setFirmantes(Request $request)
    {
        //  return response()->json($request->all());
        $pdf_rpd = PdfReporteDestino::find($request->estado_id);

        if ($request->delete_users_id) {
            foreach ($request->delete_users_id as $key => $delete_user) {
                $porciones = explode('-', $delete_user);
                $id = $porciones[0];
                $query = DB::table('pdf_reportes_users')
                    ->where('user_id', $id)
                    ->where([
                        'conciliacion_id' => $request->conciliacion_id,
                        'pdf_reporte_id' => $pdf_rpd->id,
                        'revocado' => 0,
                        'tipo_usuario_id' => $request->tipo_usuario_id[$key],
                    ])
                    ->delete();
            }
        }

        if ($request->user_id) {
            foreach ($request->user_id as $key => $id) {
                $pdf_reporte = PdfReporte::find($pdf_rpd->reporte_id);
                $referencia = TablaReferencia::find($request->tipo_usuario_id[$key]);

                $user = DB::table('pdf_reportes_users')
                    ->where('user_id', $id)
                    ->where([
                        'conciliacion_id' => $request->conciliacion_id,
                        'pdf_reporte_id' => $pdf_rpd->id,
                        'tipo_usuario_id' => $request->tipo_usuario_id[$key],
                        'revocado' => 0,
                    ])
                    ->first();

                if (!$user) {
                    $firma = 0;
                    $fecha_firma = null;
                    if ($request->tipo_firmante[$key] != 209) {
                        $firma = 1;
                        $fecha_firma = date("Y-m-d H:i:s");
                    }
                    $token = str_replace('/', '', bcrypt(time()));
                    $codigo = Str::random(5);
                    $user = $pdf_rpd->users()->attach($id, [
                        'tipo_usuario_id' => $request->tipo_usuario_id[$key],
                        'tipo_firma_id' => $request->tipo_firmante[$key],
                        'conciliacion_id' => $request->conciliacion_id,
                        'token' => $token,
                        'codigo' => $codigo,
                        'firmado' => $firma,
                        'fecha_firma' => $fecha_firma
                    ]);

                    if ($request->has('user_email_id') and in_array($id, $request->user_email_id)) {
                        $user = User::find($id);
                        $user->token = $token;
                        $user->codigo = $codigo;
                        $user->calidad = $referencia->ref_nombre;
                        $user->nombre_reporte = $pdf_reporte->nombre_reporte;
                        Mail::to($user)->send(new Firma($user));
                    }
                } else {
                    //return response()->json($user);
                    if ($user->tipo_firma_id != $request->tipo_firmante[$key] and $request->tipo_firmante[$key] == 209) {
                        $us = User::find($id);
                        $us->token = $user->token;
                        $us->codigo = $user->codigo;
                        $user->calidad = $referencia->ref_nombre;
                        $user->nombre_reporte = $pdf_reporte->nombre_reporte;
                        Mail::to($us)->send(new Firma($us));
                    }
                    if ($request->tipo_firmante[$key] == 209) {
                        $user = $pdf_rpd->users()->updateExistingPivot($id, [
                            'tipo_firma_id' => $request->tipo_firmante[$key],
                            'conciliacion_id' => $request->conciliacion_id,
                        ]);
                    } else {
                        $user = $pdf_rpd->users()->updateExistingPivot($id, [
                            'tipo_firma_id' => $request->tipo_firmante[$key],
                            'conciliacion_id' => $request->conciliacion_id,
                            'firmado' => 1,
                        ]);
                    }
                    //return response()->json($user);
                }
            }
        }

        return response()->json($request->all());
    }
    public function create()
    {
        $conciliaciones = Conciliacion::all();
        $reportes = PdfReporte::all();
        return view('myforms.summernote_reportes.edit', compact('conciliaciones', 'reportes'));
        //return view('myforms.conciliaciones.reportes.edit');
    }
    public function store(Request $request)
    {
        return response()->json($request->all());
        //$request['pdf_reporte_id']=$request->id;
        $config = $this->setConfig($request);
        $request['configuraciones'] = json_encode($config);
        $co_reporte = PdfReporte::create($request->except(['tipo_papel', 'is_temp']));
        $co_pdf = ConciliacionPdfTemporal::create([
            'reporte_pdf_id' => $co_reporte->id,
            'status_id' => $request->status_id,
            'parent_reporte_pdf_id' => $request->id,
            'conciliacion_id' => $request->conciliacion_id,
        ]);
        $reporte_ant = PdfReporte::find($request->id);
        if ($request->file('encabezado_file') != '') {
            $configuracion = json_encode($this->setEncaConfig($request));
            $file = $co_reporte->uploadFile($request->file('encabezado_file'), '/pdf_reportes_' . $co_reporte->id);
            $co_reporte->files()->attach($file, [
                'seccion' => 'encabezado',
                'configuracion' => $configuracion,
            ]);
        } else {
            $file_en = $reporte_ant
                ->files()
                ->where('seccion', 'encabezado')
                ->first();
            if ($file_en) {
                $co_reporte->files()->attach($file_en, [
                    'seccion' => 'encabezado',
                    'configuracion' => $file_en->pivot->configuracion,
                ]);
            }
        }
        if ($request->file('pie_file') != '') {
            $configuracion = json_encode($this->setPieConfig($request));
            $file = $co_reporte->uploadFile($request->file('pie_file'), '/pdf_reportes_' . $co_reporte->id);
            $co_reporte->files()->attach($file, [
                'seccion' => 'pie',
                'configuracion' => $configuracion,
            ]);
        } else {
            $file_pie = $reporte_ant
                ->files()
                ->where('seccion', 'pie')
                ->first();
            if ($file_pie) {
                $co_reporte->files()->attach($file_pie, [
                    'seccion' => 'pie',
                    'configuracion' => $file_pie->pivot->configuracion,
                ]);
            }
        }

        return response()->json($co_reporte);
    }

    public function update(Request $request, $id)
    {
        
        $config = $this->setConfig($request);
        $request['configuraciones'] = json_encode($config);
        $co_reporte = PdfReporte::find($id);
        $co_reporte->fill($request->all());
        $co_reporte->save();
        $file_en = $co_reporte
            ->files()
            ->where('seccion', 'encabezado')
            ->first();

        if ($file_en) {
            $configuracion = json_encode($this->setEncaConfig($request));
            $file_en->pivot->configuracion = $configuracion;
            $file_en->pivot->save();
        }
        if ($request->file('encabezado_file') != '') {
            if ($file_en and $file_en->path != '') {
                Storage::delete('public/' . $file_en->path);
            }
            $file = $file_en->delete();
            $file = $co_reporte->uploadFile($request->file('encabezado_file'), '/pdf_reportes_' . $co_reporte->id);
            $co_reporte->files()->attach($file, [
                'seccion' => 'encabezado',
                'configuracion' => $configuracion,
            ]);
        }
        $file_pie = $co_reporte
            ->files()
            ->where('seccion', 'pie')
            ->first();

        if ($file_pie) {
            $configuracion = json_encode($this->setPieConfig($request));
            $file_pie->pivot->configuracion = $configuracion;
            $file_pie->pivot->save();
        }

        if ($request->file('pie_file') != '') {
            if ($file_pie and $file_pie->path != '') {
                Storage::delete('public/' . $file_pie->path);
            }
            $file = $file_pie->delete();
            $file = $co_reporte->uploadFile($request->file('pie_file'), '/pdf_reportes_' . $co_reporte->id);
            $co_reporte->files()->attach($file, [
                'seccion' => 'pie',
                'configuracion' => $configuracion,
            ]);
        }
        return response()->json($co_reporte);
    }

    public function destroy($id)
    {
        // return $id;
        $pdf_temp = ConciliacionPdfTemporal::where(['reporte_pdf_id' => $id])->first();
        $co_reporte = PdfReporte::find($id);
        if (!$pdf_temp) {
            $file_en = $co_reporte
                ->files()
                ->where('seccion', 'encabezado')
                ->first();
            if ($file_en and $file_en->path != '') {
                Storage::delete('public/' . $file_en->path);
            }
            $file_pie = $co_reporte
                ->files()
                ->where('seccion', 'pie')
                ->first();
            if ($file_pie and $file_pie->path != '') {
                Storage::delete('public/' . $file_pie->path);
            }
        }
        $co_reporte->delete();
        return response()->json($co_reporte);
    }

    public function editReporteTemporal($id, $conciliacion, $estado)
    {
        // dd($id);
        $pdf_temp = ConciliacionPdfTemporal::where([
            'conciliacion_id' => $conciliacion,
            'status_id' => $estado,
            'parent_reporte_pdf_id' => $id,
        ])->first();
        if ($pdf_temp) {
            $reporte = $pdf_temp->reporte_child;
            $reporte->is_temp = true;
        } else {
            $reporte = PdfReporte::find($id);
        }
        $conciliacion = Conciliacion::find($conciliacion);
        $ocultar_menu = true;
        return view('myforms.summernote_reportes.edit_temporal', compact('reporte', 'ocultar_menu', 'conciliacion', 'estado'));
    }

    public function getAllPdf(Request $request)
    {
        //
        $conciliacion = Conciliacion::with('estados.files.userinestado')
            ->find($request->conciliacion_id);
        /* $conciliacion->estados->each(function ($estado) {
            $estado->files->each(function ($file){
                $file->userinconciliacion;
            });
        });  */

        // dd($conciliacion->estados);
        //$ocultar_menu = true;
        return response()->json($conciliacion);
    }

    public function revocarFirmas(Request $request)
    {
        //

        $users = DB::table('users')
            ->join('pdf_reportes_users', 'pdf_reportes_users.user_id', '=', 'users.id')
            ->where([
                'conciliacion_id' => $request->conciliacion_id,
                'pdf_reporte_id' => $request->reporte_id,
                'tipo_firma_id' => 209,
                'firmado' => 1,
                'revocado' => 0,
            ])
            ->get();

        // return response()->json($request->all());

        foreach ($users as $key => $user) {
            Mail::to($user)->send(new RevocarFirma($user));
        }

        return response()->json($users);
    }

    public function getFromReports(Request $request)
    {
        $reporte = PdfReporte::find($request->reporte_id);
        $reporte_data = json_decode($reporte->report_keys);
        $data = [];
        if (count($reporte_data) > 0) {
            foreach ($reporte_data as $key => $value) {
                if ($value->user_type == 'personalized') {
                    $data[] = $value;
                }
            } 
        }

       //return response()->json([$data]);
        $view =  view('myforms.conciliaciones.componentes.form_personalized_values', compact('data', 'reporte'))->render();
        $response = [];
        $response['render_view'] = $view;
        return response()->json($response);
    }

    public function insertPersonalizedReportValues(Request $request)
    {
        //return response()->json($request->all());

        if ($request->has('short_name') and is_array($request->get('short_name'))) {
            foreach ($request->get('short_name') as $key => $short_name) {
                $ref_data = getAditionalDataByShortName($short_name, 'pdf_reportes');
                if ($ref_data) {
                    $old_data = PdfReporteAditionalData::where([
                        'reference_data_id' => $ref_data->id,
                        'reference_data_option_id' => $ref_data->options()->first()->id,
                        'reporte_id' => $request->reporte_id,
                    ])->first();
                    if ($old_data) {
                        if ($request->personalized_value[$key] != null) {
                            $old_data->value = $request->personalized_value[$key];
                            $old_data->save();
                        } else {
                            $old_data->delete();
                        }
                    } else {
                        if ($request->personalized_value[$key] != null) {
                            PdfReporteAditionalData::create([
                                'value' => $request->personalized_value[$key],
                                'reference_data_id' => $ref_data->id,
                                'reference_data_option_id' => $ref_data->options()->first()->id,
                                'reporte_id' => $request->reporte_id,
                            ]);
                        }
                    }
                }
            }
        }

        //$view =  view('myforms.conciliaciones.componentes.form_personalized_values',compact('data','reporte'))->render();
        $response = [];
        // $response['render_view'] = $view;
        return response()->json($response);
    }

    public function revockFirma(Request $request)
    {
        $revock = DB::table('pdf_reportes_users')
            ->where(['id' => $request->pivot_id])
            ->update(['revocado' => 1]);
        return response()->json($revock);
    }
}
