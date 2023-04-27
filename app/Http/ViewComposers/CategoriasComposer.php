<?php 
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use DB;
use \App\TablaReferencia;



/**
*  
*/
class CategoriasComposer
{
	
	public function compose(View $view)
	{

		$type_data = TablaReferencia::where([
            'categoria'=>'type_references_data',
            'tabla_ref'=>'references_data',

            ])
		->where('ref_nombre','<>','Sin definir')
		->pluck('ref_nombre','id');  
	

		$view->with(['type_data'=>$type_data]);
	}

	
}


