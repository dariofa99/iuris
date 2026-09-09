<?php

namespace App\Repositories;


use App\TurnosDocente as AppTurnosDocente;

class TurnosDocenteRepository
{
    public function obtenerPorPeriodo($periodoId)
    {
        return AppTurnosDocente::join(
                'users',
                'turnos_docentes.trnd_docidnumber',
                '=',
                'users.idnumber'
            )
            ->select(
                'turnos_docentes.id',
                'turnos_docentes.trnd_docidnumber',
                'turnos_docentes.trnd_dia',
                'turnos_docentes.trnd_hora_inicio',
                'turnos_docentes.trnd_hora_fin',
                'users.name',
                'users.lastname',
                'users.image'
            )
            ->where(
                'turnos_docentes.trndid_periodo',
                $periodoId
            )
            ->orderBy(
                'turnos_docentes.trnd_docidnumber',
                'DESC'
            )
            ->get();
    }
}