<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sede;
use Auth;
use Illuminate\Support\Facades\DB;

class SedesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $sedes  = $this->getSedes($request);
       return view("myforms.sedes.index",compact('sedes'));
    }

    private function getSedes(Request $request){
        return $sedes = Sede::all();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // dd("");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sede = Sede::create($request->all());
        $sedes  = $this->getSedes($request);
        $view = view('myforms.sedes.partials.ajax.sedes_list',compact('sedes'))->render();
        $response = [
            'view'=>$view
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
        dd("sss");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $sede = Sede::find($id);
        return response()->json($sede); 
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
        $sede = Sede::find($id);
        $sede->fill($request->all());
        $sede->save();
        $sedes  = $this->getSedes($request);
        $view = view('myforms.sedes.partials.ajax.sedes_list',compact('sedes'))->render();
        $response = [
            'view'=>$view
        ];
        return response()->json($response); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $sede = Sede::find($id);       
        $sede->delete();
        $sedes  = $this->getSedes($request);
        $view = view('myforms.sedes.partials.ajax.sedes_list',compact('sedes'))->render();
        $response = [
            'view'=>$view
        ];
        return response()->json($response); 
    }

    public function selectSede(Request $request){

           $sedes = Sede::all();       






        if(count($sedes)>1){
            return view('myforms.frm_bienvenida',compact('sedes'));
        }elseif(count($sedes)<=0){
            session()->forget('sede');
        }
        if(auth()->user()->hasRole("solicitante")){
            return redirect()->to('oficina/solicitante');
        } 
        return view('myforms.frm_bienvenida',compact('sedes'));
    }

    public function changeSede(Request $request){
        $sede = Sede::find($request->sede_id);       
        session(["sede"=>$sede]);
        Auth::user()->sedes()->sync($sede->id);
        if(auth()->user()->hasRole("solicitante")){
            return redirect()->to('oficina/solicitante');
        }
     
        return redirect()->back();;
    }

}
