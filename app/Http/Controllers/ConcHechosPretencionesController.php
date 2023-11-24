<?php

namespace App\Http\Controllers;

use App\ConcHechosPretenciones;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConcHechosPretencionesController extends Controller
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
        //return response()->json($request->all());
        $request['estado_id'] = 1;
        $request['user_id'] = Auth::user()->id;
        $tipo_id = $request->input(('tipo_id'));
        if ($request->has('descripcion') and is_array($request->input('descripcion'))) {
            $data = $request->input('descripcion');
            foreach ($data as $key => $value) {
                if ($value != '') {
                    $request['descripcion'] = $value;
                    $conHP = ConcHechosPretenciones::create($request->all());
                    $conciliacion = $conHP->conciliacion;
                    $tipo_id = $conHP->tipo_id;
                }
            }
        } else if ($request->has('descripcion') and $request->input('descripcion') != '') {
            $conHP = ConcHechosPretenciones::create($request->all());
            $conciliacion = $conHP->conciliacion;
            $tipo_id = $conHP->tipo_id;
        }


        $view = view('myforms.conciliaciones.componentes.hechos_pretensiones_ajax', compact('conciliacion', 'tipo_id'))->render();

        $response = [
            'view' => $view,
            'tipo_id' => $tipo_id
        ];
        return response()->json($response);
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
        $conHP = ConcHechosPretenciones::find($id);

        return response()->json($conHP);
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
        $conHP = ConcHechosPretenciones::find($id);
        $conHP->fill($request->all());
        $conHP->save();

        $conciliacion = $conHP->conciliacion;
        $tipo_id = $conHP->tipo_id;
        $view = view('myforms.conciliaciones.componentes.hechos_pretensiones_ajax', compact('conciliacion', 'tipo_id'))->render();

        $reponse = [
            'view' => $view,
            'tipo_id' => $tipo_id
        ];
        return response()->json($reponse);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        $conHP = ConcHechosPretenciones::find($id);

        $conHP->delete();
        $conciliacion = $conHP->conciliacion;
        $tipo_id = $conHP->tipo_id;

        $view = view('myforms.conciliaciones.componentes.hechos_pretensiones_ajax', compact('conciliacion', 'tipo_id'))->render();

        $reponse = [
            'view' => $view,
            'tipo_id' => $tipo_id
        ];
        return response()->json($reponse);
    }
}
