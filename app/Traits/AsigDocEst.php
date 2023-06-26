<?php
namespace App\Traits;
use App\Turno;
use DB;
use App\Periodo;
use App\Asigna_docen_est;
use Session;

trait AsigDocEst {

	public function asignarDocente($request){	
            $docentes = DB::table('users')
           ->join('role_user', 'users.id', '=', 'role_user.user_id')
           ->join('roles' , 'role_user.role_id','=','roles.id')
           ->join('horario_docentes as hd' , 'hd.docidnumber','=','users.idnumber')
           ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
           ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
           ->where ('role_id', '4' )
           ->where ('users.active', true)
           ->where('sedes.id_sede',session('sede')->id_sede)
           ->select('users.idnumber','hd.horas_a','hd.horas_b','hd.num_max_est','hd.num_est_a','hd.num_est_b')
           ->get();
           
            if (count($docentes)<=0) {
             return false; 
            }
         
          $docentes_horario = [];
          $docentes_es = [];
          //Comienzo if 4a
            if ($request->cursando_id == '114') {
                //Docentes
                foreach ($docentes as $key => $docente) {
                  if ($docente->horas_b>0) {
                     $docentes_horario[$key] = $docente;
                    }
                }

                foreach ($docentes_horario as $key => $docente_h) {
                    /*$cupos_docente = Asigna_docen_est::where(['asgedidnumberdocen'=>$docente_h->idnumber,'asgedidcursando'=>$request->cursando_id])->count('asgedidcursando');
*/
                  $cupos_docente = DB::table('asigna_docent_ests')
                  //->join('users','users.idnumber','=','asigna_docent_ests.asgedidnumberest') 
                  ->join('users','users.idnumber','=','asigna_docent_ests.asgedidnumberdocen') 
                  ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
                  ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')  //linea de prueba, sin validar               
                  ->where('asgedidnumberdocen',$docente_h->idnumber)
                  ->where('users.cursando_id',$request->cursando_id)
                  ->where('sedes.id_sede',session('sede')->id_sede)
                  ->count('users.cursando_id');

                  echo "$cupos_docente<br>";

                    if ($cupos_docente < $docente_h->num_est_b) {
                     $docentes_es[$docente_h->idnumber] = $cupos_docente;
                    }
                }

                foreach ($docentes_es as $key => $docente_e) {
                 if ($docente_e == min($docentes_es)) {
                   $asgedidnumberdocen = $key;
                   break;
                 }
                }

                //dd('');
               
            }
            //////////////////////////////////////
            //fin if 4a

            /////4b  //////////////////////////////////
            if ($request->cursando_id == '115') {
                 //Docentes
                foreach ($docentes as $key => $docente) {
                  if ($docente->horas_a>0) {
                     $docentes_horario[$key] = $docente;
                    }
                }

                foreach ($docentes_horario as $key => $docente_h) {
                    $cupos_docente = DB::table('asigna_docent_ests')
                  ->join('users','users.idnumber','=','asigna_docent_ests.asgedidnumberdocen')
                  ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
                  ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')  //linea de prueba, sin validar               
                  ->where('sedes.id_sede',session('sede')->id_sede)
                  ->where('asgedidnumberdocen',$docente_h->idnumber)
                  ->where('users.cursando_id',$request->cursando_id)
                 
                  ->count('users.cursando_id');
                    if ($cupos_docente < $docente_h->num_est_a) {
                     $docentes_es[$docente_h->idnumber] = $cupos_docente;
                    }
                }

                foreach ($docentes_es as $key => $docente_e) {
                 if ($docente_e == min($docentes_es)) {
                   $asgedidnumberdocen = $key;
                   break;
                 }
                }

               
                //dd($turnos_color);
            }
		/////////////////////////////////////////////////////////////////////////////

            /////5a  //////////////////////////////////
            if ($request->cursando_id == '116') {
                //Docentes
                foreach ($docentes as $key => $docente) {
                  if ($docente->horas_b>0) {
                     $docentes_horario[$key] = $docente;
                    }
                }

                foreach ($docentes_horario as $key => $docente_h) {
                   $cupos_docente = DB::table('asigna_docent_ests')
                  ->join('users','users.idnumber','=','asigna_docent_ests.asgedidnumberdocen')
                  ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
                  ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')  //linea de prueba, sin validar               
                  ->where('sedes.id_sede',session('sede')->id_sede)
                  ->where('asgedidnumberdocen',$docente_h->idnumber)
                  ->where('users.cursando_id',$request->cursando_id)
                  ->count('users.cursando_id');
                    if ($cupos_docente < $docente_h->num_est_b) {
                     $docentes_es[$docente_h->idnumber] = $cupos_docente;
                    }
                }

                foreach ($docentes_es as $key => $docente_e) {
                 if ($docente_e == min($docentes_es)) {
                   $asgedidnumberdocen = $key;
                   break;
                 }
                }
                
                //dd($turnos_horario);
            }
        /////////////////////////////////////////////////////////////////////////////

            /////5b  //////////////////////////////////
            if ($request->cursando_id == '117') {
                //Docentes
                foreach ($docentes as $key => $docente) {
                  if ($docente->horas_a>0) {
                     $docentes_horario[$key] = $docente;
                    }
                }

                foreach ($docentes_horario as $key => $docente_h) {
                    $cupos_docente = DB::table('asigna_docent_ests')
                  ->join('users','users.idnumber','=','asigna_docent_ests.asgedidnumberdocen')
                  ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
                  ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')  //linea de prueba, sin validar               
                  ->where('sedes.id_sede',session('sede')->id_sede)
                  ->where('asgedidnumberdocen',$docente_h->idnumber)
                  ->where('users.cursando_id',$request->cursando_id)
                  ->count('users.cursando_id');
                    if ($cupos_docente < $docente_h->num_est_a) {
                     $docentes_es[$docente_h->idnumber] = $cupos_docente;
                    }
                }

                foreach ($docentes_es as $key => $docente_e) {
                 if ($docente_e == min($docentes_es)) {
                   $asgedidnumberdocen = $key;
                   break;
                 }
                }
                
            }
        /////////////////////////////////////////////////////////////////////////////
            $periodo = Periodo::where('estado',true)->first();
         //dd($periodo);
         if ($periodo and isset($asgedidnumberdocen)) {
            $asigacion = [
              'asgedidnumberest'=>currentUser()->idnumber,
              'asgedidnumberdocen'=>$asgedidnumberdocen,
              'asgedidperiodo'=>$periodo->id,
              //'asgedidcursando'=>$request->cursando_id,
              'asgedusercreated'=>currentUser()->idnumber,
              'asgeduserupdated'=>currentUser()->idnumber,
            ];

            $this->createAsignacion($asigacion);
            return true;
         }

         return false;

           
		 	

    }

    public function createAsignacion($asigacion){
        Asigna_docen_est::create($asigacion);
    }

}