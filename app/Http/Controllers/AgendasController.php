<?php

namespace App\Http\Controllers;

use App\CitacionEstudiantes;
use App\Services\ExpedientesService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendasController extends Controller
{
    
    public function __construct(
       

    ) {

        
    }

    public function formCitasForCalendar(Request $request)
    {


        return view("myforms.components_exp.citaciones_estudiante_calendario");
    }

    public function searchCitasForCalendar(Request $request)
    {
        $events = CitacionEstudiantes::with('asignacion')
        ->where([
            "docidnumber"=>auth()->user()->idnumber
        ])
        ->get()
        ->map(function ($citacion) {
            $hora = $this->parseHora($citacion->hora);
            return [
                'title' => $citacion->asignacion->estudiante->name." ".$citacion->asignacion->estudiante->lastname."-".$citacion->asignacion->asigexp_id ?? 'Sin nombre', // Nombre desde 'exp'
                'start' => $citacion->fecha_corta,   // Fecha + Hora
                'end' => $citacion->fecha_corta,                            // Opcional si hay una hora final
                'motivo' => $citacion->motivo,                  // Campo adicional
                'docente' => $citacion->docidnumber, 
                'fecha_larga' => $citacion->fecha." ". $hora,          // Campo adicional
            ];
        });

        return response()->json($events);
    }
    function parseHora($hora) {
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
