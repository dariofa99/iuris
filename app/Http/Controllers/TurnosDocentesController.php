<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TurnosDocente;
use App\Periodo;
use App\Services\PeriodosService;
use App\Services\UsersService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TurnosDocentesController extends Controller
{
    private $periodoService;
    private $userService;

    public function __construct(PeriodosService $periodoService, UsersService $userService)
    {
        $this->periodoService = $periodoService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $docentes = DB::table('users')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftjoin('referencias_tablas', 'referencias_tablas.id', '=', 'users.cursando_id')
            ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
            ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
            ->where('role_id', '4')
            ->where('users.active', true)
            ->where('users.idnumber', '<>', '2020')
            ->where('sedes.id_sede', session('sede')->id_sede)
            ->select(
                'users.active',
                'users.id',
                'ref_nombre',
                'users.idnumber',
                DB::raw('CONCAT(users.name," ",users.lastname) as full_name'),
                'role_user.role_id',
                'roles.display_name'
            )->orderBy('users.created_at', 'desc')->get();
       // dd($docentes);
        return view('myforms.frm_turnos_docentes_list', compact('docentes'))->render();
        //
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $periodo = Periodo::join('sede_periodos as sp', 'sp.periodo_id', '=', 'periodo.id')
            ->where('sp.sede_id', session('sede')->id_sede)
            ->where('estado', 1)
            ->first();
        $turnos_doc = TurnosDocente::where('trnd_docidnumber', $request->id)->where('trndid_periodo', $periodo->id)->orderBy('trnd_hora_inicio', 'ASC')->get();
        return response()->json(

            $turnos_doc->toArray()

        );
        //dd($turnos_doc);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $response = [];
        $periodo = $this->periodoService->getPeriodoActivo();
        $prdfecha_inicio = $periodo->prdfecha_inicio;
        $prdfecha_fin = $periodo->prdfecha_fin;
        if (request()->has('start') && request()->has('end')) {
            if(Carbon::parse(request()->get('start'))->gt(Carbon::parse(request()->get('end')))) {
                return response()->json([
                    'errors' => 'La fecha inicial no puede ser mayor que la fecha final.'
                ], 200);
            }
            $prdfecha_inicio = request()->get('start');
            $prdfecha_fin = request()->get('end');
        }


        if(!$periodo) {
            return response()->json([
                'errors' => 'No hay un periodo activo actualmente.'
            ], 200);
        }

        

        $response['docentes'] = $docentes = DB::table('users')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
            ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
            ->leftjoin('referencias_tablas', 'referencias_tablas.id', '=', 'users.cursando_id')
            ->join('turnos_docentes', 'users.idnumber', '=', 'turnos_docentes.trnd_docidnumber')
            ->where('role_id', '4')
            ->where('users.active', true)
            ->where('users.idnumber', '<>', '2020')
            ->where('sedes.id_sede', session('sede')->id_sede)
            ->select('users.idnumber', DB::raw('CONCAT(users.name," ",users.lastname) as full_name'))
            ->groupBy('users.idnumber')->orderBy('users.created_at', 'desc')->get();


/* 
        $response['asistencia'] =  $asistencia = DB::table('asistencia_docentes')
            ->where('reposicion', '0')
            ->where('tipo_asis', '149')
            ->whereDate('inicio', '>=', $prdfecha_inicio)
            ->select('docidnumber', DB::raw('SUM(TIMESTAMPDIFF(MINUTE, `inicio`, `fin`)) AS asistencia'))
            ->groupBy('docidnumber')
            ->orderBy('docidnumber', 'desc')->get(); */

        $response['asistencia'] = DB::table('asistencia_docentes')
            ->where('reposicion', '0')
            ->where('tipo_asis', '149')
            ->whereDate('inicio', '>=', $prdfecha_inicio)
            ->select(
                'docidnumber',
                DB::raw('SUM(TIMESTAMPDIFF(MINUTE, `inicio`, `fin`)) AS asistencia'),
                DB::raw("
            CONCAT(
                FLOOR(SUM(TIMESTAMPDIFF(MINUTE, inicio, fin)) / 60),
                ' hora',
                IF(FLOOR(SUM(TIMESTAMPDIFF(MINUTE, inicio, fin)) / 60) <> 1, 's', ''),
                IF(
                    MOD(SUM(TIMESTAMPDIFF(MINUTE, inicio, fin)), 60) > 0,
                    CONCAT(
                        ', ',
                        MOD(SUM(TIMESTAMPDIFF(MINUTE, inicio, fin)), 60),
                        ' minuto',
                        IF(MOD(SUM(TIMESTAMPDIFF(MINUTE, inicio, fin)), 60) <> 1, 's', '')
                    ),
                    ''
                )
            ) AS asistencia_label
        ")
            )
            ->groupBy('docidnumber')
            ->orderBy('docidnumber', 'desc')
            ->get();

        //$asistencia = DB::select('SELECT `docidnumber`, SUM(TIMESTAMPDIFF(MINUTE, `inicio`, `fin`)) AS asistencia FROM `asistencia_docentes` WHERE `reposicion`=0 AND `tipo_asis` = 149	GROUP BY `docidnumber` ORDER BY `docidnumber` DESC');

        $response['horas_semanales'] = $this->tuMetodo(request());

        $response['permisos'] = $permisos = DB::table('asistencia_docentes')
            ->where('reposicion', '0')
            ->where('tipo_asis', '150')
            ->whereDate('inicio', '>=', $prdfecha_inicio)
            ->select('docidnumber', DB::raw('SUM(TIMESTAMPDIFF(MINUTE, `inicio`, `fin`)) AS permisos'))
            ->groupBy('docidnumber')->orderBy('docidnumber', 'desc')->get();

        $response['faltas'] = DB::table('asistencia_docentes')
            ->where('reposicion', '0')
            ->where('tipo_asis', '284')
            ->whereDate('inicio', '>=', $prdfecha_inicio)
            ->select('docidnumber', DB::raw('SUM(TIMESTAMPDIFF(MINUTE, `inicio`, `fin`)) AS faltas'))
            ->groupBy('docidnumber')->orderBy('docidnumber', 'desc')->get();

        //$permisos = DB::select('SELECT `docidnumber`, SUM(TIMESTAMPDIFF(MINUTE, `inicio`, `fin`)) AS permisos FROM `asistencia_docentes` WHERE `reposicion`=0 AND `tipo_asis` = 150	GROUP BY `docidnumber` ORDER BY `docidnumber` DESC');   
        $response['reposicion'] = $reposicion = DB::table('asistencia_docentes')
            ->where('categoria', 'reposicion')
            ->where('tipo_asis', '285')
            ->whereDate('inicio', '>=', $prdfecha_inicio)
            ->select('docidnumber', DB::raw('SUM(TIMESTAMPDIFF(MINUTE, `inicio`, `fin`)) AS reposicion'))
            ->groupBy('docidnumber')->orderBy('docidnumber', 'desc')->get();
        //$reposicion = DB::select('SELECT `docidnumber`,SUM(TIMESTAMPDIFF(MINUTE, `inicio`, `fin`)) AS reposicion FROM `asistencia_docentes` WHERE `reposicion`=1 AND `tipo_asis` = 149	GROUP BY `docidnumber` ORDER BY `docidnumber` DESC');

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {}

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

    public function updateinfo(Request $request)
    {
        //
        foreach ($request->all() as $key => $info) { //recibe el json y se dispone a ver las actividades a realizar
            if ($info['accion'] == 'eliminar') {
                $turnos_doc = TurnosDocente::where('trnd_hora_inicio', $info['hora_i'])
                    ->where('trnd_hora_fin', $info['hora_f'])
                    ->where('trnd_dia', $info['value'])
                    ->where('trnd_docidnumber', $info['usuario'])
                    ->delete();
            } elseif ($info['accion'] == 'crear') {
                $periodo = Periodo::where('estado', 1)->first();
                $turnos_doc = TurnosDocente::insert([
                    "trnd_docidnumber" => $info['usuario'],
                    "trnd_dia" => $info['value'],
                    "trnd_hora_inicio" => $info['hora_i'],
                    "trnd_hora_fin" => $info['hora_f'],
                    "trndid_periodo" => $periodo->id
                ]);
            } elseif ($info['accion'] == 'actualizar') {
                if (is_array($info['value'])) {
                    $turnos_doc = TurnosDocente::where('trnd_hora_inicio', $info['hora_i'])
                        ->where('trnd_hora_fin', $info['hora_f'])
                        ->where('trnd_docidnumber', $info['usuario'])
                        ->update(["trnd_hora_inicio" => $info['value'][0], "trnd_hora_fin" => $info['value'][1]]);
                }
            }
        }
        return response()->json($request->all());
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

    public function tuMetodo(Request $request)
    {
        $periodo = $this->periodoService->getPeriodoActivo();

        if (!$periodo) {
            return response()->json([
                'errors' => 'No hay un periodo activo actualmente.'
            ], 400);
        }

        /*
    |--------------------------------------------------------------------------
    | Rango de fechas
    |--------------------------------------------------------------------------
    */

        $inicio = Carbon::parse(request()->get('start'))->startOfDay();
        $fin = Carbon::parse(request()->get('end'))->endOfDay();

        if ($inicio->gt($fin)) {
            return response()->json([
                'errors' => 'La fecha inicial no puede ser mayor que la fecha final.'
            ], 400);
        }

        /*
    |--------------------------------------------------------------------------
    | Equivalencia de los días almacenados en turnos_docentes
    |--------------------------------------------------------------------------
    |
    | Carbon::dayOfWeekIso:
    |
    | 1 = lunes
    | 2 = martes
    | 3 = miércoles
    | 4 = jueves
    | 5 = viernes
    | 6 = sábado
    | 7 = domingo
    |
    */

        $diasSemana = [
            'lunes'     => Carbon::MONDAY,
            'martes'    => Carbon::TUESDAY,
            'miércoles' => Carbon::WEDNESDAY,
            'miercoles' => Carbon::WEDNESDAY,
            'jueves'    => Carbon::THURSDAY,
            'viernes'   => Carbon::FRIDAY,
            'sábado'    => Carbon::SATURDAY,
            'sabado'    => Carbon::SATURDAY,
            'domingo'   => Carbon::SUNDAY,
        ];

        /*
    |--------------------------------------------------------------------------
    | Obtener los turnos del período
    |--------------------------------------------------------------------------
    */

        $turnos = DB::table('turnos_docentes')
            ->where('trndid_periodo', $periodo->id)
            ->select(
                'trnd_docidnumber',
                'trnd_dia',
                'trnd_hora_inicio',
                'trnd_hora_fin'
            )
            ->orderBy('trnd_docidnumber')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Calcular minutos según el rango de fechas
    |--------------------------------------------------------------------------
    */

        $horasRango = $turnos
            ->groupBy('trnd_docidnumber')
            ->map(function ($turnosDocente) use (
                $inicio,
                $fin,
                $diasSemana
            ) {

                $minutosTotales = 0;

                /*
            |--------------------------------------------------------------------------
            | Procesar cada turno del docente
            |--------------------------------------------------------------------------
            */

                foreach ($turnosDocente as $turno) {

                    $diaTurno = strtolower(
                        trim($turno->trnd_dia)
                    );

                    /*
                | Si el día no existe en nuestro mapa,
                | simplemente ignoramos ese turno.
                */

                    if (!isset($diasSemana[$diaTurno])) {
                        continue;
                    }

                    $diaSemana = $diasSemana[$diaTurno];

                    /*
                |--------------------------------------------------------------------------
                | Duración del turno en minutos
                |--------------------------------------------------------------------------
                */

                    $horaInicio = Carbon::createFromFormat(
                        'H:i:s',
                        $turno->trnd_hora_inicio
                    );

                    $horaFin = Carbon::createFromFormat(
                        'H:i:s',
                        $turno->trnd_hora_fin
                    );

                    $minutosTurno = $horaInicio->diffInMinutes($horaFin);

                    /*
                |--------------------------------------------------------------------------
                | Contar cuántas veces aparece ese día dentro del rango
                |--------------------------------------------------------------------------
                */

                    $ocurrencias = 0;

                    $fecha = $inicio->copy()->startOfDay();

                    while ($fecha->lte($fin)) {

                        if ($fecha->dayOfWeekIso == $diaSemana) {
                            $ocurrencias++;
                        }

                        $fecha->addDay();
                    }

                    /*
                |--------------------------------------------------------------------------
                | Minutos del turno dentro del rango
                |--------------------------------------------------------------------------
                */

                    $minutosTotales += (
                        $minutosTurno * $ocurrencias
                    );
                }

                /*
            |--------------------------------------------------------------------------
            | Resultado por docente
            |--------------------------------------------------------------------------
            */

                return [
                    'trnd_docidnumber' => $turnosDocente->first()->trnd_docidnumber,
                    'minutos_rango' => $minutosTotales,
                    'horas_rango' => round($minutosTotales / 60, 2),
                ];
            })
            ->values();

        /*
    |--------------------------------------------------------------------------
    | Respuesta
    |--------------------------------------------------------------------------
    */

        $response['horas_semanales'] = $horasRango;

        return $horasRango;
    }
}
