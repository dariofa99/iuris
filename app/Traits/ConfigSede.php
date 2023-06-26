<?php
namespace App\Traits;

trait ConfigSede {

		public function has($permission){

		 	$configs = $this->configuracions ;
            //dd($configs) ;
			 foreach ($this->configuracions as $key => $config) {
				if($config->nombre_corto == $permission) return true;				
			 }
			 return false;

	}
  
}