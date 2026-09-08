<?php

namespace App\Repositories;

use App\CitacionEstudiantes;

class CitacionEstudianteRepository
{
    public function obtenerPorDocenteYPeriodo(
        $docidnumber,
        $expedientes,
        $inicio,
        $fin
    ) {
        return CitacionEstudiantes::query()
            ->where('docidnumber', $docidnumber)
            ->whereBetween('created_at', [$inicio, $fin])
            ->whereHas('asignacion', function ($query) use ($expedientes) {
                $query->whereIn('asigexp_id', $expedientes);
            })
            ->with('asignacion')
            ->get();
    }
}