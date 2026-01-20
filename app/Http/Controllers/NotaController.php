<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Nota;
use App\Expediente;
use App\Requerimiento;
use DB;
use App\User;
use App\AsignacionCaso;
use App\Actuacion;
use App\Periodo;
use App\Segmento;
use Excel;
use App\Exports\NotasExport;
use App\Services\PeriodosService;
use App\Services\SegmentosService;
use App\Services\UsersService;
use Carbon\Carbon;

class NotaController extends Controller
{
    private $userService;
    private $periodosService;
    private $segmentosService;

    public function __construct(
        UsersService $userService,
        PeriodosService $periodosService,
        SegmentosService $segmentosService
    ) {
        $this->userService = $userService;
        $this->periodosService = $periodosService;
        $this->segmentosService = $segmentosService;
    }


    public function index(Request $request)
    {

        $periodos = Periodo::all();
       
        return view('report.notas.frm_notas_list', compact('periodos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notas_ver(Request $request)
    {
        $segmentoAct = $this->segmentosService->getSegmentoActivo();

        // $user = User::where('idnumber',3030)->first();
        if (currentUser()->hasRole("estudiante")) {
            $user = User::where('idnumber', auth()->user()->idnumber)->first();
        } elseif (currentUser()->can("ver_notas_estudiante")) {
            if ($request->has('idnumber')) {
                $user = User::where('idnumber', $request->idnumber)->first();
            }
        }
        if($request->has('expid')){
           $request['segid'] = $segmentoAct->id;
          
        }
        $notas = [];
        if (isset($user)) {
            $notas = $user->getNotas($request);
           // dd($notas);
            return view("myforms.notas_ver.index", compact('user', 'notas','segmentoAct'));
        } else {
            //$user = User::where('idnumber',3030)->first();
            $request->session()->flash('message-success', 'No se encontró el estudiante!');
        }


        // dd($notas);
        return view("myforms.notas_ver.index", compact('notas', 'segmentoAct'));
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
            $response = 0;

            //return response()->json($request->all());

            $expediente  = Expediente::where('expid', $request->expid)->first();
            $expediente->estudiante;
            $docente_id = \Auth::user()->idnumber;
            $estudiante_id = $expediente->estudiante->idnumber;
            if (
                count($expediente->get_nota_corte('etica')) > 0
                and $expediente->get_nota_corte('etica')['nota'] == 0
                and $expediente->get_nota_corte('etica')['id'] == 0
            ) {
                $data = [
                    'ntaaplicacion' => $request->ntaaplicacion,
                    'ntaconocimiento' => $request->ntaconocimiento,
                    'ntaetica' => $request->ntaetica,
                    'ntaconcepto' => $request->ntaconcepto,
                    'orgntsid' => $request->orgntsid,
                    'segid' => $request->segid,
                    'perid' => $request->perid,
                    'tpntid' => $request->tpntid,
                    'expidnumber' => $request->expid,
                    'estidnumber' => $estudiante_id,
                    'docidnumber' => $docente_id,
                    'tbl_org_id' => $expediente->id,
                ];
                $expediente->asignarNotas($data);
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
        ob_end_clean(); // this
        ob_start();
        if (!$request->ajax()) {

            $notas = $this->notas_download($request);
            
            //return response()->json($notas);
            $segmento = Segmento::find($request->segmento_id);
            $periodo = Periodo::find($request->periodo_id);

            if ($request->segmento_id) {
                $header =  ['cedula', 'codigo', 'nombres', 'curso', 'casos', 'defensas', 'conciliaciones', 'otros', 'final'];
                return Excel::download(new NotasExport($notas, $header, null, 'Notas_' . $periodo->prddes_periodo), 'Reporte.xlsx');
            } else {
                $segmentos = Segmento::join('sede_segmentos as sg', 'sg.segmento_id', '=', 'segmentos.id')
                    ->where('sg.sede_id', session('sede')->id_sede)
                    ->where('perid', $request->periodo_id)
                    ->get();
                $header = ['cedula', 'codigo', 'nombres', 'curso'];
                $segme = ['Datos del estudiante'];
                foreach ($segmentos as $key => $segmento) {
                    $header[] = 'casos';
                    $header[] = 'defensas';
                    $header[] = 'conciliaciones';
                    $header[] = 'otros';
                    $header[] = 'final';
                }
                $header[] = 'final periodo';
                return Excel::download(new NotasExport($notas, $header, $segmentos, 'Notas_' . $periodo->prddes_periodo), 'Reporte.xlsx');
            }
            $data = [
                'success' => true,
                'path' => '/exports/Notas_' . $periodo->prddes_periodo . '.xlsx'
            ];

            return response()->json($data);





            $segmentos = Segmento::where('perid', $request->periodo_id)->get();



            $not_exp = [];
            $not_act = [];
            $not_req = [];

            $nota_conocimiento = [];
            $nota_aplicacion = [];
            $nota_etica = [];
            $notasR = [];

            foreach ($segmentos as $key => $segmento) {
                $not = [];
                foreach ($notas as $key_1 => $nota) {
                    if ($segmento->id == $nota->segid and $nota->cptnotaid != 4)  $not[] = $nota;
                }
                $notasR[$segmento->segnombre] = $not;
            }
            // return response()->json($notasR); 
            $notas = [];
            foreach ($notasR as $key => $notaR) {

                if (count($notaR) > 0) {
                    foreach ($notaR as $key_2 => $nota) {
                        if ($nota->cptnotaid == 1)  $nota_conocimiento[] = $nota->nota;
                        if ($nota->cptnotaid == 2)  $nota_aplicacion[] = $nota->nota;
                        if ($nota->cptnotaid == 3)  $nota_etica[] = $nota->nota;
                    }
                    $notas[$key] = [
                        'conocimiento' => $nota_conocimiento,
                        'etica' => $nota_etica,
                        'aplicacion' => $nota_aplicacion,

                    ];
                } else {
                    $notas[$key] = [
                        'conocimiento' => [],
                        'etica' => [],
                        'aplicacion' => [],
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
        $notas = [];
        if ($request->origen == 2) {
            $actuacion = Actuacion::find($id);
            $notas = $actuacion->get_notas();
        }
        if ($request->origen == 1) {
            $expediente = Expediente::find($id);
            //return response()->json($expediente);
            if (count($expediente->get_has_nota_final()) > 0) {
                $nota_final = $expediente->get_has_nota_final();
                $days = \Carbon\Carbon::parse($nota_final['nota_conocimiento']['created_at'])->diffInDays(Carbon::now());
                //return response()->json($days);
                $can_edit = false;
                if ($expediente->getDocenteAsig()->idnumber == currentUser()->idnumber || currentUser()->hasRole('amatai') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('diradmin')) $can_edit = true;
                if ($days <= 1) {
                    $can_edit = true;
                }
                $notas = [
                    "nota_conocimiento" => number_format($nota_final['nota_conocimiento']['nota'], 1, '.', '.'),
                    "nota_conocimientoid" => $nota_final['nota_conocimiento']['id'],
                    "nota_etica" => number_format($nota_final['nota_etica']['nota'], 1, '.', '.'),
                    'nota_eticaid' => $nota_final['nota_etica']['id'],
                    "nota_aplicacion" => number_format($nota_final['nota_aplicacion']['nota'], 1, '.', '.'),
                    "nota_aplicacionid" => $nota_final['nota_aplicacion']['id'],
                    'nota_concepto' => $nota_final['nota_concepto']['nota'],
                    'nota_conceptoid' => $nota_final['nota_concepto']['id'],
                    "nota_final" => number_format($nota_final['nota_final']['nota'], 1, '.', '.'),
                    "can_edit" =>  $can_edit,
                    "encontrado" => true,
                    "segmento" => $nota_final['segmento'],
                    "periodo" => $nota_final['nota_aplicacion']['periodo'],
                    "tipo" => $nota_final['nota_aplicacion']['tipo'],
                    "tipo_id" => $nota_final['nota_aplicacion']['tipo_id'],
                    "segmento_id" => $nota_final['segmento_id'],
                    "docevname" => $nota_final['nota_conocimiento']['docevname']
                ];

                //  $nota_final['nota_etica']['docidnumber'] == \Auth::user()->idnumber ? $notas["can_edit"] = true:'';


            } else {
                $notas = $expediente->get_notas();
                if ($expediente->getDocenteAsig()->idnumber == currentUser()->idnumber) $notas["can_edit"] = true;
            }
        }
        if ($request->origen == 3) {
            $req = Requerimiento::find($id);
            $notas = $req->get_notas();
            //return response()->json($req);
        }

        return response()->json($notas);
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
        return response()->json($request->all());

        if ($request->ajax()) {

            $nota = Nota::find($id);
            $nota->nota = $request->new_nota;
            $nota->save();

            $expediente = Expediente::where('expid', $request->expid)->first();
            $final = $nota->getNotaFinal($expediente->notas);

            return response()->json($final);
        }
    }

    public function updateNota(Request $request)
    {

        // return response()->json($request->all());

        if ($request->ajax()) {
            $expediente = Expediente::find($request->exp_id);
            foreach ($request->nota as $key_1 => $nota_r) {
                foreach ($request->nota_id as $key_2 => $nota_id) {
                    if ($key_1 == $key_2) {
                        $nota = Nota::find($nota_id);
                        $nota->nota = $nota_r;
                        $nota->docidnumber = \Auth::user()->idnumber;
                        $nota->tpntid = $request->tipo_nota_id;
                        if ($nota->tbl_org_id == null) $nota->tbl_org_id = $request->tbl_org_id;

                        $nota->save();
                        $ex_id = $nota->expidnumber;
                    }
                }
            }
            $final = number_format($expediente->get_nota_corte('final')['nota'], 1, '.', '.');

            $response = [
                "nota_final" => $final,
                'user_name' => \Auth::user()->name,
                "notas_caso" => view("myforms.frm_calificacion_create_ajax_ln", compact('expediente'))->render(),
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
    public function delete(Request $request)
    {
        //return response()->json($request->all());
        foreach ($request->nota_id as $key_2 => $nota_id) {
            $nota = Nota::find($nota_id);
            $nota->delete();
        }



        if ($request->has('origen') and $request->origen == 3) {
            $req = Requerimiento::find($request->tbl_org_id);
            if ($req) {
                $req->evaluado = 0;
                $req->save();
            }
            return response()->json($req);
        }

        return response()->json($request->all());
    }

    public function notas_download($request)
    {
        /* $periodo = Periodo::where('estado',1)->first();
      $segmento = Segmento::where('perid',$periodo->id)->first(); */
        if ($request->idnumber) {
            $users = DB::table('users')
                ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->leftjoin('referencias_tablas', 'referencias_tablas.id', '=', 'users.cursando_id')
                ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
                ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
                ->where('role_id', '6')
                ->where('users.active', true)
                ->where('users.idnumber', $request->idnumber)
                ->where('sedes.id_sede', session('sede')->id_sede)
                ->select( 
                    'users.active',
                    'users.id',
                    'ref_nombre',
                    'users.idnumber',
                    'users.codigo_estudiantil',
                    DB::raw('CONCAT(users.name," ",users.lastname) as full_name'),
                    'role_user.role_id',
                    'roles.display_name'
                )
                ->orderBy('users.created_at', 'desc')
                ->get();
        } else {
            $users = DB::table('users')
                ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->leftjoin('referencias_tablas', 'referencias_tablas.id', '=', 'users.cursando_id')
                ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
                ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
                ->join('turnos','turnos.trnid_estudent','=',"users.idnumber")
                ->where('role_id', '6')
                ->where('users.active', true)
                ->where('sedes.id_sede', session('sede')->id_sede)
                ->select(
                    'users.active',
                    'users.id',
                    'ref_nombre',
                    'users.idnumber',
                    'users.codigo_estudiantil',
                    DB::raw('CONCAT(users.name," ",users.lastname) as full_name'),
                    'role_user.role_id',
                    'roles.display_name'
                )
                ->orderBy('users.created_at', 'desc')
                ->get();
        }       
        $notas_periodo = [];
        if ($request->segmento_id) {
            foreach ($users as $key_2 => $user) {
                //if($user->idnumber=="1004133867"){
                //  dd($nota_caso);

                $to_excel = [];
                $to_excel[] = $user->idnumber;
                $to_excel[] = $user->codigo_estudiantil;
                $to_excel[] = $user->full_name;
                $to_excel[] = $user->ref_nombre;
                $nota_us = $this->getNotas($user, $request->segmento_id);
                foreach ($nota_us as $key_3 => $not) {
                    $to_excel[] = $not;
                }
                $notas_periodo[] = $to_excel;
                //}      
            }
            return $notas_periodo;
        } else {
            $segmentos = Segmento::join('sede_segmentos as sg', 'sg.segmento_id', '=', 'segmentos.id')
                ->where('sg.sede_id', session('sede')->id_sede)
                ->where('perid', $request->periodo_id)->get();
            foreach ($users as $key_2 => $user) {
                //  if($user->idnumber=="1004133867"){
                $final = 0;
                $to_excel = [];
                $to_excel[] = $user->idnumber;
                $to_excel[] = $user->codigo_estudiantil;
                $to_excel[] = $user->full_name;
                $to_excel[] = $user->ref_nombre;
                foreach ($segmentos as $key => $segmento) {
                    $nota_us = $this->getNotas($user, $segmento->id);
                    foreach ($nota_us as $key_3 => $not) {
                        $to_excel[] = $not;
                    }
                    if (is_numeric($nota_us[4]) and is_numeric($final)) {
                        $final =  ($final + $nota_us[4]);
                    } else {
                        $final = "N/A";
                    }
                    //$final = 

                }
                if (is_numeric($final)) {
                    $to_excel[] = round($final / count($segmentos), 1);
                } else {
                    $to_excel[] = $final;
                }
                $notas_periodo[] = $to_excel;

                // }

            }

            /*  */

            return $notas_periodo;
        }
    }

    private function getNotas($user, $segmento)
    {



        //dd( $nota_ofi );
        $nota_caso = DB::select(
            DB::raw("SELECT `estidnumber`, AVG(`nota`) as nota
                        FROM `notas` JOIN expedientes on notas.expidnumber=expedientes.expid        
                        WHERE `segid` = $segmento AND `cptnotaid` != 4 AND expedientes.exptipoproce_id != '3'
                      and estidnumber = $user->idnumber
                        GROUP BY `estidnumber` order by cptnotaid")
        );

        $nota_defensas = DB::select(DB::raw(
            "SELECT `estidnumber`, AVG(`nota`)  as nota FROM `notas`
                        JOIN expedientes on notas.expidnumber=expedientes.expid 
                        WHERE `segid` = $segmento AND `cptnotaid` != 4 AND expedientes.exptipoproce_id = '3'
                        and estidnumber = $user->idnumber
                        GROUP BY `estidnumber`"
        ));

        $notas_oficina =[];
        /*  DB::select(DB::raw(
            "SELECT `estidnumber`, AVG(`nota`) as nota FROM `notas_ext`       
                        WHERE `segid` = $segmento AND `cptnotaid` != 4  
                        and estidnumber = $user->idnumber
                        GROUP BY `estidnumber`"
        )); */

        $data_user = [];

        $has_ncaso = false;
        $nota_final = 0;

        if (count($nota_caso) > 0) $has_ncaso = (array_search($user->idnumber, array_column($nota_caso, 'estidnumber')));
        //dd(($has_ncaso));
        if (is_numeric($has_ncaso)) {
            $nota_c = round($nota_caso[$has_ncaso]->nota, 1);
            $nota_final += ($nota_c * 0.4);
        } else {
            $nota_c = "N/A";
        }


        $has_ndefensa = false;
        if (count($nota_defensas) > 0) $has_ndefensa = array_search($user->idnumber, array_column($nota_defensas, 'estidnumber'));
        if (is_numeric($has_ndefensa)) {
            $nota_d = round($nota_defensas[$has_ndefensa]->nota, 1);
            $nota_final += ($nota_d * 0.2);
        } else {
            $nota_d = "N/A";
            if (is_numeric($nota_c)) $nota_final += ($nota_c * 0.2);
        }

        $nota_con = "N/A";
        // $percent_nota_caso += 20;
        if (is_numeric($nota_c)) $nota_final += ($nota_c * 0.2);


        $has_nota_ofi = false;
        if (count($notas_oficina) > 0) $has_nota_ofi = array_search($user->idnumber, array_column($notas_oficina, 'estidnumber'));
        if (is_numeric($has_nota_ofi)) {
            $nota_ofi = round($notas_oficina[$has_nota_ofi]->nota, 1);
            $nota_final += ($nota_ofi * 0.2);
        } else {
            $nota_ofi = "N/A";
            if (is_numeric($nota_c)) $nota_final += ($nota_c * 0.2);
        }

        // $final = ()
        //$ncp = ($percent_nota_caso/100);
        $data_user[] = $nota_c;
        $data_user[] = $nota_d;
        $data_user[] = $nota_con;
        $data_user[] = $nota_ofi;
        $data_user[] = is_numeric($nota_c) ? round($nota_final, 1) : "N/A";

        return $data_user;
    }
}
