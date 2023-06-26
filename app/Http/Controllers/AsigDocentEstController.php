<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asigna_docen_est;
use App\Periodo;
use DB;

class AsigDocentEstController extends Controller
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
        
        $periodo = Periodo::join('sede_periodos as sp','sp.periodo_id','=','periodo.id')
		->where('sp.sede_id',session('sede')->id_sede)
		->where('estado',true)->first();
        $asigest = new Asigna_docen_est();
        $asigest->asgedidnumberdocen=$request->docente_idnumber;
        $asigest->asgedidnumberest=$request->est_idnumber;
        $asigest->asgedidperiodo=$periodo->id;
        $asigest->asgedusercreated=currentUser()->idnumber;
        $asigest->asgeduserupdated=currentUser()->idnumber;
        $asigest->save();  
        $asigest->docente->asignaciones_docente;          

        return response()->json($asigest);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estudiantes = Asigna_docen_est::where('asgedidnumberdocen',$id)->get();

        $estudiantes->each(function($estudiantes){
            $estudiantes->estudiante->curso;
            //$estudiantes->estudiante_curso;
            $estudiantes->docente;
            $estudiantes->periodo;
        });

        return response()->json($estudiantes);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asigest = Asigna_docen_est::find($id);
        $asigest->delete();
        return response()->json($asigest);
    }

    public function confAsigDoc(){
        $asigest = DB::table('asigna_docent_ests')->update(['confirmado'=>true]);
        return response()->json($asigest);
    }
}
