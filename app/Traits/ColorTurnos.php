<?php
namespace App\Traits;

trait ColorTurnos {

	public function getColorTurno($num){

 switch ($num) { 
 	//0->naranja, 1->Azul oscuro, 2->verde, 3->gris, 4->rojo

            case '0':
                return "color-amarillo"; 
                break;
            case '1':
                return "color-azul"; 
                break;
            case '2':
                return "color-verde"; 
                break;
            case '3':
                return "color-gris"; 
                break;
            case '4':
                return "color-rojo"; 
                break;
            case '5':
                return "color-morado"; 
                break;                    
            default:
               return 'bg-red';
               break;

	} 

	}

		public function getMjs($num){

		 	switch ($num) { 
		 	        case '0':
		            case '1':
                    case '4':
		                return "Jornada de la Mañana"; 
		                break;
		            case '2':
		            case '3':
                    case '5':
		                return "Jornada de la Tarde"; 
		                break;                     
		            default:
		               return 'Sin Jornada';
		               break;

			}

	}

}