<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ActuarDocenteService;
use Carbon\Carbon;
use App\Services\UsersService;

class ActuarDocenteController extends Controller
{
    protected ActuarDocenteService $service;
    protected UsersService $usersService;

    public function __construct(ActuarDocenteService $service, UsersService $usersService)
    {
        $this->service = $service;
        $this->usersService = $usersService;
    }

    public function consultar(Request $request)
    {



        if ($request->has('docidnumber') and ($request->docidnumber) != '') {
            $docente = $this->usersService->findWithFilter(['idnumber' => $request->docidnumber]);
        } else {
            $docente = $this->usersService->findWithFilter(['idnumber' => currentUser()->idnumber]);
        }

        $docentes = $this->usersService->getDocentes();

        if (!$request->has('fecha')) {
            return view('myforms.actuar_docente.index', [
                'resultado' => [
                    "expedientes" => collect(),
                ],
                'docentes' => $docentes,
                'docente' => $docente,
            ]);
        }
        /*  $request['docidnumber'] = $docente->idnumber;
        $request['fecha'] = Carbon::parse($request->fecha)->format('Y-m-d');
        $request['hora_inicio'] = Carbon::parse($request->hora_inicio)->format('H:i:s');
        $request['hora_fin'] = Carbon::parse($request->hora_fin)->format('H:i:s'); */

        $resultado = $this->service->consultar(
            $request->docidnumber,
            $request->fecha,
            $request->hora_inicio,
            $request->hora_fin
        );

        // dd($docentes);

        return view('myforms.actuar_docente.index', [
            'resultado' => $resultado,
            'docentes' => $docentes,
            'docente' => $docente,
        ]);
    }
}
