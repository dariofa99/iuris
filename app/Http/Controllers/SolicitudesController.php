<?php

namespace App\Http\Controllers;

use App\Conciliacion;
use App\ConciliacionEstado;
use App\Events\RecepcionDocumentSolicitudEvent;
use App\Events\RecepcionStoreEvent;
use App\Events\SolicitudEstadosCambio;
use App\Expediente;
use Illuminate\Http\Request;
use App\Solicitud;
use App\User;
use App\Sede;
use DB;
use Carbon\Carbon;
use Storage;
use Facades\App\Facades\NewPush;
use App\File;
use App\Http\Requests\UserStoreRequest;
use App\Mail\RegConciliacionSuccess;
use App\Mail\RegSolicitudExp;
use App\Services\ConciliacionesService;
use App\Services\PeriodosService;
use App\Services\SedesService;
use App\Services\SolicitudesService;
use App\Services\UsersService;
use Facades\App\Facades\NewChat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SolicitudesController extends Controller
{
    private $solicitudesService;
    private $sedesService;
    private $userService;
    private $conciliacionService;
    public $periodoService;

    public function __construct(
        UsersService $userService,
        SolicitudesService $solicitudesService,
        SedesService $sedesService,
        PeriodosService  $periodoService,
        ConciliacionesService $conciliacionService
    ) {
        $this->periodoService = $periodoService;
        $this->conciliacionService  = $conciliacionService;
        $this->userService = $userService;
        $this->solicitudesService  = $solicitudesService;
        $this->sedesService  = $sedesService;
        $sede = Sede::find(1);
        session(["sede" => $sede]);
        $this->middleware('auth', ['except' => ['registro', 'solicitar_store', 'store', 'waitRoom', 'userRegister', 'update', 'find', 'recepcion', 'solicitar', 'recepcion_conciliacion', 'recepcion_expediente', 'solicitarExpediente', 'storeExpediente_', 'buscarSolicitud', 'solicitarExpedienteStore']]);
        $this->middleware('permission:ver_solicitudes',   ['only' => ['index', 'edita']]);
        $this->middleware('permission:admin_solicitudes',   ['only' => ['edit']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $solicitudesh = $this->get_solicitudesh();
        $solicitudes = $this->get_solicitudes();
        if ($request->ajax()) {
            return view('myforms.solicitudes.frm_list_solicitudesh_ajax', compact('solicitudesh'))->render();
        }
        return view('myforms.solicitudes.frm_list_solicitudes', compact('solicitudes', 'solicitudesh'));
    }

    public function solicitar(Request $request)
    {
        // $user = User::find(20351)  ;
        // Session::forget('sede');
        return view('myforms.recepcion.solicitar_conciliacion');
    }

    public function solicitarExpediente(Request $request)
    {
        // $user = User::find(20351)  ;
        // Session::forget('sede');
        return view('myforms.recepcion.solicitar_expediente');
    }

    public function recepcion_conciliacion(Request $request, $token)
    {


        $conciliacion = Conciliacion::where("token", $token)->first();


        if ($conciliacion and ($conciliacion->estado_id == 240 || $conciliacion->estado_id == 176)) {
            if ($conciliacion and $request->paso != 1) {

                if ($request->paso == 2) {
                    $user = $conciliacion->getUser(205); //solicitante
                    Auth::login($user);
                    //natural
                    if ($user->tipopers_id != 238) {

                        return redirect("/solicitudes/recepcion/conciliacion/$conciliacion->token?id=$conciliacion->id&paso=3");
                    }
                }
                if ($request->paso > 2) {
                    $user = $conciliacion->getUser(205); //solicitante                    
                    if (Auth::guest() and Auth::user() and Auth::user()->id != $user->id) Auth::logout();
                }

                if (Auth::guest()) return redirect('login');
                if ($request->paso >= 3) {
                    $user = $conciliacion->getUser(205); //solicitante
                    //juridico
                    if ($user->tipopers_id == 238) {
                        $user = $conciliacion->getUser(195); //rep legal solicitante
                        if ($user->id == null) {
                            return redirect("/solicitudes/recepcion/conciliacion/$conciliacion->token?id=$conciliacion->id&paso=2");
                        }
                    }
                }
                if ($request->paso == 6) {
                    $user = $conciliacion->getUser(197); //solicitado                   
                    //natural
                    if ($user->tipopers_id != 238) {
                        return redirect("/solicitudes/recepcion/conciliacion/$conciliacion->token?id=$conciliacion->id&paso=7");
                    }
                }
                return view('myforms.recepcion.solicitar_conciliacion', compact('conciliacion'));
            }
        } else {
            Session::flash('message-danger', 'La solicitud ya fue enviada a revisión, pronto nos comunicaremos contigo.');
            return view("myforms.recepcion.frm_mensaje_estado_conciliacion");
            return redirect("/solicitudes/conciliacion/recepcion?paso=1");
        }

        return abort(404);
    }

    public function registro(Request $request)
    {
        $conciliacion = Conciliacion::first();
        return view('myforms.recepcion.frm_registro_conciliacion', compact('conciliacion'));
    }

    public function recepcion_expediente(Request $request, $token)
    {

        // dd($request->all()) ;     
        $solicitud = Solicitud::where("token", $token)->first();
        if ($request->paso == 2) {
            if ($solicitud->type_status_id == 171)
                return redirect("/solicitudes/recepcion/expedientes/$solicitud->token?paso=3");
        }
        if ($request->paso <= 3) {
            if ($solicitud->type_status_id == 165) {
                if (Auth::guest()) {
                   return redirect("/solicitudes/expedientes/recepcion?paso=1");
                }
                $user = $solicitud->user;
                return redirect("/solicitudes/recepcion/expedientes/$solicitud->token?paso=4");
            }
        }
        if ($request->paso > 3) {
            if (Auth::guest()) return redirect("/solicitudes/expedientes/recepcion?paso=1");
            if ($solicitud->type_status_id == 171)
                return redirect("/solicitudes/recepcion/expedientes/$solicitud->token?paso=3");
        }
        $request['token'] = $token;

        return view('myforms.recepcion.solicitar_expediente', compact('solicitud'));
        dd($solicitud);
    }
    public function solicitarExpedienteStore(Request $request)
    {
        //  return response()->json("Awee");//
        $messages = [
            'name.required' => 'El nombre es requerido.',
            'lastname.required' => 'El apellido es requerido.',
            'email.unique' => 'El :attribute  ya existe en otra cuenta.',
            'email.required' => 'El :attribute es requerido.',
            'idnumber.required' => 'El número de documento es requerido.',
            'idnumber.unique' => 'El número de documento ya existe en otra cuenta.',
        ];
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'lastname' => ['required'],
            'email' => ['required', 'unique:users'],
            'idnumber' => ['required', 'unique:users']
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $this->sedesService->setSede($request);
        //$user = $this->userService->store($request);
        //if(Auth::guest()) Auth::login($user);
        $turno  = $this->solicitudesService->getTurno();
        $request['turno'] = $turno;
        $solicitud = $this->solicitudesService->store($request);
        $response = [];
        try {
            Mail::to($solicitud->email)->send(new RegSolicitudExp($solicitud));
        } catch (\Throwable $th) {
            $response['errors'] = [$th->getMessage()];
        }
        $response['solicitud'] = $solicitud;
        $solicitudes = $this->get_solicitudes();
        $render = view('myforms.solicitudes.frm_list_solicitudes_ajax', compact('solicitudes'))->render();
        //$response["view"] = $render;
        event(new RecepcionStoreEvent($render));

        return response()->json($response); // dd($request->all());

        $solicitud = Solicitud::create($request->all());
        $solicitud->number = $request->idnumber . '' . $turno;
        $solicitud->save();


        if ($request->has('sede_id')) {
            $solicitud->sedes()->attach($request->sede_id);
        }
        $solicitudes = $this->get_solicitudes();
        $render = view('myforms.solicitudes.frm_list_solicitudes_ajax', compact('solicitudes'))->render();
        NewPush::channel('solicitudes_coord')
            ->message(['data' => 'mensaje', 'render' => $render])
            ->publish();
        return  redirect()->action('SolicitudesController@waitRoom', $solicitud->token);




        // dd($request->all()) ;     
        return redirect('/solicitudes/conciliacion/registro');
    }

    private function get_solicitudes()
    {
        $solicitudes = Solicitud::where('type_status_id', 154)
            ->join('sede_solicitudes', 'sede_solicitudes.solicitud_id', '=', 'solicitudes.id')
            ->where("sede_id", session('sede')->id_sede)
            ->orderBy("solicitudes.created_at", 'asc')
            ->paginate(300);
        return $solicitudes;
    }

    private function get_solicitudesh()
    {
        $solicitudes = Solicitud::where('type_status_id', '<>', 154)
            ->join('sede_solicitudes', 'sede_solicitudes.solicitud_id', '=', 'solicitudes.id')
            ->where("sede_id", session('sede')->id_sede)
            ->orderBy("solicitudes.created_at", 'desc')
            ->orderBy(DB::raw("FIELD(type_status_id,'155')"), 'desc')
            ->paginate(50);

        return $solicitudes;
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

    public function buscarSolicitud(Request $request)
    {
        $solicitud = Solicitud::where('number', $request->codigo_solicitud)->first();

        if ($solicitud) {
            if ($solicitud->type_status_id == 165) {
                $user = $this->userService->findWithFilter([
                    'idnumber' => $solicitud->idnumber
                ]);
                if ($user) {
                    Auth::login($user);
                }
            }
        } //session("validateTokenSolicitud","activo");


        return response()->json($solicitud);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        //  return response()->json($request->all());//

        $this->sedesService->setSede($request);
        $user = $this->userService->store($request);
        if (Auth::guest()) Auth::login($user);
        $turno  = $this->solicitudesService->getTurno();
        $request['turno'] = $turno;
        $solicitud = $this->solicitudesService->store($request);
        $periodo = $this->periodoService->getPeriodoActivo();
        $request['periodo_id'] =  $periodo->id;
        $request['solicitante_id'] =  $user->id;
        $request['estado_id'] =  240;
        $request['categoria_id'] =  219;

        $conciliacion = $this->conciliacionService->store($request);
        $conciliacion->usuarios()->attach($user->id, [
            'tipo_usuario_id' => 205,
            'estado_id' => 1
        ]);
        $solicitud->conciliaciones()->attach($conciliacion->id);
        $estado = ConciliacionEstado::create([
            'concepto' => "Solicitud primera vez",
            'type_status_id' => $conciliacion->estado_id,
            'user_id' => $request->input('solicitante_id'),
            'conciliacion_id' => $conciliacion->id
        ]);
        $response = [];
        try {
            Mail::to($user)->send(new RegConciliacionSuccess($conciliacion));
        } catch (\Throwable $th) {
            $response['errors'] = [$th->getMessage()];
        }


        $response['conciliacion'] = $conciliacion;
        return response()->json($response); // dd($request->all());

        $solicitud = Solicitud::create($request->all());
        $solicitud->number = $request->idnumber . '' . $turno;
        $solicitud->save();


        if ($request->has('sede_id')) {
            $solicitud->sedes()->attach($request->sede_id);
        }
        $solicitudes = $this->get_solicitudes();
        $render = view('myforms.solicitudes.frm_list_solicitudes_ajax', compact('solicitudes'))->render();
        NewPush::channel('solicitudes_coord')
            ->message(['data' => 'mensaje', 'render' => $render])
            ->publish();
        return  redirect()->action('SolicitudesController@waitRoom', $solicitud->token);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $solicitud = Solicitud::find($id);
            $tur_aten =  Solicitud::join('sede_solicitudes', 'sede_solicitudes.solicitud_id', '=', 'solicitudes.id')
                ->where("sede_id", session('sede')->id_sede)
                ->whereIn('type_status_id', [155, 156])
                ->whereDate('solicitudes.created_at', date('Y-m-d'))
                ->orderBy("turno", 'desc')->first();
            $user = User::where(['idnumber' => $solicitud->idnumber, 'tipodoc_id' => $solicitud->tipodoc_id])->first();
            return view('myforms.recepcion.frm_solicitud_espera', compact('solicitud', 'tur_aten', 'user'));
        } catch (\Throwable $th) {
            echo "Pailas";
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $solicitud = Solicitud::find($id);
        if ($solicitud->type_status_id == 154) {
            $solicitud->type_status_id = 155;
            $solicitud->save();
            $tur_aten =  Solicitud::join('sede_solicitudes', 'sede_solicitudes.solicitud_id', '=', 'solicitudes.id')
                ->where("sede_id", session('sede')->id_sede)
                ->whereIn('type_status_id', [155, 156])
                ->whereDate('solicitudes.created_at', date('Y-m-d'))
                ->orderBy("turno", 'desc')->first();
            $turno = null;
            if ($tur_aten) $turno = $tur_aten->turno;

            $user = User::where(['idnumber' => $solicitud->idnumber, 'tipodoc_id' => $solicitud->tipodoc_id])->first();
            $render =  view('myforms.recepcion.frm_solicitud_espera_ajax', compact('solicitud', 'tur_aten', 'user'))->render();
            /*  NewPush::channel('solicitudes_send')->message([
                'solicitud_id'=>$id,
                'render'=>$render,
                'tur_aten'=>$turno
                ])->publish();  */
            $solicitudes = $this->get_solicitudes();
            $render = view('myforms.solicitudes.frm_list_solicitudes_ajax', compact('solicitudes'))
                ->render();
            $solicitudesh = $this->get_solicitudesh();
            $renderh = view('myforms.solicitudes.frm_list_solicitudesh_ajax', compact('solicitudesh'))
                ->render();
            /* NewPush::channel('solicitudes_coord')
            ->message(['data'=>'mensaje','render'=>$render,'renderh'=>$renderh])->publish(); */
 
            if ($solicitud->type_status_id == 154) {
                $solicitud->type_status_id = 155;
                $solicitud->save();
                $tur_aten =  Solicitud::join('sede_solicitudes', 'sede_solicitudes.solicitud_id', '=', 'solicitudes.id')
                    ->where("sede_id", session('sede')->id_sede)
                    ->whereIn('type_status_id', [155, 156])
                    ->whereDate('solicitudes.created_at', date('Y-m-d'))
                    ->orderBy("turno", 'desc')->first();
                $turno = null;
                if ($tur_aten) $turno = $tur_aten->turno;
                $user = User::where(['idnumber' => $solicitud->idnumber, 'tipodoc_id' => $solicitud->tipodoc_id])->first();
                $render =  view('myforms.recepcion.frm_solicitud_espera_ajax', compact('solicitud', 'tur_aten', 'user'))->render();
                event(new SolicitudEstadosCambio($solicitud));
            }
        }
        return view('myforms.solicitudes.frm_edit_solicitud', compact('solicitud'));
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
        // return response()->json($request->all());
        if ($request->type_status_id == 156) {
            if ($request->has('hte') and $request->has('tiempo_espera')) {
                $date = Carbon::now()->addMinutes($request->tiempo_espera);
                $request['tiempo_espera'] = $date;
            }
        }
        $solicitud = Solicitud::find($id);
        $solicitud->fill($request->all());
        $solicitud->save();
        $response = [];
        if ($request->type_status_id != 158) {

            $tur_aten =  Solicitud::join('sede_solicitudes', 'sede_solicitudes.solicitud_id', '=', 'solicitudes.id')
                ->where("sede_id", session('sede')->id_sede)
                ->whereIn('type_status_id', [155, 156])
                ->whereDate('solicitudes.created_at', date('Y-m-d'))
                ->orderBy("turno", 'desc')->first();
            $user = User::where(['idnumber' => $solicitud->idnumber, 'tipodoc_id' => $solicitud->tipodoc_id])->first();
            $render =  view('myforms.recepcion.frm_solicitud_espera_ajax', compact('solicitud', 'tur_aten', 'user'))->render();
            NewPush::channel('solicitudes_send')->message([
                'solicitud_id' => $id,
                'render' => $render,
            ])->publish();
            $response['type_status_id'] = $solicitud->type_status_id;
        } elseif ($request->type_status_id == 158) {
            //cuando se cancela una solicitud
            $solicitudes = $this->get_solicitudes();
            $render = view('myforms.solicitudes.frm_list_solicitudes_ajax', compact('solicitudes'))->render();
            $solicitudesh = $this->get_solicitudesh();
            $renderh = view('myforms.solicitudes.frm_list_solicitudesh_ajax', compact('solicitudesh'))->render();

            NewPush::channel('solicitudes_coord')
                ->message([
                    'solicitud_id' => $id,
                    'render' => $render,
                    'renderh' => $renderh,
                    'type_status_id' => $solicitud->type_status_id,
                    'type_status' => $solicitud->estado->ref_nombre
                ])->publish();
        }
        $response['status'] = 200;
        $response['tiempo_espera'] = $solicitud->tiempo_espera;
        $response['type_category'] = $solicitud->categoria->ref_nombre;
        $response['type_status'] = $solicitud->estado->ref_nombre;
        event(new SolicitudEstadosCambio($solicitud));
        return response()->json($response);
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

    private function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function userRegister(Request $request)
    {
        $messages = [
            'name.required' => 'El nombre es requerido.',
            'lastname.required' => 'El apellido es requerido.',
            'email.unique' => 'El :attribute  ya existe en otra cuenta.',
            'email.required' => 'El :attribute es requerido.',
            'idnumber.required' => 'El número de documento es requerido.',
            'idnumber.unique' => 'El número de documento ya existe en otra cuenta.',
        ];
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'lastname' => ['required'],
            'email' => ['required', 'unique:users'],
            'idnumber' => ['required', 'unique:users']
        ], $messages);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }


        $solicitud = $this->solicitudesService->find($request->solicitud_id);
        $request['idnumber'] = $solicitud->idnumber;
        $request['tipodoc_id'] = $solicitud->tipodoc_id;
        $request['tel1'] = $solicitud->tel1;
        $request['active'] = 1;
        $request['email'] = $solicitud->email;
        $user = $this->userService->store($request);
        if (Auth::guest()) Auth::login($user);

        $solicitud->type_status_id = 165;
        $solicitud->save();

        return response()->json($solicitud);

        if (Auth::guest()) {
            Auth::login($user);
            $url = "/oficina/solicitante/solicitud/" . $solicitud->id;
        } else {
            $url = "";
            $tur_aten =  Solicitud::join('sede_solicitudes', 'sede_solicitudes.solicitud_id', '=', 'solicitudes.id')
                ->where("sede_id", session('sede')->id_sede)
                ->whereIn('type_status_id', [155, 156])
                ->whereDate('solicitudes.created_at', date('Y-m-d'))
                ->orderBy("turno", 'desc')
                ->first();
            $solicitud->type_status_id = 165;
            $solicitud->type_category_id = 172;
            $solicitud->save();
            $user = User::where(['idnumber' => $solicitud->idnumber, 'tipodoc_id' => $solicitud->tipodoc_id])->first();
            $render =  view('myforms.recepcion.frm_solicitud_espera_ajax', compact('solicitud', 'tur_aten', 'user'))->render();
            NewPush::channel('solicitudes_send')->message([
                'solicitud_id' => $solicitud->id,
            ])
                ->publish();
            NewPush::channel('solicitudes_coord')
                ->message([
                    'solicitud_id' => $solicitud->id,
                    'reload' => "true",
                ])->publish();
        }
        //return Redirect::to('oficina/solicitante');
        $response = [];
        $response['url'] = $url;
        $response['status'] = 200;
        return response()->json($response);
    }

    public function waitRoom($token)
    {
        // dd('Hola');
        try {
            $solicitud = Solicitud::where('token', $token)->first();
            /// dd ($solicitud->sedes);
            $tur_aten =  Solicitud::join('sede_solicitudes', 'sede_solicitudes.solicitud_id', '=', 'solicitudes.id')
                ->where("sede_id", $solicitud->sedes[0]->id_sede)
                ->whereIn('type_status_id', [155, 156])
                ->whereDate('solicitudes.created_at', date('Y-m-d'))
                ->orderBy("turno", 'desc')->first();

            $user = User::where(['idnumber' => $solicitud->idnumber])->first();

            return view('myforms.recepcion.frm_solicitud_espera', compact('solicitud', 'tur_aten', 'user'));
        } catch (\Throwable $th) {
            dd($th);
            return view('errors.error');
        }
    }

    public function find(Request $request)
    {
        $solicitud = Solicitud::where('number', $request->number)->first();
        try {
            return response()->json(['status' => 200, 'token' => $solicitud->token]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'error']);
        }
    }


    public function storeDocument(Request $request)
    {
        //return response()->json($request->all()); 
        $solicitud = Solicitud::find($request->solicitud_id);

        if ($request->file('solicitud_file') != '') {
            $notification_message = 'documento';
            $file = $solicitud->uploadFile($request->file('solicitud_file'), '/solicitud_' . $solicitud->number);
            $solicitud->files()->attach($file, [
                'type_status_id' => 152,
                'concept' => $request->concept,
                'user_id' => auth()->user()->id,
                'type_category_id' => $request->type_category_id
            ]);
        }
        //$expediente = $caseL->expediente;
        $response = [];
        if ($request->type_category_id == 164) {
            if ($request->view == 'student') {
                $type_category_id = $request->type_category_id;
                $response['solic_files'] = view('myforms.components_exp.frm_list_documents', compact('solicitud', 'type_category_id'))->render();
            } else {
                $response['solic_files'] = view('myforms.solicitudes.frm_list_documents', compact('solicitud'))->render();
            }
        } elseif ($request->type_category_id == 163) {
            $response['solic_files'] = view('front.solicitudes.frm_list_documents', compact('solicitud'))->render();
        }
        //$response['type_log_id'] = $caseL->type_log_id;

        if ($request->has("view_template")) {
            $view = view($request->get("view_template"), compact('solicitud'))->render();
            $response['view'] = $view;
            event(new RecepcionDocumentSolicitudEvent($solicitud, $view));
        }


        return response()->json($response);
    }

    public function editDocumento(Request $request, $id)
    {
        $solicitud = Solicitud::find($request->solicitud_id);
        $solicitud->files = $solicitud->files()->where('file_id', $request->id)->get();
        $response = [];
        $response['solicitud'] = $solicitud;
        return response()->json($response);
    }

    public function updateDocument(Request $request)
    {
        $solicitud = Solicitud::find($request->solicitud_id);
        $file = $solicitud->files()->where('file_id', $request->id)->first();
        $file->pivot->concept = $request->concept;
        $file->pivot->save();
        if ($request->file('solicitud_file') != '') {
            if ($file and $file->path != '') {
                Storage::delete($file->path);
            }
            $file = $file->delete();
            $file = $solicitud->uploadFile($request->file('solicitud_file'), '/solicitud_' . $solicitud->number);
            $solicitud->files()->attach($file, [
                'type_status_id' => 152,
                'concept' => $request->concept
            ]);
        }

        $response = [];
        $response['solic_files'] = view('myforms.solicitudes.frm_list_documents', compact('solicitud'))->render();
        //$response['type_log_id'] = $caseL->type_log_id;

        return response()->json($response);
    }

    public function deleteDocumento(Request $request, $id)
    {

        $file = File::find($id);
        // $file = $solicitud->files()->where('file_id',$request->id)->first();
        if ($file and $file->path != '') {
            Storage::delete($file->path);
        }
        $file->delete();
        $response = [];
        //$response['solic_files'] = view('myforms.solicitudes.frm_list_documents',compact('solicitud'))->render();
        return response()->json($response);
    }

    public function recepcion(Request $request)
    {
        //$sedes = Sede::all();
        //return view('vacaciones');
        return view('myforms.recepcion.frm_solicitud');
    }
}
