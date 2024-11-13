<?php
namespace App\Repositories;

use App\ReferencesData;
use App\Segmento;

use App\Services\ReferencesDataService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferencesDataRepository extends BaseRepository implements ReferencesDataService{
   
    public function __construct(Segmento $segmento)
    {
        parent::__construct($segmento);
    }
    public function store(Request $request){
        $request['categories'] = $request->table;
        $request['short_name'] = sanear_string($request->name);
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
        return  $referencia;
    }
  
  
}




?>