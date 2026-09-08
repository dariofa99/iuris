<?php

namespace App\Repositories;

use App\AsesoriaDocente;

class AsesoriaDocenteRepository
{
    public function obtenerPorDocenteYPeriodo(
        $docidnumber,
        $expedientes,
        $inicio,
        $fin
    ) {
        return AsesoriaDocente::query()
            ->where('docidnumber', $docidnumber)
            ->whereIn('expidnumber', $expedientes)
            ->whereBetween('created_at', [$inicio, $fin])
            ->get();
    }
}