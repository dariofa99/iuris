<?php

namespace App\Http\Controllers;

use App\ReferencesStaticData;
use DB;
use Illuminate\Http\Request;
use App\ReferenceStaticDataOptions;

class ReferencesStaticDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *  
     * @return \Illuminate\Http\Response
     */
    public function index()  
    {
        $categories = $this->getCategories();
        return view('myforms.static_categories.index',compact('categories'));
    }

    private function getCategories(){
       return $categories = ReferencesStaticData::orderBy('categories','asc')->get();
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
       // dd($request->all());
        $request['categories'] = $request->table;
        $referencia = ReferencesStaticData::create($request->all());

        if($request->has('option_name')){
            foreach ($request->option_name as $key => $option) {
                $insert = DB::table("references_static_data_options")
                ->insert([
                    'value'=>$option,
                    'references_static_data_id'=>$referencia->id,
                    'active_other_input'=>$request->active_other_input[$key]
                ]);
            }
        }else{
            $insert = DB::table("references_static_data_options")
                ->insert([
                    'value'=>$request->name,
                    'references_static_data_id'=>$referencia->id,
                    'active_other_input'=>0
                ]);
        }
        $categories = $this->getCategories();
        $view =  view('myforms.static_categories.partials.ajax.index',compact('categories'))->render();
        $response=[];
        $response['render_view'] = $view;
        return response()->json( $response);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ReferencesStaticData  $ReferencesStaticData
     * @return \Illuminate\Http\Response
     */
    public function show(ReferencesStaticData $ReferencesStaticData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ReferencesStaticData  $ReferencesStaticData
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $referencia = ReferencesStaticData::find($id);     
        $referencia->options; 
        return response()->json($referencia);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ReferencesStaticData  $ReferencesStaticData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {

           
            //return response()->json($request->all()); 
            $request['categories'] = $request->table;
            $referencia = ReferencesStaticData::find($id);
            $older_type_data = $referencia->type_data_id; 
            $referencia->fill($request->all());
            $referencia->save();
            $referencia->options;   
            $items_deleted = json_decode($request->items_deleted);
            if(count($items_deleted)>0){
                foreach ($items_deleted as $key => $item_d) {               
                    $option_o = ReferenceStaticDataOptions::find($item_d->id)->delete();
                }
            }        
            if($request->has('option_name')){
                if($older_type_data == 168 || $older_type_data==173 || $older_type_data== 174){
                    $referencia->options()->delete();
                }
                foreach ($request->option_name as $key => $option) {
                    if($request->options_id[$key]!='null'){
                        $option_o = ReferenceStaticDataOptions::find($request->options_id[$key]);                       
                        if($option_o){
                            $option_o->value = $option;
                            $option_o->active_other_input = $request->active_other_input[$key];
                            $option_o->save();
                        }
                    }else{ 
                        $insert = DB::table("references_static_data_options")
                        ->insert([
                            'value'=>$option,
                            'references_static_data_id'=>$referencia->id,
                            'active_other_input'=>$request->active_other_input[$key]
                        ]);
                    }
                   
                   
                }
            }elseif(!$request->has('option_name')){
                if($older_type_data != 168 && $older_type_data != 173 && $older_type_data != 174){
                    $referencia->options()->delete();
                    $insert = DB::table("references_static_data_options")               
                ->insert([
                    'value'=>$request->display_name,
                    'references_static_data_id'=>$referencia->id,
                    'active_other_input'=>0
                ]);
                }else{
                    $insert = DB::table("references_static_data_options")
                    ->where("references_static_data_id",$referencia->id)
                    ->update([
                        'value'=>$request->display_name,
                        'references_static_data_id'=>$referencia->id,
                        'active_other_input'=>0
                    ]);  
                }
               // $referencia->options()->delete();
               // dd("si");
              // return response()->json($request->all()); 
                
            }
            $categories = $this->getCategories();
            $view =  view('myforms.static_categories.partials.ajax.index',compact('categories'))->render();
            $response=[];
            $response['render_view'] = $view;
            return response()->json($response);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ReferencesStaticData  $ReferencesStaticData
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReferencesStaticData $ReferencesStaticData)
    {
        //
    }
}
