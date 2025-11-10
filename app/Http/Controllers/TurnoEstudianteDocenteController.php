<?php

namespace App\Http\Controllers;

use App\Notifications\SendMailAndNotificationGeneral;
use App\TurnoEstudianteDocente;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class TurnoEstudianteDocenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        /*  if($request->has('tipo_turno') and $request->input('tipo_turno') === 'extra'){
            return response()->json([
               'success' => false,
               'message' => "Los turnos extra no están habilitados en este momento"
           ]);
        }
         return response()->json([
            'success' => $request->all(),
            'message' => "Funcionalidad deshabilitada temporalmente"
        ]);  */




        $docente = User::where('idnumber', $request->input('docente_id'))->first();

        $request->merge(['docente_id' => $docente->id]);
        //comprobar que no exista un turno para el mismo docente en la misma fecha y hora_inicio
        $existingTurn = TurnoEstudianteDocente::where('docente_id', $request->input('docente_id'))
            ->where('fecha', $request->input('fecha'))
            ->where('hora_inicio', $request->input('hora_inicio'))
            ->first();
        if ($existingTurn) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe un turno para este docente en la misma fecha y hora de inicio.'
            ]);
        }

        $existingTurn = TurnoEstudianteDocente::where('docente_id', $request->input('docente_id'))
            ->whereDate('fecha', ">", Carbon::now())
            ->where('estudiante_id', auth()->user()->id)
            ->where('estado_id', 260)
            ->first();
        if ($existingTurn) {
             return response()->json([
                'success' => false,
                'message' => 'Ya tiene un turno pendiente con el docente.'
            ]); 
        }

        $turno = TurnoEstudianteDocente::create(
            [
                'docente_id' => $request->input('docente_id'),
                'estudiante_id' => Auth::user()->id,
                'fecha' => $request->input('fecha'),
                'hora_inicio' => $request->input('hora_inicio'),
                'hora_fin' => $request->input('hora_fin'),
                'estado_id' => ($request->has('tipo_turno') and $request->input('tipo_turno') === 'extra') ? 262 : 260,
                'motivo' => $request->input('motivo'),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Turno agendado con éxito.'
        ]);
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
        /* return response()->json([
            'success' => $request->all(),
            'message' => "Funcionalidad deshabilitada temporalmente"
        ]); */

        $turno = TurnoEstudianteDocente::find($id);
        if ($turno) {
            $user = $turno->estudiante;
            $turno->estado_id = $request->estado_id;
            $concepto = "Su solicitud de turno ha cambiado a <b>{$turno->estado->ref_nombre}</b>.";
            $user_created = Auth::user()->name . " " . Auth::user()->lastname;
            $subject = "Solicitud de turno";
            if ($turno->estado_id != 261) { //atendido
                if ($turno->estado_id == 260) { //Solicitado
                    /* $turno->fecha = $request->fecha;
                    $turno->hora_inicio = $request->hora_inicio;
                    $turno->hora_fin = $request->hora_fin; */

                    if (Auth::user()->hasRole('estudiante')) {
                        $turno->motivo = $request->motivo;
                        $turno->estudiante_id = Auth::user()->id;
                    }


                    $turno->save();
                    if ($turno->estado_id == 260 || $turno->estado_id == 263) $user->notify(new SendMailAndNotificationGeneral($concepto, $user_created, $subject));
                    return response()->json([
                        'success' => true,
                        'message' => "Agendado con éxito"
                    ]);
                } //aceptado o rechazado


                if ($turno->estado_id == 265) { //Solicitado
                    /* $turno->fecha = $request->fecha;
                    $turno->hora_inicio = $request->hora_inicio;
                    $turno->hora_fin = $request->hora_fin; */

                    /*  $child = $turno->parents()->first();
                    Log::info($child);
                    if ($child) {
                        $parent = TurnoEstudianteDocente::find($child->pivot->teparent_id);
                        $parent->estado_id = 260; //pendiente
                        $parent->save();
                    }

                    $parent = $turno->childs()->detach($child->pivot->techild_id); */


                    $turno->save();
                    if ($turno->estado_id == 260 || $turno->estado_id == 263) $user->notify(new SendMailAndNotificationGeneral($concepto, $user_created, $subject));
                    return response()->json([
                        'success' => true,
                        'message' => "Agendado con éxito"
                    ]);
                }

                $extH = false;
                $hora_ = Carbon::createFromTimeString($turno->hora_inicio);
                if ($hora_->hour >= 18 || ($hora_->hour >= 12 && $hora_->hour < 14)) {
                    $extH = true;
                }
                $hora = Carbon::createFromTimeString($turno->hora_inicio)->format("g:i A");
                if ($extH) $hora = Carbon::createFromTimeString($turno->hora_inicio)->subHour()->format("g:i A");
                if ($turno->estado_id == 260) {
                    $datos = "Puede acercarse en el horario indicado: <b>" . getSmallDate($turno->fecha) . " - " . $hora . "</b>.";
                    $concepto .= "<br>" . $datos;
                }
                if ($turno->estado_id == 263) {
                    $datos = "" . getSmallDate($turno->fecha) . " - " . $hora . ".";
                    $concepto .= "<br>" . $datos;
                }
                if ($turno->estado_id == 260 || $turno->estado_id == 263) $user->notify(new SendMailAndNotificationGeneral($concepto, $user_created, $subject));
            }
            $turno->save();
        } else {
            return response()->json([
                'success' => false,
                'message' => "Ups! Hubo un error, consulte con el administrador"
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => "Actualizado con éxito"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $turno = TurnoEstudianteDocente::find($id);
        if (!$turno) {
            return response()->json([
                'success' => false,
                'message' => "Ups! Hubo un error, consulte con el administrador"
            ]);
        }


        $child = $turno->parents()->first();
      
        if ($child) {
            $parent = TurnoEstudianteDocente::find($child->pivot->teparent_id);
            $parent->estado_id = 260; //pendiente
            $parent->save();
            //eliminar la relacion
            $parent = DB::table('turnos_estdoc_reprogramados')->where('techild_id', $child->pivot->techild_id)
            ->where('teparent_id', $child->pivot->teparent_id)->delete();
        }



        $turno->delete();
        return response()->json([
            'success' => $turno,
            'message' => "Eliminado con éxito"
        ]);
    }

    public function notificarTurnoEstudiante(Request $request)
    {

        $turno = TurnoEstudianteDocente::find($request->input('turno_id'));
        if (!$turno) {
            return response()->json([
                'success' => false,
                'message' => "Ups! Hubo un error, consulte con el administrador"
            ]);
        }

        $existingTurn = TurnoEstudianteDocente::where('docente_id', $turno->docente_id)
            ->where('fecha', $request->newfecha)
            ->where('hora_inicio', "<=", $request->newhoraInicio)
            ->where('hora_fin', '>', $request->newhoraInicio)
            ->first();
        if ($existingTurn) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe un turno en la misma fecha y hora de inicio.',
                'data' => $existingTurn,
            ]);
        }

        if ($request->input('tipo_turno') == "fuera_horario") {
            $turno->fecha = $request->input('newfecha');
            $turno->hora_inicio = $request->input('newhoraInicio');
            $turno->hora_fin = $request->input('newhoraFin');
            //$turno->estado_id = 260; //pendiente
            $turno->save();
        } else {
            $newTurno = TurnoEstudianteDocente::create(
                [
                    'docente_id' => $turno->docente_id,
                    'estudiante_id' => $turno->estudiante_id,
                    'fecha' => $request->input('newfecha'),
                    'hora_inicio' => $request->input('newhoraInicio'),
                    'hora_fin' => $request->input('newhoraFin'),
                    'estado_id' => 260,
                    'motivo' => $turno->motivo,
                ]
            );
            $turno->estado_id = 264; //reprogramado
            $turno->save();
            $parent = $turno->childs()->attach($newTurno->id, [
                'teparent_id' => $turno->id,
                'techild_id' => $newTurno->id
            ]);
        }
        $user_created = Auth::user()->name . " " . Auth::user()->lastname;
        $subject = "Recordatorio de turno agendado";
        $user = $turno->estudiante;
        // $hora = Carbon::createFromTimeString($turno->hora_inicio)->format("g:i A");
        $concepto = "";
        $concepto .= "<br>" . $request->input('motivo');

        $user->notify(new SendMailAndNotificationGeneral($concepto, $user_created, $subject));

        return response()->json([
            'success' => $turno,
            'message' => "Notificación enviada con éxito"
        ]);
    }
}
