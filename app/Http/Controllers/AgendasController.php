<?php

namespace App\Http\Controllers;

use App\CitacionEstudiantes;
use App\Services\ExpedientesService;
use App\Services\PeriodosService;
use App\Services\UsersService;
use App\TurnoEstudianteDocente;
use App\TurnosDocente;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AgendasController extends Controller
{
    private $periodosService;
    private $userService;

    public function __construct(PeriodosService $periodosService, UsersService $userService)
    {
        $this->periodosService = $periodosService;
        $this->userService = $userService;
    }

    public function formCitasByTeacher(Request $request)
    {


        return view("myforms.components_exp.citaciones_estudiante_calendario");
    }

    public function formCitasByStudent(Request $request)
    {

        $docentes = $this->userService->getDocentes();


        return view("myforms.components_exp.citaciones_docente_calendario", compact('docentes'));
    }

    public function searchCitasForCalendar(Request $request)
    {
        $events = CitacionEstudiantes::with('asignacion')
            ->where([
                "docidnumber" => auth()->user()->idnumber
            ])
            ->get()
            ->map(function ($citacion) {
                $hora = $this->parseHora($citacion->hora);
                return [
                    'title' => $citacion->asignacion->estudiante->name . " " . $citacion->asignacion->estudiante->lastname . "-" . $citacion->asignacion->asigexp_id ?? 'Sin nombre', // Nombre desde 'exp'
                    'start' => $citacion->fecha_corta,   // Fecha + Hora
                    'end' => $citacion->fecha_corta,                            // Opcional si hay una hora final
                    'motivo' => $citacion->motivo,                  // Campo adicional
                    'docente' => $citacion->docidnumber,
                    'fecha_larga' => $citacion->fecha . " " . $hora,          // Campo adicional
                ];
            });

        return response()->json($events);
    }

    public function searchCitasOfDay(Request $request)
    {
        $events = CitacionEstudiantes::with('asignacion')
            ->where([
                "docidnumber" => auth()->user()->idnumber
            ])
            ->whereDate("fecha_corta", Carbon::now())
            ->get()
            ->map(function ($citacion) {
                $hora = $this->parseHora($citacion->hora);
                return [
                    'title' => $citacion->asignacion->estudiante->name . " " . $citacion->asignacion->estudiante->lastname . "-" . $citacion->asignacion->asigexp_id ?? 'Sin nombre', // Nombre desde 'exp'
                    'start' => $citacion->fecha_corta,   // Fecha + Hora
                    'end' => $citacion->fecha_corta,                            // Opcional si hay una hora final
                    'motivo' => $citacion->motivo,                  // Campo adicional
                    'docente' => $citacion->docidnumber,
                    'fecha_larga' => $citacion->fecha . " " . $hora,          // Campo adicional
                ];
            });

        return response()->json($events);
    }



    public function searchTurnTeachers(Request $request)
    {
        // ============================================================
        // PERIODO ACTIVO
        // ============================================================
        $periodo_act = $this->periodosService->getPeriodoActivo();

        if (!$periodo_act) {
            return response()->json([
                'errors' => 'No hay un periodo activo actualmente.'
            ], 400);
        }

        $request['fecha_inicial'] = $periodo_act->prdfecha_inicio;
        $request['fecha_final'] = $periodo_act->prdfecha_fin;

        $rangeStart = Carbon::parse(
            $request['fecha_inicial'] ?? Carbon::today()
        );

        $can_delete = true;

        if (
            currentUser()->hasRole('amatai') ||
            currentUser()->hasRole('estudiante')
        ) {
            $rangeStart = Carbon::now();
            $can_delete = false;
        }

        $rangeEndRequest = $request['fecha_final']
            ? Carbon::parse($request['fecha_final'])
            : Carbon::parse('2027-05-31');

        $maxEnd = Carbon::parse($request['fecha_final']);

        $rangeEnd = $rangeEndRequest->lte($maxEnd)
            ? $rangeEndRequest
            : $maxEnd;


        // ============================================================
        // DOCENTE
        // ============================================================
        $docenteId = $request->get('docente_id')
            ? $request->get('docente_id')
            : Auth::user()->idnumber;

        if (!$docenteId) {
            return response()->json([
                'errors' => 'No se proporcionó un docente válido.'
            ], 400);
        }

        $docente = User::where('idnumber', $docenteId)->first();

        if (!$docente) {
            return response()->json([
                'errors' => 'No se encontró el docente.'
            ], 400);
        }

        $min_atencion =
            $docente->min_atencion != null &&
            $docente->min_atencion != "" &&
            $docente->min_atencion < 40 &&
            $docente->min_atencion > 20
            ? $docente->min_atencion
            : 40;


        // ============================================================
        // HORARIOS DEL DOCENTE
        // ============================================================
        $horarios = TurnosDocente::where([
            "trnd_docidnumber" => $docenteId
        ])
            ->where("trndid_periodo", $periodo_act->id)
            ->get();


        // ============================================================
        // TURNOS YA ASIGNADOS
        // ============================================================
        $turnosAsignados = TurnoEstudianteDocente::where(
            'docente_id',
            $docente->id
        )
            ->whereBetween(
                'fecha',
                [
                    $rangeStart->toDateString(),
                    $rangeEnd->toDateString()
                ]
            )
            ->get();


        // ============================================================
        // INDEXAR TURNOS ASIGNADOS
        // ============================================================
        $indexAsignados = [];

        foreach ($turnosAsignados as $t) {

            $key =
                $t->fecha .
                '|' .
                Carbon::parse($t->hora_inicio)->format('H:i:s');

            $indexAsignados[$key] = $t;
        }


        // ============================================================
        // DÍAS
        // ============================================================
        $mapDiasNormalized = [
            'lunes' => 1,
            'martes' => 2,
            'miercoles' => 3,
            'miércoles' => 3,
            'jueves' => 4,
            'viernes' => 5,
            'sabado' => 6,
            'sábado' => 6,
            'domingo' => 7
        ];


        // ============================================================
        // RECESOS
        // ============================================================
        $recesos = [
            [
                'inicio' => '10:00:00',
                'fin' => '10:30:00'
            ],
            [
                'inicio' => '16:00:00',
                'fin' => '16:30:00'
            ]
        ];


        // ============================================================
        // FUNCIÓN PARA SABER SI UNA HORA ESTÁ EN RECESO
        // ============================================================
        $obtenerReceso = function ($fecha, $inicio, $fin) use ($recesos) {

            foreach ($recesos as $receso) {

                $recesoInicio = $fecha->copy()
                    ->setTimeFromTimeString($receso['inicio']);

                $recesoFin = $fecha->copy()
                    ->setTimeFromTimeString($receso['fin']);


                // El bloque está completamente dentro del receso
                if (
                    $inicio->gte($recesoInicio) &&
                    $inicio->lt($recesoFin)
                ) {
                    return [
                        'inicio' => $recesoInicio,
                        'fin' => $recesoFin
                    ];
                }


                // El bloque atraviesa el inicio del receso
                if (
                    $inicio->lt($recesoInicio) &&
                    $fin->gt($recesoInicio)
                ) {
                    return [
                        'inicio' => $recesoInicio,
                        'fin' => $recesoFin
                    ];
                }
            }

            return null;
        };


        // ============================================================
        // EVENTOS
        // ============================================================
        $eventos = [];


        // ============================================================
        // RECORRER HORARIOS
        // ============================================================
        foreach ($horarios as $horario) {

            $diaRaw = trim($horario->trnd_dia ?? '');

            $diaNorm = \Str::lower(
                $this->removeAccents($diaRaw)
            );

            if (!isset($mapDiasNormalized[$diaNorm])) {
                continue;
            }

            $isoDia = $mapDiasNormalized[$diaNorm];


            // ========================================================
            // ENCONTRAR PRIMER DÍA DEL HORARIO
            // ========================================================
            $first = $rangeStart->copy();

            $diff = (
                $isoDia -
                $first->isoWeekday() +
                7
            ) % 7;

            if ($diff !== 0) {
                $first->addDays($diff);
            }


            // ========================================================
            // RECORRER SEMANAS
            // ========================================================
            for (
                $current = $first->copy();
                $current->lte($rangeEnd);
                $current->addWeek()
            ) {

                $horaInicio = Carbon::parse(
                    $horario->trnd_hora_inicio
                );

                $horaFin = Carbon::parse(
                    $horario->trnd_hora_fin
                );


                $bloqueInicio = $current->copy()->setTime(
                    $horaInicio->hour,
                    $horaInicio->minute,
                    $horaInicio->second
                );

                $bloqueFinToCompare = $current->copy()->setTime(
                    $horaFin->hour,
                    $horaFin->minute,
                    $horaFin->second
                );


                $bloquesDelDia = [];

                $restar = 0;


                // ====================================================
                // GENERAR BLOQUES
                // ====================================================
                while (
                    $bloqueInicio->lt($bloqueFinToCompare)
                ) {

                    $can_delete = true;


                    // =================================================
                    // SI YA PASÓ LA HORA ACTUAL
                    // =================================================
                    if (
                        currentUser()->hasRole('amatai') ||
                        currentUser()->hasRole('estudiante')
                    ) {

                        $now = Carbon::now();

                        if (
                            $bloqueInicio->isSameDay($now) &&
                            $bloqueInicio->lt($now)
                        ) {

                            $can_delete = false;

                            $bloqueInicio = $bloqueInicio->copy()
                                ->addMinutes($min_atencion);

                            continue;
                        }
                    }


                    // =================================================
                    // SI ESTAMOS DENTRO DE UN RECESO
                    // =================================================
                    $recesoActual = null;

                    foreach ($recesos as $receso) {

                        $recesoInicio = $bloqueInicio->copy()
                            ->setTimeFromTimeString(
                                $receso['inicio']
                            );

                        $recesoFin = $bloqueInicio->copy()
                            ->setTimeFromTimeString(
                                $receso['fin']
                            );


                        if (
                            $bloqueInicio->gte($recesoInicio) &&
                            $bloqueInicio->lt($recesoFin)
                        ) {

                            $recesoActual = [
                                'inicio' => $recesoInicio,
                                'fin' => $recesoFin
                            ];

                            break;
                        }
                    }


                    // =================================================
                    // SALTAR TODO EL RECESO
                    // =================================================
                    if ($recesoActual) {

                        $bloqueInicio = $recesoActual['fin']->copy();

                        continue;
                    }


                    // =================================================
                    // CALCULAR FIN DEL TURNO
                    // =================================================
                    $bloqueTo = $bloqueInicio->copy()
                        ->addMinutes($min_atencion);


                    if (
                        $bloqueTo->gt($bloqueFinToCompare)
                    ) {

                        $bloqueTo =
                            $bloqueFinToCompare->copy();
                    }


                    // =================================================
                    // VERIFICAR SI EL BLOQUE ATRAVIESA UN RECESO
                    // =================================================
                    $recesoAtravesado = null;

                    foreach ($recesos as $receso) {

                        $recesoInicio = $bloqueInicio->copy()
                            ->setTimeFromTimeString(
                                $receso['inicio']
                            );

                        $recesoFin = $bloqueInicio->copy()
                            ->setTimeFromTimeString(
                                $receso['fin']
                            );


                        // Ejemplo:
                        // 09:40 - 10:20
                        //
                        // Se convierte en:
                        // 09:40 - 10:00
                        //
                        // Luego continúa desde 10:30.

                        if (
                            $bloqueInicio->lt($recesoInicio) &&
                            $bloqueTo->gt($recesoInicio)
                        ) {

                            $recesoAtravesado = [
                                'inicio' => $recesoInicio,
                                'fin' => $recesoFin
                            ];

                            break;
                        }
                    }


                    // =================================================
                    // SI ATRAVIESA EL RECESO
                    // =================================================
                    if ($recesoAtravesado) {

                        /*
                     * Si ya existe un bloque antes del receso,
                     * lo terminamos exactamente a las 10:00 o 16:00.
                     */
                        $bloqueTo =
                            $recesoAtravesado['inicio']->copy();
                    }


                    // =================================================
                    // BUSCAR TURNO ASIGNADO
                    // =================================================
                    $key =
                        $bloqueInicio->toDateString() .
                        '|' .
                        $bloqueInicio->format('H:i:s');

                    $asignado =
                        $indexAsignados[$key] ?? null;


                    // =================================================
                    // GUARDAR BLOQUE DEL DÍA
                    // =================================================
                    $bloquesDelDia[] = [
                        'inicio' => $bloqueInicio->copy(),
                        'fin' => $bloqueTo->copy(),
                        'asignado' => $asignado,
                    ];


                    // =================================================
                    // ESTADO
                    // =================================================
                    $status = 'libre';

                    $motivo = 'Disponible';

                    $cld = '#CCCCCC';


                    // =================================================
                    // BLOQUES MUY PEQUEÑOS
                    // =================================================
                    if (
                        $bloqueTo->diffInMinutes(
                            $bloqueInicio
                        ) <= 10
                    ) {

                        $status = 'skip';

                        $motivo = 'No disponible';

                        $cld = '#727272ff';

                        $restar++;
                    }


                    // =================================================
                    // EVENTO
                    // =================================================
                    $eventos[] = [

                        'title' => $asignado
                            ? (
                                $asignado->estado->ref_nombre .
                                ':<br>' .
                                (
                                    $asignado->estudiante->name .
                                    " " .
                                    $asignado->estudiante->lastname
                                    ?? 'Asignado'
                                )
                            )
                            : $motivo,

                        'start' =>
                        $bloqueInicio->format(
                            'Y-m-d\TH:i:s'
                        ),

                        'end' =>
                        $bloqueTo->format(
                            'Y-m-d\TH:i:s'
                        ),

                        'color' =>
                        $asignado
                            ? $asignado->estado->color
                            : $cld,

                        'estado' =>
                        $asignado
                            ? $asignado->estado_id
                            : $status,

                        'motivo' =>
                        $asignado
                            ? $asignado->motivo
                            : $motivo,

                        'tipo' => "normal",

                        'docente' => $docenteId,

                        'docente_nombre' =>
                        $docente
                            ? (
                                $docente->name .
                                ' ' .
                                $docente->lastname
                            )
                            : 'Desconocido',

                        'turno_id' =>
                        $asignado
                            ? $asignado->id
                            : null,

                        'motivo_txt' =>
                        $asignado
                            ? $asignado->motivo
                            : '',

                        'fecha_larga' =>
                        getLongDateWithHour(
                            $bloqueInicio
                        ),

                        'role_user' =>
                        currentUser()->roles[0]->name,

                        'have_childs' =>
                        count(
                            $asignado
                                ? $asignado->childs
                                : []
                        ) > 0
                            ? true
                            : false,

                        'can_delete' =>
                        $asignado
                            ? (
                                $asignado->estudiante_id ==
                                auth()->user()->id
                                &&
                                $can_delete
                            )
                            : false
                    ];


                    // =================================================
                    // AVANZAR AL SIGUIENTE BLOQUE
                    // =================================================
                    $bloqueInicio =
                        $bloqueTo->copy();


                    // =================================================
                    // SI TERMINAMOS JUSTO EN EL INICIO DEL RECESO
                    // SALTAMOS LOS 30 MINUTOS
                    // =================================================
                    foreach ($recesos as $receso) {

                        $recesoInicio = $bloqueInicio->copy()
                            ->setTimeFromTimeString(
                                $receso['inicio']
                            );

                        $recesoFin = $bloqueInicio->copy()
                            ->setTimeFromTimeString(
                                $receso['fin']
                            );


                        if (
                            $bloqueInicio->equalTo(
                                $recesoInicio
                            )
                        ) {

                            $bloqueInicio =
                                $recesoFin->copy();

                            break;
                        }
                    }
                }


                // ========================================================
                // SI TODOS LOS BLOQUES ESTÁN OCUPADOS
                // AGREGAR EXTRAS
                // ========================================================
                $totalBloques =
                    count($bloquesDelDia);

                $ocupados =
                    collect($bloquesDelDia)
                    ->filter(
                        fn($bloque) =>
                        !is_null(
                            $bloque['asignado']
                        )
                    )
                    ->count();


                if (
                    $totalBloques > 0 &&
                    ($ocupados + $restar) === $totalBloques
                ) {

                    $extraInicio =
                        $bloquesDelDia[$totalBloques - 1]['fin']->copy();


                    // =================================================
                    // HORARIO EXTRA
                    // =================================================
                    $extH = false;


                    if ($extraInicio->hour >= 18) {

                        $extH = true;

                        $extraInicio->setTime(
                            18,
                            0,
                            0
                        );
                    } elseif (
                        $extraInicio->hour >= 12 &&
                        $extraInicio->hour < 14
                    ) {

                        $extraInicio->setTime(
                            12,
                            0,
                            0
                        );

                        $extH = true;
                    }


                    if ($extH) {

                        for ($i = 0; $i < 2; $i++) {

                            $extraFin =
                                $extraInicio->copy()
                                ->addMinutes(
                                    $min_atencion
                                );


                            // =================================================
                            // NO CREAR EXTRA EN RECESO
                            // =================================================
                            $esRecesoExtra = false;

                            foreach ($recesos as $receso) {

                                $recesoInicio =
                                    $extraInicio->copy()
                                    ->setTimeFromTimeString(
                                        $receso['inicio']
                                    );

                                $recesoFin =
                                    $extraInicio->copy()
                                    ->setTimeFromTimeString(
                                        $receso['fin']
                                    );


                                if (
                                    $extraInicio->lt(
                                        $recesoFin
                                    ) &&
                                    $extraFin->gt(
                                        $recesoInicio
                                    )
                                ) {

                                    $esRecesoExtra = true;

                                    $extraInicio =
                                        $recesoFin->copy();

                                    break;
                                }
                            }


                            if ($esRecesoExtra) {
                                continue;
                            }


                            // =================================================
                            // BUSCAR ASIGNADO
                            // =================================================
                            $key =
                                $extraInicio->toDateString() .
                                '|' .
                                $extraInicio->format('H:i:s');

                            $asignado =
                                $indexAsignados[$key] ?? null;


                            $fechaBuscada =
                                $extraFin->format('H:i:s');

                            $diaBuscado =
                                $extraFin->translatedFormat('l');


                            // =================================================
                            // VALIDAR HORARIO
                            // =================================================
                            $tieneHorario =
                                $horarios->contains(
                                    function ($horario)
                                    use (
                                        $diaBuscado,
                                        $fechaBuscada
                                    ) {

                                        return
                                            \Str::lower(
                                                $horario->trnd_dia
                                            ) ===
                                            \Str::lower(
                                                $diaBuscado
                                            )
                                            &&
                                            $fechaBuscada >=
                                            $horario->trnd_hora_inicio
                                            &&
                                            $fechaBuscada <=
                                            $horario->trnd_hora_fin;
                                    }
                                );


                            // =================================================
                            // VERIFICAR DUPLICADO
                            // =================================================
                            $existe =
                                collect($eventos)
                                ->contains(
                                    function ($ev)
                                    use (
                                        $extraInicio,
                                        $extraFin
                                    ) {

                                        return
                                            $ev['start'] ===
                                            $extraInicio->format(
                                                'Y-m-d\TH:i:s'
                                            )
                                            &&
                                            $ev['end'] ===
                                            $extraFin->format(
                                                'Y-m-d\TH:i:s'
                                            );
                                    }
                                );


                            if (
                                !$tieneHorario &&
                                !$existe
                            ) {

                                $hora =
                                    Carbon::createFromTimeString(
                                        $extraInicio
                                    )->format("g:i A");


                                if ($extH) {

                                    $hora =
                                        Carbon::createFromTimeString(
                                            $extraInicio
                                        )
                                        ->subHour()
                                        ->format("g:i A");
                                }


                                // =================================================
                                // COLOR
                                // =================================================
                                $color = '#ffee00ff';


                                if ($asignado) {

                                    if (
                                        $asignado->estado_id == 260
                                    ) {

                                        $color =
                                            $asignado->estado->color;
                                    } elseif (
                                        !empty($asignado->estado->color)
                                    ) {

                                        $color =
                                            $asignado->estado->color;
                                    }
                                }


                                // =================================================
                                // EVENTO EXTRA
                                // =================================================
                                $eventos[] = [

                                    'title' =>
                                    $asignado
                                        ? (
                                            $asignado
                                            ->estado
                                            ->ref_nombre .
                                            ':<br>' .
                                            (
                                                $asignado
                                                ->estudiante
                                                ->name .
                                                ' ' .
                                                $asignado
                                                ->estudiante
                                                ->lastname
                                            )
                                        )
                                        : 'Disponible',

                                    'start' =>
                                    $extraInicio->format(
                                        'Y-m-d\TH:i:s'
                                    ),

                                    'end' =>
                                    $extraFin->format(
                                        'Y-m-d\TH:i:s'
                                    ),

                                    'color' => $color,

                                    'estado' =>
                                    $asignado
                                        ? $asignado->estado_id
                                        : 'libre',

                                    'tipo' => 'extra',

                                    'docente' => $docenteId,

                                    'docente_nombre' =>
                                    $docente
                                        ? (
                                            $docente->name .
                                            ' ' .
                                            $docente->lastname
                                        )
                                        : 'Desconocido',

                                    'motivo' =>
                                    'Turno adicional',

                                    'fecha_larga' =>
                                    getLongDate(
                                        $extraInicio
                                    ) .
                                        " a las " .
                                        $hora,

                                    'role_user' =>
                                    currentUser()
                                        ->roles[0]
                                        ->name,

                                    'motivo_txt' =>
                                    $asignado
                                        ? $asignado->motivo
                                        : '',

                                    'turno_id' =>
                                    $asignado
                                        ? $asignado->id
                                        : null,

                                    'have_childs' =>
                                    count(
                                        $asignado
                                            ? $asignado->childs
                                            : []
                                    ) > 0
                                        ? true
                                        : false,

                                    'can_delete' =>
                                    $asignado
                                        ? (
                                            $asignado
                                            ->estudiante_id ==
                                            auth()->user()->id
                                            &&
                                            $can_delete
                                        )
                                        : false
                                ];
                            }


                            // =================================================
                            // SIGUIENTE EXTRA
                            // =================================================
                            $extraInicio =
                                $extraFin->copy();
                        }
                    }
                }
            }
        }


        // ============================================================
        // TURNOS FUERA DEL HORARIO HABITUAL
        // ============================================================
        foreach ($turnosAsignados as $turno) {

            $key =
                $turno->fecha .
                '|' .
                Carbon::parse(
                    $turno->hora_inicio
                )->format('H:i:s');


            // ========================================================
            // VERIFICAR SI YA ESTÁ EN LOS EVENTOS
            // ========================================================
            $existe =
                collect($eventos)
                ->contains(
                    function ($ev) use ($turno) {

                        return
                            $ev['start'] ===
                            Carbon::parse(
                                $turno->fecha .
                                    ' ' .
                                    $turno->hora_inicio
                            )->format(
                                'Y-m-d\TH:i:s'
                            );
                    }
                );


            if (!$existe) {

                // ====================================================
                // TURNO FUERA DE HORARIO
                // ====================================================
                $fechaInicio =
                    Carbon::parse(
                        $turno->fecha .
                            ' ' .
                            $turno->hora_inicio
                    );

                $fechaFin =
                    Carbon::parse(
                        $turno->fecha .
                            ' ' .
                            $turno->hora_fin
                    );


                $eventos[] = [

                    'title' =>
                    $turno->estado_id !== 265
                        ? (
                            $turno
                            ->estado
                            ->ref_nombre .
                            ': <br>' .
                            (
                                $turno
                                ->estudiante
                                ->name .
                                ' ' .
                                $turno
                                ->estudiante
                                ->lastname
                            )
                        )
                        : 'Turno Disponible',

                    'start' =>
                    $fechaInicio->format(
                        'Y-m-d\TH:i:s'
                    ),

                    'end' =>
                    $fechaFin->format(
                        'Y-m-d\TH:i:s'
                    ),

                    'color' =>
                    $turno->estado->color,

                    'estado' =>
                    $turno->estado_id,

                    'tipo' =>
                    'fuera_horario',

                    'docente' =>
                    $docenteId,

                    'docente_nombre' =>
                    $docente
                        ? (
                            $docente->name .
                            ' ' .
                            $docente->lastname
                        )
                        : 'Desconocido',

                    'motivo' =>
                    $turno->estado_id == 265
                        ? ""
                        : $turno->motivo ??
                        'Reposición / fuera de horario',

                    'fecha_larga' =>
                    getLongDateWithHour(
                        $fechaInicio
                    ),

                    'role_user' =>
                    currentUser()
                        ->roles[0]
                        ->name,

                    'turno_id' =>
                    $turno->id,

                    'can_delete' => (
                        $turno->estudiante_id ==
                        auth()->user()->id
                    ) &&
                        $can_delete,
                ];
            }
        }


        // ============================================================
        // RESPUESTA
        // ============================================================
        return response()->json($eventos);
    }

    /**
     * Helper simple para normalizar acentos y eñes (usa mb_* y str_replace)
     */
    private function removeAccents(string $str): string
    {
        // normalizar tilde y ñ
        $normalize = [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'Á' => 'a',
            'É' => 'e',
            'Í' => 'i',
            'Ó' => 'o',
            'Ú' => 'u',
            'ñ' => 'n',
            'Ñ' => 'n'
        ];
        return strtr($str, $normalize);
    }



    function parseHora($hora)
    {
        // Verificar si la hora ya contiene "AM" o "PM"
        if (stripos($hora, 'AM') !== false || stripos($hora, 'PM') !== false) {
            // Si ya contiene "AM" o "PM", no hacer nada y devolverla tal cual
            return $hora;
        } else {
            // Si no contiene "AM" o "PM", convertir a formato 12 horas con AM/PM
            return Carbon::createFromFormat('H:i', $hora)->format('g:i A');
        }
        return $hora;
    }
}
