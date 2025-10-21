<?php

namespace App\Http\Controllers;

use App\Notifications\SendMailAndNotificationGeneral;
use App\TurnoEstudianteDocente;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            /* return response()->json([
                'success' => false,
                'message' => 'Ya tiene un turno pendiente con el docente.'
            ]);  */
        }

        $turno = TurnoEstudianteDocente::create(
            [
                'docente_id' => $request->input('docente_id'),
                'estudiante_id' => Auth::user()->id,
                'fecha' => $request->input('fecha'),
                'hora_inicio' => $request->input('hora_inicio'),
                'hora_fin' => $request->input('hora_fin'),
                'estado_id' => 260,
                // 'observacion' => "SIN MOTIVO",
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

        $turno = TurnoEstudianteDocente::find($id);
        if ($turno) {
            $turno->estado_id = $request->estado_id;
            if ($turno->estado_id != 261) {
                $concepto = "Su solicitud de turno ha sido <b>{$turno->estado->ref_nombre}</b>.";
                $user_created = Auth::user()->name . " " . Auth::user()->lastname;
                $subject = "Solicitud de turno";
                $user = $turno->estudiante;
                $extH = false;
                $hora_ = Carbon::createFromTimeString($turno->hora_inicio);
                if ($hora_->hour >= 18 || ($hora_->hour >= 12 && $hora_->hour < 14)) {
                    $extH = true;
                }
                $hora = Carbon::createFromTimeString($turno->hora_inicio)->format("g:i A");
                if ($extH) $hora = Carbon::createFromTimeString($turno->hora_inicio)->subHour()->format("g:i A");
                if ($turno->estado_id == 262) {
                    $datos = "Puede acercarse en el horario indicado: <b>" . getSmallDate($turno->fecha) . " - " . $hora . "</b>.";
                    $concepto .= "<br>" . $datos;
                }
                if ($turno->estado_id == 263) {
                    $datos = "" . getSmallDate($turno->fecha) . " - " . $hora . ".";
                    $concepto .= "<br>" . $datos;
                }
                $user->notify(new SendMailAndNotificationGeneral($concepto, $user_created, $subject));
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
        $turno->delete();
        return response()->json([
            'success' => true,
            'message' => "Eliminado con éxito"
        ]);
    }
}
