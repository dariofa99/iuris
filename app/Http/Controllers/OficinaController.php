<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Oficina;

class OficinaController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $oficinas = $this->getOficinas($request); 
//dd($oficinas);
        if($request->ajax()){
            return view('myforms.frm_oficinas_list_ajax',compact('oficinas'))->render();
        }      
        return view('myforms.frm_oficinas_list',compact('oficinas'));
    }
    private function getOficinas(Request $request){
        return $oficinas = Oficina::join('sede_oficinas as so','so.oficina_id','=','oficinas.id')
        ->where('so.sede_id',session('sede')->id_sede)
        ->where('ubicacion','<>','sin_asignar')
        ->orderBy('oficinas.created_at','desc')->paginate(100);
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
       // return response()->json($request->all());
        $oficina = new Oficina($request->all());
        $oficina->save(); 
        if(session()->has('sede')){
            $oficina->sedes()->attach(session('sede')->id_sede);
        } 
        $oficinas = $this->getOficinas($request);     
        $view = view('myforms.frm_oficinas_list_ajax',compact('oficinas'))->render();
        
        return response()->json(['view'=>$view]);
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
        $oficina =  Oficina::find($id);
        $oficina->users;
         return response()->json($oficina);
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
        //return $request->all();
        if($request->ajax()){
        $oficina =  Oficina::find($id);
        $oficina->fill($request->all());
        $oficina->save();
       
        $oficinas = $this->getOficinas($request);  
        $view = view('myforms.frm_oficinas_list_ajax',compact('oficinas'))->render();
       
        
         
        return response()->json(['view'=>$view]);
    }
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $oficina =  Oficina::find($id);
        $oficina->delete();
        
        $this->getOficinas($request);  
        $view = view('myforms.frm_oficinas_list_ajax',compact('oficinas'))->render();

        return response()->json(['view'=>$view]);
    }

    public function ver($id)
    {
        $oficina =  Oficina::find($id);
       // $oficina->users;
       if(count(currentUser()->oficinas)>0 
       and currentUser()->oficinas()->first()->id == $id){
        return view('myforms.frm_oficinas_ver',compact('oficina')); 
       }
       return view('errors.error'); 
        
    }

    public function deleteUser(Request $request)
    {
        //return response()->json(['view'=>$view]);
        $oficina =  Oficina::find($request->oficina_id);
       $user = $oficina->users()->where('user_id',$request->user_id)->first();
       if($user){
           $user->pivot->delete();
       }
       $oficina->users;
       return response()->json($oficina);
       if(count(currentUser()->oficinas)>0 
       and currentUser()->oficinas()->first()->id == $id){
        return view('myforms.frm_oficinas_ver',compact('oficina')); 
       }
       return view('errors.error'); 
        
    }

    public function asigNota(Request $request)
    {
        $oficina =  Oficina::find($request->oficina_id);
        //return response()->json(['oficina'=>$request->all()]);
        $data = [
            //'ntaaplicacion'=>$request->ntaaplicacion,
            'ntaconocimiento'=>$request->ntaconocimiento,
            //'ntaetica'=>$request->ntaetica,
            'ntaconcepto'=>$request->ntaconcepto,
            'orgntsid'=>$request->orgntsid,
            'segid'=>$request->segid,
            'perid'=>$request->perid,
            'tpntid'=>$request->tpntid,
            'expidnumber'=>$request->expid,
            'estidnumber'=>$request->estidnumber,
            'extidnumber'=>auth()->user()->idnumber, 
            'tbl_org_id'=>$oficina->id,
          ];
        //$oficina->ntaconocimiento = $request->ntaconocimiento;

            
        $oficina->asignarNotas($data);
        return response()->json(['oficina'=>$request->all()]);
    }

}
