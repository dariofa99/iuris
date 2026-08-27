<?php

namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class CasosAbandonoExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    private $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Expediente',
            'Fecha asignación',
            'Estudiante',
            'Solicitante',
            'Estado',
            'Proceso',
            'Docente',
            //'Hechos',
            //'Pretensiones',
            'Última actuación',
            'Última redacción',
        ];
    }

    public function map($caso): array
    {
        return [
            $caso->expid,
            $caso->fecha_asig,
            $caso->estudiante,
            $caso->usuario_sol,
            $caso->estado,
            $caso->proceso,
            $caso->docente_as,
            //$caso->exphechos,
            //$caso->exprtaest,
            $caso->fecha_ultima_actuacion,
            $caso->fecha_redaccion,
        ];
    }

    public function title(): string
    {
        return 'Casos abandono';
    }
}