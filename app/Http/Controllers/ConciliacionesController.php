<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conciliacion;
use App\Turno;
use App\File;
use App\TablaReferencia;
use App\User;
use App\ConciliacionAditionalData;
use App\ReferencesData;
use App\ConciliacionComentario;
use App\ConciliacionEstado;
use App\ConciliacionPdfTemporal;
use App\AudienciaConciliacion;
use App\ConciliacionEstadoFileCompartido;
use App\ConciliacionEstadoReporteGenerado;
use App\Expediente;
use App\Jobs\ProcessEmailSendConciliacionResponse;
use App\Jobs\ProcessEmailSendSummernoteNotification;
use App\Mail\RegConciliacionCorregir;
use App\Mail\RegConciliacionSuccess;
use App\Mail\VerifyPdfReportConciliacion;
use App\Notifications\NotificationsSummernote;
use App\Notifications\SolicitudRadicarConciliacion;
use App\PdfReporte;
use App\PdfReporteDestino;
use App\Periodo;
use App\Traits\PdfReport as TraitPdf;
use PDF;
use App\SalasAlternasConciliacion;
use App\Services\ConciliacionComentarioService;
use App\Services\ConciliacionesService;
use App\Services\PeriodosService;
use App\Services\SegmentosService;
use App\Services\TurnosService;
use App\Services\UsersService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ConciliacionesController extends Controller
{
    use TraitPdf;
    private $userService;
    private $turnosService;
    private $conciliacionService;
    public $periodoService;
    public $segmentoService;
    protected $conciliacionComentariosService;


    public function __construct(
        TurnosService $turnosService,
        PeriodosService $periodoService,
        SegmentosService $segmentoService,
        UsersService $userService,
        ConciliacionesService $conciliacionService,
        ConciliacionComentarioService $conciliacionComentariosService
    ) {
        $this->periodoService = $periodoService;
        $this->segmentoService = $segmentoService;
        $this->userService = $userService;
        $this->turnosService = $turnosService;
        $this->conciliacionService = $conciliacionService;
        $this->conciliacionComentariosService = $conciliacionComentariosService;
        //$this->middleware('permission:ver_conciliaciones',   ['only' => ['index']]);
        $this->middleware('auth', ['except' => ['downloadFile']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //if (currentUser()->hasRole("solicitante")) return redirect("/oficina/solicitante");

        $conciliaciones =   $this->conciliacionService->getAllConciliaciones($request); // $reporte = ConciliacionReporte::find(5); 

        // dd($conciliaciones,auth()->user());
        return view('myforms.conciliaciones.index', compact('conciliaciones'));
    }



    public function audiencias(Request $request)
    {
        $conciliaciones = Conciliacion::orderBy(DB::raw("FIELD(estado_id,'182')"), 'desc')->paginate(10);
        return view('myforms.conciliaciones.audiencias', compact('conciliaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $periodo = $this->periodoService->getPeriodoActivo();
            $request['periodo_id'] =  $periodo->id;
            $conciliacion = $this->conciliacionService->store($request);
        } catch (\Throwable $e) {
            $mensajeError = "Ha ocurrido un error: " . $e->getMessage();
            Session::flash('message-warning', $mensajeError);
            return redirect('/conciliaciones/');
        }
        return redirect('/conciliaciones/' . $conciliacion->id . '/edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$conciliacion = Conciliacion::find(10);
         //return response()->json($request->all());
        try {
            $periodo = $this->periodoService->getPeriodoActivo();
            $request['periodo_id'] =  $periodo->id;
            $conciliacion = $this->conciliacionService->store($request);
            if ($request->has('solicitante_id') and currentUser()->hasRole('solicitante')) {
                $conciliacion->usuarios()->attach($request->get('solicitante_id'), [
                    'tipo_usuario_id' => 205,
                    'estado_id' => 1 
                ]);
            }
            $estado = ConciliacionEstado::create([
                'concepto' => "Solicitud primera vez",
                'type_status_id' => $conciliacion->estado_id,
                'user_id' => $request->input('solicitante_id'),
                'conciliacion_id' => $conciliacion->id
            ]);
            if ($request->has('mail_solicitante')) {
                $user = $conciliacion->getUser(205);
                try {
                    Mail::to($user)->send(new RegConciliacionSuccess($conciliacion));
                } catch (\Throwable $th) {
                    return response()->json(
                        [
                            'errors_email' => [$th->getMessage()],
                            'conciliacion' => $conciliacion,

                        ]
                    );
                }
            }
        } catch (\Throwable $e) {
            $mensajeError = "Ha ocurrido un error: " . $e->getMessage();
            Session::flash('message-warning', $mensajeError);
            return response()->json(['errors' => [
                $mensajeError
            ]]);
        }


        return response()->json($conciliacion);
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
    public function edit($id, Request $request)
    {
        $conciliacion = $this->conciliacionService->find($id);
        if (currentUser()->hasRole("solicitante") and ($conciliacion->estado_id != 178)) {
            return redirect("/solicitudes/recepcion/conciliacion/$conciliacion->token?id=$conciliacion->id&paso=2");
        }

        if (!$conciliacion) {
            Session::flash('message-warning', "Ups! No se ha encontrado la conciliacion");
            return redirect("/conciliaciones");
        }
        $cursando = TablaReferencia::where(['categoria' => 'cursando', 'tabla_ref' => 'turnos'])
            ->pluck('ref_nombre', 'id');

        $estudiantes = $this->getEstudiantes();
       // dd($request);

        $turnos = $this->turnosService->index($request);

        //dd($turnos);
        $numusers =  $conciliacion->usuarios->count();
        $audiencia = AudienciaConciliacion::where('id_conciliacion', $conciliacion->id)->first();
        $salaalterna = SalasAlternasConciliacion::where(['idnumber' => Auth::user()->idnumber, "id_conciliacion" => $conciliacion->id])->first();
        $sala_alterna_url = "";
        if ($salaalterna) {
            $sala_alterna_url = $request->root() . "/audiencia" . "/salaalaterna" . "/" . $salaalterna->token_access;
        }
        if (!$audiencia) {  //si no existe lo crea
            $audiencia = "";
        }
        return  view('myforms.conciliaciones.edit', [
            'cursando' => $cursando,
            'turnos' => $turnos,
            'estudiantes' => $estudiantes,
            'conciliacion' => $conciliacion,
            'cont' => '1',
            'audiencia' => $audiencia,
            'numusers' => $numusers,
            'sala_alterna_url' => $sala_alterna_url
        ]);


        // return view('myforms.conciliaciones.edit',compact('conciliacion'));
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

    public function crearActa(Request $request)
    {
        $conciliacion = $this->conciliacionService->find($request->conciliacion_id);
        //  $repo = $this->crearCopiasFormatoEstado($request,$conciliacion);
        $reportes = PdfReporteDestino::whereHas(
            'reporte',
            function (Builder $query) use ($request) {
                $query->where('is_copy', 1);
            }
        )->whereHas(
            'temporales',
            function (Builder $query) use ($request) {
                $query->where("conciliacion_id", $request->conciliacion_id)
                ->where('parent_reporte_pdf_id', $request->reporte_id);
            }
        )->where(([
            "status_id" => $request->type_status_id,
            "tabla_destino" => "226"
        ]))->get();
       
       // return response()->json(['generate'=>false,'rq'=>$request->all(),'rep'=>$conc_estado]);
        if(count($reportes)<=0){
            $data = PdfReporte::where('is_copy', 0)
            ->where('id', $request->reporte_id)->first();
            $repo = $this->crearCopiasFormatoEstado($request,$conciliacion,$data);
            return response()->json(['generate'=>$repo]);
        }
        return response()->json(['generate'=>false]);
    }
    private function crearCopiasFormatoEstado(Request $request, Conciliacion $conciliacion,$reporte)
    {
       /*  $reportes = PdfReporteDestino::whereHas('reporte', function (Builder $query) {
            $query->where('is_copy', 1);
        })->whereHas('temporales', function (Builder $query) use ($request) {
            $query->where("conciliacion_id", $request->conciliacion_id);
        })->where(([
            "status_id" => $request->type_status_id,
            "tabla_destino" => "226"
        ]))->get(); */
       
        /*     $data = PdfReporteDestino::whereHas('reporte', function (Builder $query) {
                $query->where('is_copy', 0);
            })
                ->where([
                    "status_id" => $request->type_status_id,
                    "tabla_destino" => "226"
                ])->get(); */

           // $data->each(function ($data) use ($request, $conciliacion) {
                //  $reporte_or = PdfReporte::find($reporte->id);
                $conc_estado = ConciliacionEstado::where([
                    'type_status_id'=>$request->type_status_id,
                    'conciliacion_id'=>$conciliacion->id
                ])->orderBy('created_at','desc')->first();
                $copy_reporte = PdfReporte::create(
                    [
                        'reporte' => $reporte->reporte,
                        'report_keys' => $reporte->report_keys,
                        'nombre_reporte' => $reporte->nombre_reporte,
                        'configuraciones' => $reporte->configuraciones,
                        'is_copy' => 1,
                        'categoria_id' =>  $reporte->categoria_id
                    ]
                );
                $reporDest = PdfReporteDestino::create([
                    "status_id" => $request->type_status_id,
                    "tabla_destino" => "226",
                    "reporte_id" => $copy_reporte->id
                ]);
                $co_pdf = ConciliacionPdfTemporal::create([
                    'reporte_pdf_id' => $copy_reporte->id,
                    'status_id' => $request->type_status_id,
                    'parent_reporte_pdf_id' => $reporte->id,
                    'conciliacion_id' => $conciliacion->id,
                    'conc_estado_id' => $conc_estado->id
                ]);

                $file_en = $reporte->files()->where('seccion', 'encabezado')->first();
                if ($file_en) {
                    $reporte->files()->attach($file_en, [
                        'seccion' => 'encabezado',
                        'configuracion' => $file_en->pivot->configuracion
                    ]);
                }
                $file_pie = $reporte->files()->where('seccion', 'pie')->first();
                if ($file_pie) {
                    $reporte->files()->attach($file_pie, [
                        'seccion' => 'pie',
                        'configuracion' => $file_pie->pivot->configuracion
                    ]);
                }
           // });
            return true;
        
    }

    public function insertEstado(Request $request)
    {

        $request['user_id'] = Auth::user()->id;
        $estado = ConciliacionEstado::create($request->all());
        $conciliacion = Conciliacion::find($request->conciliacion_id);
        $conciliacion->estado_id = $request->type_status_id;
        if ($request->type_status_id == 181)  $conciliacion->auto_admisorio = intval($conciliacion->auto_admisorio) + 1;

        if ($request->type_status_id == 178) { //radicado
            $periodo = Periodo::where("estado", 1)->first();
            $date_fin = Carbon::parse($periodo->prdfecha_fin)->year;
            $date_ini = Carbon::parse($periodo->prdfecha_inicio)->year;
            $con_ul = Conciliacion::where('periodo_id', $periodo->id)
                ->where('num_conciliacion', '<>', 'CCEAH-000-00-00')
                ->orderBy('created_at', 'desc')->first();
            if ($con_ul == null) {
                $id_num = '001';
                $numero = "CCEAH-" . $id_num . "-" . substr($date_ini, -2) . "-" . substr($date_fin, -2);
            } else {
                $id_num = intval(explode('-', $con_ul->num_conciliacion)[1]) + 1;
                if ($id_num < 10)  $id_num =  '00' . $id_num;
                if ($id_num > 10 and $id_num < 100)  $id_num =  '0' . $id_num;
                $numero = "CCEAH-" . $id_num . "-" . substr($date_ini, -2) . "-" . substr($date_fin, -2);
            }
            $conciliacion->num_conciliacion = $numero;
            $conciliacion->fecha_radicado = date('Y-m-d H:i:s');
        }
        $conciliacion->save();
        //Crea las copias del formatos para el estado
        //$this->crearCopiasFormatoEstado($request, $conciliacion);


        if ($request->has("status_file")) {
            $file = $estado->uploadFile($request->file('status_file'), '/concilacion_' . $conciliacion->id . '/status_' . $estado->id);
            $estado->files()->attach(
                $file,
                ['conciliacion_id' => $conciliacion->id, 'user_id' => currentUser()->id]
            );
        }
        $user_created = currentUser()->name . " " . currentUser()->lastname;
        if ($conciliacion->estado_id == 175) { //Enviado a revision
            if (count($conciliacion->actuaciones) > 0) {
                $actuacion = $conciliacion->actuaciones[0];
                $actuacion->actestado_id = $conciliacion->estado_id;
                $actuacion->save();
                if (isset($file)) {
                    $actuacion->files()->attach($file);
                }
            }
            if ($request->has("send_notification")) {
                $users = $this->userService->getUsersByPermissionName('recibir_correos_conciliacion_r');
                ProcessEmailSendSummernoteNotification::dispatch(
                    $users,
                    $estado->concepto,
                    $conciliacion,
                    "Solicitud de conciliación",
                    $user_created
                )
                    ->onConnection('database')->onQueue('emails');;
                /*   Notification::send($users, new NotificationsSummernote(
                        $estado->concepto,
                        $conciliacion,
                        "Revision por favor",
                        $user_created
                    )); */

                //Notification::send($users, new SolicitudRadicarConciliacion($estado,$user_created));
            }
        }

        if ($conciliacion->estado_id == 225) { //solicitud de radicado

            //if($conciliacion->fecha_radicado=='0000-00-00')
            //$conciliacion->fecha_radicado = date('Y-m-d H:i:s');
            $conciliacion->save();
            $users = $this->userService->getUsersByPermissionName('recibir_correos_conciliacion_r');
            ProcessEmailSendSummernoteNotification::dispatch(
                $users,
                $request->cuerpo_correo,
                $conciliacion,
                $request->asunto,
                $user_created
            )
                ->onConnection('database')->onQueue('emails');;
            //Notification::send($users, new SolicitudRadicarConciliacion($estado,$user_created));
        }

        if ($conciliacion->estado_id == 176) { //corregir

            $users = $conciliacion->getUser(205);
            if ($users->id != null and $conciliacion->categoria_id == 219) {
                ProcessEmailSendConciliacionResponse::dispatch(
                    $users,
                    $estado->concepto,
                    $conciliacion,
                    "Solicitud de correcciones",
                    $user_created

                )
                    ->onConnection('database')->onQueue('emails');;
                /* Notification::send($users, new NotificationsSummernote(
                        $estado->concepto,
                        $conciliacion,
                        "Solicitud de correcciones",
                        $user_created
                    )); */
                // Mail::to($user)->send(new SolicitudRadicarConciliacion($estado,$user_created ));
            }
        }

        $view = view('myforms.conciliaciones.componentes.conciliacion_estados_ajax', compact('conciliacion'))->render();
        return response()->json([
            'view' => $view
        ]);
        return response()->json($request->all());
    }


    public function insertComentario(Request $request)
    {

        $request['user_id'] = Auth::user()->id;
        $comentario = ConciliacionComentario::create($request->all());
        $conciliacion = Conciliacion::find($request->conciliacion_id);
        $view = view('myforms.conciliaciones.componentes.solicitud_comentarios_ajax', compact('conciliacion'))->render();
        return response()->json([
            'view' => $view
        ]);
    }

    public function getComentarios(Request $request)
    {
        $conciliacion = Conciliacion::find($request->conciliacion_id);
        $view = view('myforms.conciliaciones.componentes.solicitud_comentarios_ajax', compact('conciliacion'))->render();
        return response()->json([
            'view' => $view
        ]);
    }
    public function getFilesByCategory(Request $request)
    {
        $conciliacion = Conciliacion::find($request->conciliacion_id);
        $files = $conciliacion->files()->where([
            'category_id' => $request->category_id
        ])->get();
        //$view = view('myforms.conciliaciones.componentes.solicitud_comentarios_ajax', compact('conciliacion'))->render();
        return response()->json([
            'files' => $files
        ], 200);
    }

    public function deleteComentario(Request $request)
    {
        $comentario = ConciliacionComentario::find($request->comentario_id)->delete();
        $conciliacion = Conciliacion::find($request->conciliacion_id);
        $view = view('myforms.conciliaciones.componentes.solicitud_comentarios_ajax', compact('conciliacion'))->render();
        return response()->json([
            'view' => $view
        ]);
    }

    public function deleteEstado(Request $request)
    {
        $comentario = ConciliacionEstado::find($request->estado_id)->delete();
        $conciliacion = Conciliacion::find($request->conciliacion_id);
        $view = view('myforms.conciliaciones.componentes.solicitud_estados_ajax', compact('conciliacion'))->render();
        return response()->json([
            'view' => $view
        ]);
    }

    public function editComentario(Request $request)
    {
        $comentario = ConciliacionComentario::find($request->comentario_id);
        return response()->json($comentario);
    }

    public function editEstado(Request $request)
    {
        $estado = ConciliacionEstado::find($request->estado_id);
        $estado->type_status;
        return response()->json($estado);
    }

    public function updateComentario(Request $request)
    {

        $comentario = ConciliacionComentario::find($request->comentario_id);
        $comentario->fill($request->all());
        $comentario->save();
        $conciliacion = Conciliacion::find($request->conciliacion_id);
        $view = view('myforms.conciliaciones.componentes.solicitud_comentarios_ajax', compact('conciliacion'))->render();
        return response()->json([
            'view' => $view
        ]);
    }

    public function updateEstado(Request $request)
    {
        $comentario = ConciliacionEstado::find($request->estado_id);
        $comentario->fill($request->all());
        $comentario->save();
        $conciliacion = Conciliacion::find($request->conciliacion_id);

        $view = view('myforms.conciliaciones.componentes.conciliacion_estados_ajax', compact('conciliacion'))->render();
        return response()->json([
            'view' => $view
        ]);
    }

    private function storeData($ref_data, $request)
    {

        $data = ConciliacionAditionalData::where([
            'reference_data_id' => $ref_data->id,
            'conciliacion_id' => $request['conciliacion_id']
        ])->first();
        if ($data) {
            $data->fill([
                'value' => $request["value"],
                'reference_data_option_id' => $request["option_id"],
                'value_is_other' => isset($request["value_is_other"]) != null ? $request["value_is_other"] : null,
            ]);
            $data->save();
        } else {
            $data = ConciliacionAditionalData::create([
                'reference_data_id' => $ref_data->id,
                'reference_data_option_id' => $request["option_id"],
                'conciliacion_id' => $request["conciliacion_id"],
                'value' => $request["value"],
                'value_is_other' => isset($request["value_is_other"]) != null ? $request["value_is_other"] : null,
            ]);
        }
    }

    public function insertData(Request $request)
    {

        if ($request->has('data') and is_array($request->data)) {
            foreach ($request->data as $key => $rq) {
                $rq['conciliacion_id'] = $request->conciliacion_id;
                $ref_data = ReferencesData::where(['name' => $rq['name'], 'section' => $rq['section']])->first();
                if ($ref_data) {
                    $this->storeData($ref_data, $rq);
                }
            }
        } else {
            $ref_data = ReferencesData::where(['name' => $request['name'], 'section' => $request['section']])->first();

            $this->storeData($ref_data, $request);
        }


        $conciliacion = Conciliacion::find($request->conciliacion_id);

        return response()->json($conciliacion);
        if ($ref_data) {
        } else {
            return response()->json(['error' => 'El atributo no existe']);
        }
        return response()->json($request->all());
    }

    public function storeAnexo(Request $request)
    {
        //return response()->json($request->all());
        $conciliacion = Conciliacion::find($request->conciliacion_id);
        if ($request->file('conciliacion_file') != '') {
            $file = $conciliacion->uploadFile($request->file('conciliacion_file'), '/conciliacion_' . $conciliacion->id);
            $conciliacion->files()->attach($file, [
                'type_status_id' => 1,
                'concepto' => $request->concept,
                'user_id' => auth()->user()->id,
                "category_id" => $request->category_id,
            ]);
        }
        $category = $request->category_id;
        $conciliacion = Conciliacion::find($request->conciliacion_id);
        $view = view("myforms.conciliaciones.componentes.".$request->view_template, compact("conciliacion", 'category'))->render();
        return response()->json([
            'view' => $view
        ]);
    }

    public function deleteAnexo(Request $request)
    {
        //return response()->json($request->all());
        $conciliacion = Conciliacion::find($request->conciliacion_id);

        $file = $conciliacion->files()->where('file_id', $request->file_id)
            ->where(function ($query) {
                if (!auth()->user()->can("act_conciliacion")) {
                    $query->where('user_id', auth()->user()->id);
                }
            })
            ->first();
        if ($file) {
            $file = File::find($request->file_id);
            if ($file->path != '') {
                Storage::delete("conciliacion_files/conciliacion_" . $request->conciliacion_id . "/" . $file->encrypt_name);
                $file->delete();
            }
            $conciliacion = Conciliacion::find($request->conciliacion_id);
            $category  = $request->category_id;
            $view = view("myforms.conciliaciones.componentes.anexos_ajax", compact("conciliacion", "category"))->render();
            return response()->json([
                'view' => $view
            ]);
        }

        return response()->json([
            'error' => "A ocurrido un error de servidor"
        ], 404);
    }


    public function updateAnexo(Request $request)
    {

        //return response()->json($request->all());
        $conciliacion = Conciliacion::find($request->conciliacion_id);

        $file = $conciliacion->files()->where('file_id', $request->file_id)
            ->where(function ($query) {
                if (!auth()->user()->can("act_conciliacion")) {
                    $query->where('user_id', auth()->user()->id);
                }
            })
            ->first();
        if ($file) {
            $file = File::find($request->file_id);
            if ($request->file('conciliacion_file') != '') {
                if ($file->path != '') {
                    Storage::delete("conciliacion_files/conciliacion_" . $request->conciliacion_id . "/" . $file->encrypt_name);
                    //return response()->json("conciliacion_files/conciliacion_".$request->conciliacion_id."/".$file->encrypt_name);

                    $file->delete();
                }
                $file = $conciliacion->uploadFile($request->file('conciliacion_file'), '/conciliacion_' . $conciliacion->id);
                $conciliacion->files()->attach($file, [
                    'type_status_id' => 1,
                    'concepto' => $request->concept,
                    'user_id' => auth()->user()->id
                ]);
            } else {
                $file = $conciliacion->files()->where('file_id', $request->file_id)->first();
                $file->pivot->concepto = $request->concept;
                $file->pivot->save();
            }
            $conciliacion = Conciliacion::find($request->conciliacion_id);
            $view = view("myforms.conciliaciones.componentes.anexos_ajax", compact("conciliacion"))->render();
            return response()->json([
                'view' => $view
            ]);
        }

        return response()->json([
            'error' => "A ocurrido un error de servidor"
        ], 404);
    }


    public function downloadFile($file_id)
    {
        $id = 100;
        if (auth()->user()) $id = auth()->user()->id;

        array_map('unlink', glob(public_path('act_temp/' . $id . '___*'))); //elimina los archivos que el 
        $file = File::find($file_id);
        if ($file) {
            try {
                $rutaDeArchivo = storage_path($file->path);
                $filename = $id . '___' . $file->original_name;
                copy($rutaDeArchivo, public_path("act_temp/" . $filename));
                return redirect("act_temp/" . $filename);
            } catch (\Throwable $th) {
                echo "<h3>El archivo que buscas ya no existe!</h3><small><i>Amatai&copy" . date("Y") . "</i></small><br>$th";
            }
        }
    }

    public function getEstadosReportesPdf(Request $request)
    {
        $estado = ConciliacionEstado::find($request->estado_id);
        $estado->files = $estado->files()
            ->where('conciliacion_id', $request->conciliacion_id)
            ->get();
        return response()->json($estado);
    }

    public function getEstadosFilesByCategory(Request $request)
    {
        /* $estado = ConciliacionEstado::find($request->conc_estado_id);
        $estado->files = $estado->files()
            ->where('conciliacion_id', $request->conciliacion_id)
            ->get(); */
        $conciliacion = Conciliacion::find($request->conciliacion_id);
        $partes = $conciliacion->usuarios;
       /*  $compartidos = ConciliacionEstadoFileCompartido::where([
            'conciliacion_id' => $conciliacion->id,
            'status_id' =>   $request->status_id
        ])->get();
 */
        $view = view('myforms.conciliaciones.componentes.conciliacion_estados_files_ajax', compact('estado'))->render();
        $view_compartidos = view('myforms.conciliaciones.componentes.files_conciliacion_compartidos_ajax', compact('compartidos'))->render();

        $response = [
            "view_compartidos" => $view_compartidos,
            "compartidos" => $compartidos,
            "partes" => $partes,
            "estado" => $estado,
            "view" => $view
        ];
        return response()->json($response);
    }
    
    public function getEstadosFiles(Request $request)
    {
        /* $estado = ConciliacionEstado::find($request->conc_estado_id);
        $estado->files = $estado->files()
            ->where('conciliacion_id', $request->conciliacion_id)
            ->get(); */
        $conciliacion = Conciliacion::find($request->conciliacion_id);
        $partes = $conciliacion->usuarios;
        $conciliacion->files = $conciliacion->files()
            ->where('file_id', $request->conc_file_id)
            ->get();
         $compartidos = ConciliacionEstadoFileCompartido::whereHas('files',function($query) use ($request){
            $query->where("file_id",$request->conc_file_id);
         })->where([
            'conciliacion_id' => $conciliacion->id,
            'status_id' =>   $request->status_id
        ])->get(); 

        $view = view('myforms.conciliaciones.componentes.conciliacion_estados_files_ajax', compact('conciliacion'))->render();
        $view_compartidos = view('myforms.conciliaciones.componentes.files_conciliacion_compartidos_ajax', compact('compartidos'))->render();
 
        $response = [
            "view_compartidos" => $view_compartidos,
            "compartidos" => $compartidos, 
            "partes" => $partes,
             "conciliacion" => $conciliacion,
            "view" => $view 
        ];
        return response()->json($response);
    }

    public function getUser(Request $request, $idnumber)
    {
        try {
            $user = $this->userService->setValidateSede(false)
                ->findWithFilter([
                    'tipodoc_id' => $request->tipodoc_id,
                    'idnumber' => $request->idnumber
                ]);
            $conciliacion = $this->conciliacionService->find($request->conciliacion_id);
            if ($user) {
                $user->roles;
                $view = view('myforms.conciliaciones.componentes.user_solicitante_form', compact('conciliacion', 'user'))->render();
                if ($request->has("view")) {
                    $view = view('myforms.conciliaciones.componentes.' . $request->get("view"), compact('conciliacion', 'user'))->render();
                }
                return response()->json(['encontrado' => true, 'user' => $user, 'view' => $view]);
            }
            $view = view('myforms.conciliaciones.componentes.user_solicitante_form', compact('conciliacion'))->render();
        } catch (\Throwable $th) {
            return  response()->json(['encontrado' => false, 'errors' => $th->getMessage()]);
        }
    }
    public function getDetallesUser(Request $request, $idnumber)
    {
        // return $request->all(); 
        $user = $this->userService->setValidateSede(false)
            ->findWithFilter([
                'idnumber' => $request->idnumber
            ]);

        if ($user) {
            $conciliacion = Conciliacion::find($request->conciliacion_id);
            $user->roles;
            $view = view('myforms.conciliaciones.componentes.user_detalles_form', compact('user', 'conciliacion'))->render();
            return response()->json(['encontrado' => true, 'user' => $user, 'view' => $view]);
        }
        return  response()->json(['encontrado' => false]);
    }

    public function deleteUser(Request $request)
    {
        $user = DB::table('conciliacion_has_user')
            ->where('id', $request->pivot)->delete();
        return response()->json(['user' => $user]);
    }

    public function addUser(Request $request)
    {
        $conciliacion = $this->conciliacionService->find($request->conciliacion_id);

        //return response()->json($request->all(), 200);

        try { 
            if($request->has('id') and $request->input("id")!=''){
                $request["user_id"] = $request->input("id");
                $conciliacion = $this->conciliacionService->addUser($conciliacion, $request);
                $user = $this->userService->setValidateSede(false)->find($request->user_id);
             }if($request->has('user_id') and $request->input("user_id")!=''){
                $request["user_id"] = $request->input("user_id");
                $conciliacion = $this->conciliacionService->addUser($conciliacion, $request);
                $user = $this->userService->setValidateSede(false)->find($request->user_id);
       
             }else{
                $user = $this->userService->store($request);
                $request["user_id"] = $user->id;
                $conciliacion = $this->conciliacionService->addUser($conciliacion, $request);
                           
            }
            $this->userService->addSede($user);
            $conciliacion->usuarios;
            return response()->json($conciliacion, 200);
        } catch (\Throwable $th) {
            return response()->json([$th->getMessage()], 404);
        }
    }

    public function sancionarUser(Request $request)
    {
        $user = DB::table('conciliacion_has_user')
            ->where('id', $request->pivot)->update([
                'estado_id' => $request->estado_id
            ]);
        return response()->json([
            'user' => $user
        ]);
    }

    public function getEstudiantes()
    {

        $users = DB::table('users')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftjoin('turnos', 'turnos.trnid_estudent', '=', 'users.idnumber')
            ->leftjoin('referencias_tablas', 'referencias_tablas.id', '=', 'users.cursando_id')
            ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
            ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
            ->where('turnos.trnid_estudent', '=', null)
            ->where('role_id', '6')
            ->where('users.active', true)
            ->where('sedes.id_sede', session('sede')->id_sede)
            ->select(
                'referencias_tablas.ref_nombre as cursando',
                'users.active',
                'users.id',
                'users.idnumber',
                DB::raw('CONCAT(users.name," ",users.lastname) as full_name'),
                'role_user.role_id',
                'roles.display_name'
            )->orderBy('users.created_at', 'desc')->get();

        return ($users);
    }

    public function generateDocuments(Request $request)
    {


        $conciliacion = Conciliacion::find($request->conciliacion_id);
        $estado = ConciliacionEstado::find($request->conc_estado_id);
        //return response()->json(['req' => $request->all(), 'es' => $estado]);
        if ($request->status_id != '0') {
            $pdfs =  PdfReporteDestino::with('reporte')
                ->where('status_id', $request->status_id)
                ->where('reporte_id', $request->reporte_id)
                ->get();
            foreach ($pdfs as $key_1 => $pdf_repor) {
                //obtengo el pdf temporal            
                $reporte_t = ConciliacionPdfTemporal::where('reporte_pdf_id',
                 $pdf_repor->reporte_id)
                    ->first();
                if ($reporte_t) {
                    $reporte =     $reporte_t->reporte_child;
                    $bodytag = $this->getBody($reporte, $conciliacion);
                    // $reporte->delete();
                    $name = $reporte->nombre_reporte;
                    $config = json_decode($reporte->configuraciones);
                } else {
                    $bodytag = $this->getBody($pdf_repor->reporte, $conciliacion);
                    $name = $pdf_repor->reporte->nombre_reporte;
                    $config = json_decode($pdf_repor->reporte->configuraciones);
                }
                if ($pdf_repor) {
                    $file_pie = $pdf_repor->reporte->files()->where('seccion', 'pie')->first();
                    $pie_conf = $file_pie != null ? json_decode($file_pie->pivot->configuracion) : null;
                    $file_enc = $pdf_repor->reporte->files()->where('seccion', 'encabezado')->first();
                    $encab_conf = $file_enc != null ? json_decode($file_enc->pivot->configuracion) : null;
                }

                $users = $pdf_repor->users()
                    ->where('conciliacion_id', $conciliacion->id)
                    ->where('firmado', 1)
                    ->where('revocado', 0)
                    ->get();

                $pdf = PDF::loadView(
                    'pdf.conciliacion',
                    [
                        'is_preview' => false,
                        'pdf' => $bodytag,
                        'margin' => $config->margin_string,
                        'pie' => $file_pie,
                        'pie_conf' => $pie_conf,
                        'encabezado' => $file_enc,
                        'encab_conf' => $encab_conf,
                        'users' => $users,

                    ]
                )
                    ->setPaper($config->tipo_papel);
                $path = storage_path('app/conciliaciones_pdf');
                $fileName =  time() . '_' . md5($name) . '.' . 'pdf';
                $pdf->save($path . '/' . $fileName);
                /*  copy($path . '/' . $fileName, public_path("pdf_temp/".$fileName));
            return response()->json([
                'url'=>'/pdf_temp' . '/' . $fileName,
                'user'=>$users
            ]); */
                $hash = hash_hmac_file("sha256", $path . '/' . $fileName, 'secret');
                $path =   'app/conciliaciones_pdf';
                $file = new \App\File();
                $file->original_name = $name;
                $file->encrypt_name = $fileName;
                $file->path = $path . '/' . $fileName;
                $file->size = '0000';
                $file->hash = $hash;
                $file->save();
               /*  $estado->files()->attach($file, [
                    'conciliacion_id' => $conciliacion->id,
                    'user_id'=>currentUser()->id,
                    'category_id'=>212
                ]); */
                $conciliacion->files()->attach($file, [
                    'type_status_id' => 1,
                    'concepto' => $name,
                    'user_id' => auth()->user()->id,
                    "category_id" => 212,
                ]);

                $verification_token = str_replace("/", "", bcrypt(\Str::random(50)));
                $clave = \Str::random(6);
                $Rgenerate = ConciliacionEstadoReporteGenerado::create([
                    'fecha_exp_token' => Carbon::now()->addDay(),
                    'conciliacion_id' => $conciliacion->id,
                    'status_id' => $request->status_id,
                    'reporte_id' => $pdf_repor->reporte_id,
                ]);
                $generate = ConciliacionEstadoFileCompartido::create(
                    [
                        'token' => $verification_token,
                        'fecha_exp_token' => Carbon::now()->addDay(),
                        'conciliacion_id' => $conciliacion->id,
                        'status_id' => $request->status_id,
                        'category_id' => 214,
                        'means_id' => 218,
                        'clave' => $clave
                    ]
                );

                if (count($users) > 0) {
                    $generate->files()->attach($file->id);
                    foreach ($users as $key => $user) {
                        $generate->is_user = true;
                        Mail::to($user)->send(new VerifyPdfReportConciliacion($generate));
                    }
                }

                // $pdf_repor = PdfReporte::find($request->reporte_id)->delete();

            }

            return response()->json(['user' => $users]);
        }
    }

    public function storeSharedConcFiles(Request $request)
    {

        //return response()->json($request->all());
        try {
            $verification_token = str_replace("/", "", bcrypt(\Str::random(50)));
            $clave = \Str::random(6);
            $generate = ConciliacionEstadoFileCompartido::create(
                [
                    'token' => $verification_token,
                    'fecha_exp_token' => Carbon::now()->addDay(),
                    'conciliacion_id' => $request->conciliacion_id,
                    'status_id' => $request->status_id,
                    'category_id' => $request->category_id,
                    'means_id' => $request->means_id,
                    'clave' => $clave
                ]
            );
            foreach ($request->compartir_id as $key => $file_id) {
                $generate->files()->attach($file_id);
            }
            $response = [];
            if ($generate->means_id == 218) {
                foreach ($request->shared_mail as $key => $mail) {
                    $generate->is_user = false;
                    Mail::to($mail)->send(new VerifyPdfReportConciliacion($generate));
                }
                $url = false;
            } else {
                $url = url("/firmar/pdf/verify/$generate->token");
            }

            $compartidos = ConciliacionEstadoFileCompartido::where([
                'conciliacion_id' => $request->conciliacion_id,
                'status_id' =>   $request->status_id
            ])->get();

            $view_compartidos = view('myforms.conciliaciones.componentes.files_conciliacion_compartidos_ajax', compact('compartidos'))->render();

            $response = [
                'generate' => $generate,
                'url' => $url,
                'view_compartidos' => $view_compartidos
            ];

            return response()->json($response);
        } catch (\Throwable $th) {
            return response()->json([
                'th' => $th
            ]);
        }
    }

    public function asigExpediente(Request $request)
    {

        //return response()->json($request->all());
        try {
            $expediente = Expediente::where('expid', $request->expid)->first();
            if ($expediente) {
                if (count($expediente->conciliaciones) <= 0) {
                    $expediente->conciliaciones()->attach($request->conciliacion_id, [
                        'type_status_id' => 1,
                        'user_id' => auth()->user()->id
                    ]);
                } else {
                    return response()->json([
                        "mensaje" => "El expediente ya tiene asignada una conciliación"
                    ]);
                }
            }
            return response()->json([
                "mensaje" => "No existe el expediente"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'th' => $th
            ]);
        }
    }

    public function prueba(Request $request)
    {

        $us = $this->userService->getUsersByPermissionName("recibir_correos_conciliacion_r");
        dd($us);
        $us = json_decode($us);
        return response()->json($us);
        $mensaje =  ConciliacionEstado::find(153);
        // Notification::send($us, new SolicitudRadicarConciliacion($mensaje,$user_created));
    }

    public function enviarCorreo(Request $request)
    {
        // return response()->json($request->all()); 

        if ($request->has('correo_send')) {
            $users = User::whereIn('email', $request->correo_send)->get();
        } else {
            $users = $this->userService->getUsersByPermissionName('recibir_correos_conciliacion_r');
        }
        $comentario = $this->conciliacionComentariosService->store($request);
        $conciliacion = Conciliacion::find($request->conciliacion_id);
        $user_created = currentUser()->name . " " . currentUser()->lastname;
        if ($request->has('pivot_id') and $request->get('pivot_id') != '' and $request->get('pivot_id') != null) {
            $update = $conciliacion->usuarios()
                ->where('conciliacion_has_user.id', $request->pivot_id)->first();
            $update->pivot->estado_id = $request->user_estado_id;
            $update->pivot->save();
        }
        ProcessEmailSendSummernoteNotification::dispatch(
            $users,
            $request->cuerpo_correo,
            $conciliacion,
            $request->asunto,
            $user_created
        )
            ->onConnection('database')->onQueue('emails');;
       // Notification::send($users, new NotificationsSummernote( $request->cuerpo_correo,$conciliacion,$request->asunto,$user_created ));

        return response()->json([$users, $request->has('correo_send')]);
    }
}
