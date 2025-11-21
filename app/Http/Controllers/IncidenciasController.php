<?php

namespace App\Http\Controllers;

use App\AsignacionCaso;
use App\Incidencia;
use App\IncidenciaEstado;
use App\Jobs\ProcessSendNotificationGeneral;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\In;

class IncidenciasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $incidencias = Incidencia::with('asignaciones')->where(function ($query) use ($request) {
            if (!auth()->user()->hasRole('amatai')) {
                $query->where("user_id", auth()->user()->id);
            }
        })
            ->where(function ($query) use ($request) {
                if ($request->has('expid') && $request->get('expid') != "") {
                    $query->whereHas('asignaciones', function ($q) use ($request) {
                        $q->where('asigexp_id', 'like', '%' . $request->get('expid') . '%');
                    });
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        //  dd($incidencias);
        return view("myforms.incidencias.admin", compact("incidencias"));
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

        $incidencia = Incidencia::create([
            'motivo' => $request->motivo,
            'estado_id' => 272,
            "categoria_id" => $request->categoria_id,
            'user_id' => auth()->user()->id,
            //'asig_caso_id' => $request->id_asig
        ]);





        $url = null;

        if ($request->has('id_asig') && $request->id_asig != null) {
            $incidencia->asignaciones()->attach($request->id_asig);
            $asignacion = AsignacionCaso::find($request->id_asig);
            $expediente = $asignacion->expediente;
            $url = url("/expedientes/{$expediente->expid}/edit");
        }


        $incidenciaEs = IncidenciaEstado::create([
            'incidencia_id' => $incidencia->id,
            'estado_id' => $incidencia->estado_id,
            'motivo' => $request->motivo,
            'user_id' => auth()->user()->id
        ]);

        if ($request->hasFile('archivo')) {
            $file = $incidenciaEs->uploadFile($request->file('archivo'), '/incidencias_' . $incidenciaEs->id);
            $incidenciaEs->files()->attach(
                $file,
                [
                    'incidencia_id' => $incidenciaEs->id,
                    'user_id' => currentUser()->id,
                    'type_status_id' => 1
                ]
            );
        }

        $destinatarios = [
            'darioj99@gmail.com',
            'amatai.ingeinfo@gmail.com',
        ];
        $concepto = $request->motivo;
        $user_created = Auth::user()->name . ' ' . Auth::user()->lastname;
        $subject = 'Se ha creado una nueva incidencia';

        ProcessSendNotificationGeneral::dispatch($destinatarios, $concepto, $user_created, $subject, $url)
            ->onConnection('database')->onQueue('emails');

        return response()->json($incidencia);
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
        $incidencia = Incidencia::where('asig_caso_id', $id)->get();
    }

    public function getByAsigCaso($id)
    {
        /* $incidencias = Incidencia::with(['user', 'categoria', 'estados', 'estado','expedientes'])
       // ->where('asig_caso_id', $id)
            ->where(function ($query) {
                if (!auth()->user()->hasRole('amatai')) {
                    $query->where("user_id", auth()->user()->id);
                }
            })
            ->get();  */

        $asignacion = AsignacionCaso::find($id);
        $incidencias = $asignacion->incidencias()->with(['user', 'categoria', 'estados', 'estado'])
            ->where(function ($query) {
                if (!auth()->user()->hasRole('amatai')) {
                    $query->where("user_id", auth()->user()->id);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $view = view('myforms.incidencias.admin_ajax', compact('incidencias'))->render();

        return response()->json([
            'html' => $view,
            'incidencias' => $incidencias
        ]);
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

        // return response()->json($request->all());

        if ($request->has('is_update') and $request->get('is_update') == "true") {

            $incidenciaE = IncidenciaEstado::find($request->hestado_id);
            $incidenciaE->update([
                'motivo' => $request->motivo
            ]);
            return response()->json($incidenciaE);
        }

        $incidencia = Incidencia::find($id);
        $incidencia->update([
            'estado_id' => $request->estado_id
        ]);
        $incidenciaEs = IncidenciaEstado::create([
            'incidencia_id' => $incidencia->id,
            'estado_id' => $incidencia->estado_id,
            'motivo' => $request->motivo,
            'user_id' => auth()->user()->id
        ]);

        $url = null;
        if ($request->has('id_asig')) {
            $asignacion = AsignacionCaso::find($request->id_asig);
            $expediente = $asignacion->expediente;
            $url = url("/expedientes/{$expediente->expid}/edit");
        }

        $subject = 'Se ha actualizado una incidencia';
        $concepto = $request->motivo;
        $user_created = Auth::user()->name . ' ' . Auth::user()->lastname;
        if ($request->estado_id == 272) {
            $destinatarios = [
                'darioj99@gmail.com',
                'amatai.ingeinfo@gmail.com',
            ];
            $subject = 'Se ha solicitado la revisión de una incidencia';
        } else {
            $user = $incidencia->user;
            $destinatarios = [$user->email];
        }


        ProcessSendNotificationGeneral::dispatch($destinatarios, $concepto, $user_created, $subject, $url)
            ->onConnection('database')->onQueue('emails');

        return response()->json($incidencia);
    }

    public function addStatus(Request $request, $id)
    {
        $incidencia = IncidenciaEstado::find($id);
        $incidencia->update([
            'estado_id' => $request->estado_id
        ]);
        $incidenciaEs = IncidenciaEstado::create([
            'incidencia_id' => $incidencia->id,
            'estado_id' => $incidencia->estado_id,
            'motivo' => $request->motivo,
            'user_id' => auth()->user()->id
        ]);

        return response()->json($incidencia);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
