<?php 
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\TipoArchivo;
use App\RamaDerecho;


/**
* 
*/
class BibliotecasComposer
{
	
	public function compose(View $view)
	{

		$tipo_archivo = TipoArchivo::pluck('tiparchinombre','id');
		$rama_derecho = RamaDerecho::pluck('ramadernombre','id');

		$view->with(['tipo_archivo'=>$tipo_archivo])
			//->with(['cajas'=>$cajas])
			//->with(['clientes'=>$clientes])
			->with(['rama_derecho'=>$rama_derecho]);
	}

	
}


