<?php
namespace App\Traits;

trait Cursos {

		public function getCurso($num){

		 	switch ($num) { 
		 	        case '0':
		 	        return "4a";
		 	        break;
		            case '1':
		                return "4b"; 
		                break;
		            case '2':
		                return "5a"; 
		                break;                     
		            case '3':
		               return '5b';
		               break;

			}

	}

}