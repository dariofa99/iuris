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
        // Rango pedido por FullCalendar (si viene en la query)
        $periodo_act = $this->periodosService->getPeriodoActivo();
        if (!$periodo_act) {
            return response()->json([]);
        }
        $request['fecha_inicial'] = $periodo_act->prdfecha_inicio;
        $request['fecha_final'] = $periodo_act->prdfecha_final;
        $rangeStart = Carbon::parse($request['fecha_inicial'] ?? Carbon::today());
        $can_delete = true;
        if (currentUser()->hasRole('amatai') || currentUser()->hasRole('estudiante')) {
            $rangeStart = Carbon::now();
            $can_delete = false;
        }

        // Forzamos un tope hasta 31 de mayo de 2026 si el end no viene o es mayor
        $rangeEndRequest = $request['fecha_final'] ? Carbon::parse($request['fecha_final']) : Carbon::parse('2026-05-31');
        $maxEnd = Carbon::parse('2026-05-31');
        $rangeEnd = $rangeEndRequest->lte($maxEnd) ? $rangeEndRequest : $maxEnd;

        $docenteId = $request->get('docente_id') ? $request->get('docente_id') : Auth::user()->idnumber;

        if (!$docenteId) {
            return response()->json([]); // sin docente, no hay eventos
        }
        $docente = User::where('idnumber', $docenteId)->first();
        $min_atencion = $docente->min_atencion != null && $docente->min_atencion != "" && $docente->min_atencion < 40 && $docente->min_atencion > 20 ? $docente->min_atencion : 40;
        $horarios = TurnosDocente::where([
            "trnd_docidnumber" => $docenteId
        ])
            ->where("trndid_periodo", $periodo_act->id)
            ->get();

        // Traemos todos los turnos ya asignados del docente entre el rango (una sola consulta)
        $turnosAsignados = TurnoEstudianteDocente::where('docente_id', $docente->id)
            ->whereBetween('fecha', [$rangeStart->toDateString(), $rangeEnd->toDateString()])
            ->get();

        // Indexamos por clave "YYYY-MM-DD|HH:MM:SS" para búsqueda rápida
        $indexAsignados = [];
        foreach ($turnosAsignados as $t) {
            $key = $t->fecha . '|' . (Carbon::parse($t->hora_inicio)->format('H:i:s'));
            $indexAsignados[$key] = $t;
        }

        // Mapa de nombres de día (normalizado) a desplazamiento desde inicio de semana
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

        $eventos = [];

        foreach ($horarios as $horario) {
            $diaRaw = trim($horario->trnd_dia ?? '');
            $diaNorm = \Str::lower($this->removeAccents($diaRaw));

            if (!isset($mapDiasNormalized[$diaNorm])) continue;
            $isoDia = $mapDiasNormalized[$diaNorm];

            $first = $rangeStart->copy();
            $diff = ($isoDia - $first->isoWeekday() + 7) % 7;
            if ($diff !== 0) $first->addDays($diff);

            for ($current = $first->copy(); $current->lte($rangeEnd); $current->addWeek()) {
                $horaInicio = Carbon::parse($horario->trnd_hora_inicio);
                $horaFin = Carbon::parse($horario->trnd_hora_fin);

                $bloqueInicio = $current->copy()->setTime($horaInicio->hour, $horaInicio->minute, $horaInicio->second);
                $bloqueFinToCompare = $current->copy()->setTime($horaFin->hour, $horaFin->minute, $horaFin->second);

                $bloquesDelDia = []; // almacenamos los turnos del día

                while ($bloqueInicio->lt($bloqueFinToCompare)) {
                    $can_delete = true;
                    $bloqueTo = $bloqueInicio->copy()->addMinutes($min_atencion);
                    if ($bloqueTo->gt($bloqueFinToCompare)) {
                        $bloqueTo = $bloqueFinToCompare->copy();
                    }

                    if (currentUser()->hasRole('amatai') || currentUser()->hasRole('estudiante')) {
                        $now = Carbon::now();
                        if ($bloqueInicio->isSameDay($now) && $bloqueInicio->lt($now)) {
                            $can_delete = false;
                            $bloqueInicio = $bloqueTo;
                            continue;
                        }
                    }

                    $key = $bloqueInicio->toDateString() . '|' . $bloqueInicio->format('H:i:s');
                    $asignado = $indexAsignados[$key] ?? null;

                    $bloquesDelDia[] = [
                        'inicio' => $bloqueInicio->copy(),
                        'fin' => $bloqueTo->copy(),
                        'asignado' => $asignado,
                    ];

                    $eventos[] = [
                        'title' => $asignado
                            ? ('Turno reservado por:<br>' . ($asignado->estudiante->name . " " . $asignado->estudiante->lastname ?? 'Asignado'))
                            : 'Disponible',
                        'start' => $bloqueInicio->format('Y-m-d\TH:i:s'),
                        'end' => $bloqueTo->format('Y-m-d\TH:i:s'),
                        'color' => $asignado ?  $asignado->estado->color : '#CCCCCC',
                        'estado' => $asignado ? $asignado->estado_id : 'libre',
                        'motivo' => $asignado ? $asignado->motivo : 'Disponible',
                        'tipo' => "normal",
                        'docente' => $docenteId,
                        'docente_nombre' => $docente ? ($docente->name . ' ' . $docente->lastname) : 'Desconocido',
                        'turno_id' => $asignado ? $asignado->id : null,
                        'motivo_txt' => $asignado ? $asignado->motivo : '',
                        'fecha_larga' => getLongDateWithHour($bloqueInicio),
                        'role_user' => currentUser()->roles[0]->name,
                        'can_delete' => $asignado ? ($asignado->estudiante_id == auth()->user()->id and $can_delete)  : false
                    ];

                    $bloqueInicio = $bloqueTo;
                }
                // Log::info($bloquesDelDia);

                // ✅ Si todos los bloques del día están ocupados → añadimos 2 extras
                $totalBloques = count($bloquesDelDia);
                $ocupados = collect($bloquesDelDia)
                    ->filter(fn($bloque) => !is_null($bloque['asignado']))
                    ->count();
                Log::info("Día {$current->toDateString()}: {$ocupados}/{$totalBloques} ocupados.");
                if ($totalBloques > 0 && $ocupados === $totalBloques) {
                    $extraInicio = $bloquesDelDia[$totalBloques - 1]['fin']->copy();
                    Log::info($extraInicio);
                    // 🔹 Si la hora es mayor o igual a 14 (2 PM)
                    $extH = false;
                    if ($extraInicio->hour >= 18) {
                        // Fíjala a las 18:00 (6 PM)
                        $extH = true;
                        $extraInicio->setTime(18, 0, 0);
                    } else if ($extraInicio->hour >= 12 && $extraInicio->hour < 14) {
                        // Si no, fíjala a las 12:00 (mediodía)
                        $extraInicio->setTime(12, 0, 0);
                        $extH = true;
                    }

                    if ($extH) {
                        for ($i = 0; $i < 2; $i++) {
                            $extraFin = $extraInicio->copy()->addMinutes($min_atencion);
                            $key = $extraInicio->toDateString() . '|' . $extraInicio->format('H:i:s');
                            $asignado = $indexAsignados[$key] ?? null;

                            $sig  = $bloquesDelDia[$i]["fin"];
                            Log::info("------------------");
                            $fechaBuscada = $extraFin->format('H:i:s');
                            $diaBuscado = $extraFin->translatedFormat('l');
                            Log::info($fechaBuscada);
                            $tieneHorario = $horarios->contains(function ($horario) use ($diaBuscado, $fechaBuscada) {
                                return \Str::lower($horario->trnd_dia) === $diaBuscado
                                    && $fechaBuscada >= $horario->trnd_hora_inicio
                                    && $fechaBuscada <= $horario->trnd_hora_fin;
                            });
                            Log::info($tieneHorario);
                            Log::info($diaBuscado);
                            $existe = collect($eventos)->contains(function ($ev) use ($extraInicio, $extraFin) {
                                return $ev['start'] === $extraInicio->format('Y-m-d\TH:i:s')
                                    && $ev['end'] === $extraFin->format('Y-m-d\TH:i:s');
                            });
                            if (!$tieneHorario && !$existe) {
                                $hora = Carbon::createFromTimeString($extraInicio)->format("g:i A");
                                if ($extH) $hora = Carbon::createFromTimeString($extraInicio)->subHour()->format("g:i A");
                                Log::info($hora);
                                Log::info("------------------");
                                $color = '#ffee00ff'; // valor por defecto

                                if ($asignado) {
                                    if ($asignado->estado_id == 260) {
                                        $color = '#9f02c7ff';
                                    } elseif (!empty($asignado->estado->color)) {
                                        $color = $asignado->estado->color;
                                    }
                                }
                                $eventos[] = [
                                    'title' =>  $asignado
                                        ? ('Turno reservado por:<br>' . ($asignado->estudiante->name . " " . $asignado->estudiante->lastname ?? 'Asignado'))
                                        : 'Disponible',
                                    'start' => $extraInicio->format('Y-m-d\TH:i:s'),
                                    'end' => $extraFin->format('Y-m-d\TH:i:s'),
                                    'color' => $color,
                                    'estado' => $asignado ? $asignado->estado_id : 'libre',
                                    'tipo' => 'extra',
                                    'docente' => $docenteId,
                                    'docente_nombre' => $docente ? ($docente->name . ' ' . $docente->lastname) : 'Desconocido',
                                    'motivo' => 'Turno adicional',
                                    'fecha_larga' => getLongDate($extraInicio) . " a las " . $hora,
                                    'role_user' => currentUser()->roles[0]->name,
                                    'motivo_txt' => $asignado ? $asignado->motivo : '',
                                    'turno_id' => $asignado ? $asignado->id : null,
                                    'can_delete' => $asignado ? ($asignado->estudiante_id == auth()->user()->id and $can_delete)  : false
                                ];
                            }
                            $extraInicio = $extraFin;
                        }
                    }
                }
            }
        }
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
