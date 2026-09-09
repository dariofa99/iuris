<?php

namespace App\Repositories;

use App\Actuacion;


class ActuacionRepository
{
    public function obtenerPorDocenteYPeriodo(
        $docidnumber,
        $expedientes,
        $inicio,
        $fin
    ) {
        return Actuacion::query()
        ->whereIn('actexpid', $expedientes)
        ->where(function ($query) use ($docidnumber, $inicio, $fin) {
            $query->where(function ($q) use ($docidnumber, $inicio, $fin) {
                $q->where('actusercreated', $docidnumber)
                ->whereBetween('created_at', [$inicio, $fin]);
            })
        ->orWhere(function ($q) use ($docidnumber, $inicio, $fin) {
                $q->where('actuserupdated', $docidnumber)
                ->where('actdocidnumber', $docidnumber)
                ->whereBetween('updated_at', [$inicio, $fin]);
            });
    })
    ->get();
    }
}