<?php

namespace App\Http\Controllers;

use App\ConciliacionUserForm;
use App\ReferencesData;
use DB;
use Illuminate\Http\Request;
use App\ReferenceDataOptions;

class ReferencesDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *  
     * @return \Illuminate\Http\Response
     */
    public function index()  
    {
        $categories = $this->getCategories();
        return view('myforms.categories.index',compact('categories'));
    }

    private function getCategories(){
       return $categories = ReferencesData::orderBy('categories','asc')->get();
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
       //dd($request->all());
        $this->guardar($request);
        $categories = $this->getCategories();
        $view =  view('myforms.categories.partials.ajax.index',compact('categories'))->render();
        $response=[];
        $response['render_view'] = $view;
        return response()->json( $response);

    }

    public function storeFromReports(Request $request)
    {
       //dd($request->all());
        $this->guardar($request);
        $categories = $this->getCategories();
        $view =  view('myforms.categories.partials.ajax.index',compact('categories'))->render();
        $response=[];
        $response['render_view'] = $view;
        return response()->json( $response);

    }

   

    private function guardar(Request $request){
        $request['categories'] = $request->table;
        $referencia = ReferencesData::create($request->all());
 
        if($request->has('option_name')){
            foreach ($request->option_name as $key => $option) {
                $insert = DB::table("references_data_options")
                ->insert([ 
                    'value'=>$option,
                    'references_data_id'=>$referencia->id,
                    'active_other_input'=>$request->active_other_input[$key]
                ]);
            }
        }else{
            $insert = DB::table("references_data_options")
                ->insert([
                    'value'=>$request->name,
                    'references_data_id'=>$referencia->id,
                    'active_other_input'=>0
                ]);
        }
        if ($request->table=='conciliacion') {
            foreach ($request->parte as $key => $parte) {
                $insert = ConciliacionUserForm::create([
                    'parte'=>$parte,
                    'reference_data_id'=>$referencia->id,                    
                ]);
            }
           
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ReferencesData  $referencesData
     * @return \Illuminate\Http\Response
     */
    public function show(ReferencesData $referencesData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ReferencesData  $referencesData
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $referencia = ReferencesData::find($id);     
        $referencia->options; 
        $referencia->partes;
        return response()->json($referencia);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ReferencesData  $referencesData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {

           
           // return response()->json($items_deleted); 
            $request['categories'] = $request->table;
            $referencia = ReferencesData::find($id);
            $older_type_data = $referencia->type_data_id; 
            $referencia->fill($request->all());
            $referencia->save();
            $referencia->options;   
            $items_deleted = json_decode($request->items_deleted);
            if(count($items_deleted)>0){
                foreach ($items_deleted as $key => $item_d) {               
                    $option_o = ReferenceDataOptions::find($item_d->id)->delete();
                }
            }         
            if($request->has('option_name')){
                if($older_type_data == 168 || $older_type_data==173 || $older_type_data== 174){
                    $referencia->options()->delete();
                }
                foreach ($request->option_name as $key => $option) {
                    if($request->options_id[$key]!='null'){
                        $option_o = ReferenceDataOptions::find($request->options_id[$key]);                       
                        if($option_o){
                            $option_o->value = $option;
                            $option_o->active_other_input = $request->active_other_input[$key];
                            $option_o->save();
                        }
                    }else{ 
                        $insert = DB::table("references_data_options")
                        ->insert([
                            'value'=>$option,
                            'references_data_id'=>$referencia->id,
                            'active_other_input'=>$request->active_other_input[$key]
                        ]);
                    }
                   
                   
                }
            }elseif(!$request->has('option_name')){
                if($older_type_data != 168 && $older_type_data != 173 && $older_type_data != 174){
                    $referencia->options()->delete();
                    $insert = DB::table("references_data_options")
                    ->insert([
                        'value'=>$request->name,
                        'references_data_id'=>$referencia->id,
                        'active_other_input'=>0
                    ]);
                }else{
                    $insert = DB::table("references_data_options")
                    ->where("references_data_id",$referencia->id)
                    ->update([
                        'value'=>$request->display_name,
                        'references_data_id'=>$referencia->id,
                        'active_other_input'=>0
                    ]);  
                }            
            }
            if ($request->table=='conciliacion') {
                $delete = DB::table('conciliacion_user_form')
                ->where('reference_data_id',$referencia->id)->delete();
                foreach ($request->parte as $key => $parte) {
                    $insert = ConciliacionUserForm::create([
                        'parte'=>$parte,
                        'reference_data_id'=>$referencia->id,                    
                    ]);
                }
               
            }

            $categories = $this->getCategories();
            $view =  view('myforms.categories.partials.ajax.index',compact('categories'))->render();
            $response=[];
            $response['render_view'] = $view;
            return response()->json($response);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ReferencesData  $referencesData
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReferencesData $referencesData)
    {
        //
    }
}
