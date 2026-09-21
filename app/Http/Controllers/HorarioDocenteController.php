<?php

namespace App\Http\Controllers;

use App\AsistenciaDocentes;
use Illuminate\Http\Request;
use \App\User;
use \App\Role;
use DB;
use App\HorarioDocente;
use App\TurnosDocente;
use Carbon\Carbon;

class HorarioDocenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $docentes_activos = $this->getDocentes();
        $horarios_docente = HorarioDocente::all();
        $horarios_docente->each(function ($horarios_docente) {
            $horarios_docente->docente->asignaciones_docente;
        });
        $estudiantes = $this->getestudiantes($request);
        if ($request->ajax()) {
            if ($request->data_search != 'all' and !empty($request->data_search)) {
                $estudiantes = $this->getEstudiante($request);
            }
            //return response()->json($estudiantes);
            return view('myforms.frm_asignaciones_docente_estudiante_ajax', compact('estudiantes'))->render();
        }

        $active_asig = 'active';
      
        return view('myforms.frm_asignaciones_docente_estudiante', compact('docentes_activos', 'horarios_docente', 'active_asig', 'estudiantes'));
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

        foreach ($request->docidnumber as $key => $docidnumber) {
            foreach ($request->horas_a as $key_2 => $horas_a) {
                foreach ($request->horas_b as $key_3 => $horas_b) {
                    foreach ($request->num_max_est as $key_4 => $num_max_est) {
                        if ($key == $key_2 and $key == $key_3 and $key == $key_4) {
                            // echo "$docidnumber <br>";
                            if ($horas_a != null and $horas_b != null and $horas_a != '0' and $horas_b != '0') {
                                $est_hora = ($horas_a + $horas_b) / $num_max_est;
                                $num_est_a = round($horas_a / $est_hora);
                                $num_est_b = round($horas_b / $est_hora);
                                $horario = [
                                    'docidnumber' => $docidnumber,
                                    'horas_a' => $horas_a,
                                    'horas_b' => $horas_b,
                                    'num_max_est' => $num_max_est,
                                    'num_est_a' => $num_est_a,
                                    'num_est_b' => $num_est_b
                                ];
                                HorarioDocente::create($horario);
                            }
                        }
                    }
                }
            }
        }
        return redirect('/docentes/horario');


        // dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estudiantes = Asigna_docen_est::where('asgedidnumberdocen', $id)->get();

        $estudiantes->each(function ($estudiantes) {
            $estudiantes->estudiante->curso;
            //$estudiantes->estudiante_curso;
            $estudiantes->docente;
            $estudiantes->periodo;
        });

        return response()->json($estudiantes);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $est_hora = ($request->horas_a + $request->horas_b) / $request->num_max_est;
        $num_est_a = round($request->horas_a / $est_hora);
        $num_est_b = round($request->horas_b / $est_hora);

        $horario = HorarioDocente::find($id);
        $horario->horas_a = $request->horas_a;
        $horario->horas_b = $request->horas_b;
        $horario->num_max_est = $request->num_max_est;
        $horario->num_est_a = $num_est_a;
        $horario->num_est_b = $num_est_b;
        $horario->save();


        return response()->json($horario);
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

    public function getDocentes()
    {

        $users = DB::table('users')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftjoin('horario_docentes', 'horario_docentes.docidnumber', '=', 'users.idnumber')
            ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
            ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
            ->where('horario_docentes.horas_a', '=', null)
            ->where('role_id', '4')
            ->where('users.active', true)
            ->where('sedes.id_sede', session('sede')->id_sede)
            ->select(
                'horario_docentes.horas_a',
                'users.active',
                'users.id',
                'users.idnumber',
                DB::raw('CONCAT(users.name," ",users.lastname) as full_name'),
                'role_user.role_id',
                'roles.display_name'
            )->orderBy('users.created_at', 'desc')->get();

        return ($users);
    }

    public function searchEstud(Request $request)
    {
        if ($request->data_search != 'all') {
            $estudiantes = $this->getEstudiante($request);
        } elseif ($request->data_search) {
            $estudiantes = $this->getEstudiantes();
        }
        // return response()->json($estudiantes);

        return view('myforms.frm_asignaciones_docente_estudiante_ajax', compact('estudiantes'))->render();
    }

    public function getEstudiante(Request $request)
    {

        if ($request->ajax()) {
            if ($request->data_search) {
                $estudiantes = DB::table('users')
                    ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
                    ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
                    ->leftjoin('asigna_docent_ests', 'asigna_docent_ests.asgedidnumberest', '=', 'users.idnumber')
                    ->leftjoin('referencias_tablas', 'referencias_tablas.id', '=', 'users.cursando_id')
                    ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
                    ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
                    ->where('asigna_docent_ests.asgedidnumberest', '=', null)
                    ->where('role_id', '6')
                    ->where('users.idnumber', $request->data_search)
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
                    )->orderBy('users.created_at', 'desc')->paginate(50);

                return $estudiantes;
            }
        }
        return false;
    }

    public function getEstudiantes(Request $request)
    {

        $users = DB::table('users')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftjoin('asigna_docent_ests', 'asigna_docent_ests.asgedidnumberest', '=', 'users.idnumber')
            ->leftjoin('referencias_tablas', 'referencias_tablas.id', '=', 'users.cursando_id')
            ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
            ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
            ->where('asigna_docent_ests.asgedidnumberest', '=', null)
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
            )->orderBy('users.created_at', 'desc')->paginate(20);

        return ($users);
    }

    public function searchHorasDocente(Request $request)
    {

        $horario_docente = HorarioDocente::where('docidnumber', $request->idnumber)->first();
        $horario_docente->docente->asignaciones_docente;
        return response()->json($horario_docente);
    }
    public function deleteAllHorarioDocentes()
    {
        $horarios = DB::table('horario_docentes')->delete();
        $horarios = DB::table('asigna_docent_ests')->delete();
    }

    public function registrarAsistencia(Request $request)
    {
        //return response()->json($request->all());
        $turno_docente = TurnosDocente::find($request->turno_id);

        if ($turno_docente === null) {
            return response()->json(['error' => 'El docente no coincide con el turno seleccionado.'], 400);
        }


        $inicio = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $request->fecha_turno . ' ' . ($request->asistencia_id != null ? $request->hora_inicio_asis : $request->hora_inicio)
        )->format('Y-m-d H:i:s');

        $fin = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $request->fecha_turno . ' ' . ($request->asistencia_id != null ? $request->hora_fin_asis : $request->hora_fin)
        )->format('Y-m-d H:i:s');

        if ($request->tipo_asis == 284) {
            $minutos_reponer = Carbon::parse($turno_docente->trnd_hora_inicio)->diffInMinutes(Carbon::parse($turno_docente->trnd_hora_fin));
            $inicio = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->fecha_turno . ' ' . ($turno_docente->trnd_hora_inicio)
            )->format('Y-m-d H:i:s');

            $fin = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->fecha_turno . ' ' . ($turno_docente->trnd_hora_fin)
            )->format('Y-m-d H:i:s');
        } else {
            $minutos_de_atencion = Carbon::parse($inicio)->diffInMinutes(Carbon::parse($fin));
            $minutos_turno = Carbon::parse($turno_docente->trnd_hora_inicio)->diffInMinutes(Carbon::parse($turno_docente->trnd_hora_fin));

            $minutos_reponer = $minutos_turno - $minutos_de_atencion;
        }



        $asistencia = AsistenciaDocentes::create([
            'docidnumber' => $turno_docente->trnd_docidnumber,
            'tipo_asis' => $request->tipo_asis,
            'inicio' => $inicio,
            'fin' => $fin,
            'descripcion' => $request->descripregisdocasis == '' ? "Sin descripción" : $request->descripregisdocasis,
            'categoria' => $request->categoria ?? "turno",
            'turno_docente_id' => $turno_docente->id,
            'minutos_reponer' => $minutos_reponer
        ]);

        if ($request->has('fecha_repo') && $request->has('hora_inicio_repo') && $request->has('hora_fin_repo')) {
            $inicio_repo = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->fecha_repo . ' ' . $request->hora_inicio_repo . ":00"
            )->format('Y-m-d H:i:s');

            $fin_repo = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->fecha_repo . ' ' . $request->hora_fin_repo . ":00"
            )->format('Y-m-d H:i:s');

            $asistencia = AsistenciaDocentes::create([
                'docidnumber' => $turno_docente->trnd_docidnumber,
                'tipo_asis' => 285,
                'inicio' => $inicio_repo,
                'fin' => $fin_repo,
                'descripcion' => $request->descripregisdocasis,
                'categoria' => $request->categoria ?? "reposicion",
                'turno_docente_id' => $turno_docente->id,
                // 'minutos_reponer' => $request->minutos_reponer
            ]);
        }




        return response()->json($asistencia);
    }
}
