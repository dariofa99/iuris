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
        $request['estado_id'] = 1;
        $request['user_id'] = Auth::user()->id;
        $conHP = ConcHechosPretenciones::create($request->all());
        $conciliacion = $conHP->conciliacion;
        $tipo_id = $conHP->tipo_id;
        $view = view('myforms.conciliaciones.componentes.hechos_pretenciones_ajax',compact('conciliacion','tipo_id'))->render();
        
        $reponse = [
            'view'=>$view,
            'tipo_id'=>$tipo_id
        ];
        return response()->json($reponse);
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
        $view = view('myforms.conciliaciones.componentes.hechos_pretenciones_ajax',compact('conciliacion','tipo_id'))->render();
        
        $reponse = [
            'view'=>$view,
            'tipo_id'=>$tipo_id
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
       
        $view = view('myforms.conciliaciones.componentes.hechos_pretenciones_ajax',compact('conciliacion','tipo_id'))->render();
        
        $reponse = [
            'view'=>$view,
            'tipo_id'=>$tipo_id
        ];
        return response()->json($reponse);
    }
}
