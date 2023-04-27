<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use Session;
use Redirect;
use App\Notifications\CitacionEstudiantes;
use App\Notifications\CitacionEstudiantesQueve;
use App\Expediente;
use App\CitacionEstudiantes as Citacion;
use DB;
use App\User;
use Carbon\Carbon;
class CitacionEstudiantesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $expediente = Expediente::where('expid',$request->expid)->first();
        $asignacion = $expediente->getAsignacion();
        $can_edit = false;
        if($asignacion->asig_docente!==null and $asignacion->asig_docente->docidnumber == auth()->user()->idnumber){
          $can_edit = true;
        }
        $asignacion->citaciones->each(function($citacion) use($can_edit){
            $citacion->can_edit = $can_edit ;
        }); 
       
                return $asignacion->citaciones;
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
          
        $expediente = Expediente::where('expid',$request->exp_id)->first();
        $asignacion = $expediente->getAsignacion();
        $docente_as = $expediente->getDocenteAsig();
 
        $citacion = new Citacion();
        $citacion->hora = $request->hora;
        $citacion->motivo = $request->motivo;
        $citacion->fecha = $request->fecha;
        $citacion->fecha_corta = $request->fecha_corta;
        $citacion->docente_fullname = $docente_as->name.' '.$docente_as->lastname;
        $citacion->docidnumber = $docente_as->idnumber;
        $citacion->user_updated_id = \Auth::user()->idnumber;
        $citacion->user_created_id = \Auth::user()->idnumber;
        $citacion->asignacion_caso_id = $asignacion->id;
        $citacion->save();
        $citacion_notify = $expediente->estudiante;
        $citacion_notify->expid = $expediente->expid; 
        $citacion_notify->hora = $citacion->hora;
        $citacion_notify->fecha = $citacion->fecha;
        $citacion_notify->motivo = $citacion->motivo;
        $citacion_notify->to = $citacion_notify->email;
        $citacion_notify->cc = $docente_as->email;
        $citacion_notify->docente_fullname = $docente_as->name.' '.$docente_as->lastname;
        $citacion_notify->notify(new CitacionEstudiantes($citacion_notify));
        
        return response()->json($expediente);

         $expediente = Expediente::where('expid',$request->exp_id)->first(['expid']);
    //$estudiante = $expediente->estudiante;
    //$estudiante = currentUser();
    $expediente->email = 'darioj99@gmail.com';

    $expediente->notify(new CitacionEstudiantes($expediente));

    // return $expediente->estudiante;
    
        
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
        $citacion = Citacion::find($id);

        return response()->json($citacion);
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
        $expediente = Expediente::where('expid',$request->exp_id)->first();
        $asignacion = $expediente->getAsignacion();
        $docente_as = $expediente->getDocenteAsig();
 
        $citacion =  Citacion::find($id);
        $citacion->hora = $request->hora;
        $citacion->motivo = $request->motivo;
        $citacion->fecha = $request->fecha;
        $citacion->fecha_corta = $request->fecha_corta;
        $citacion->docente_fullname = $docente_as->name.' '.$docente_as->lastname;
        $citacion->docidnumber = $docente_as->idnumber;
        $citacion->user_updated_id = \Auth::user()->idnumber;
        $citacion->user_created_id = \Auth::user()->idnumber;
        $citacion->asignacion_caso_id = $asignacion->id;
        $citacion->save();
        $citacion_notify = $expediente->estudiante;
        $citacion_notify->expid = $expediente->expid; 
        $citacion_notify->hora = $citacion->hora;
        $citacion_notify->fecha = $citacion->fecha;
        $citacion_notify->motivo = $citacion->motivo;
        $citacion_notify->to = $citacion_notify->email;
        $citacion_notify->cc = $docente_as->email;
        $citacion_notify->docente_fullname = $docente_as->name.' '.$docente_as->lastname;
        $citacion_notify->notify(new CitacionEstudiantes($citacion_notify));
        
        return response()->json(['rs'=>200]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function searchCitasForDay(Request $request)
    {
        
        $citas = Citacion::whereDate('fecha_corta',$request->fecha)
        ->where('docidnumber',\Auth::user()->idnumber)->get();
        $citas->each(function($asignacion){
            $asignacion->asignacion->estudiante;
        });
        
        return response()->json($citas);
    }

    public function citasAutomatic(Request $request)
    {

        $estudiantes = DB::select(DB::raw('SELECT `asignacion_docente_caso`.`docidnumber` as docente, COUNT(`asignacion_docente_caso`.`docidnumber`) as casos, COUNT(`asignacion_docente_caso`.`docidnumber`)*15 as tiempo, expedientes.expidnumberest as estudiante, users.cursando_id as curso, "0" as asignado, CONCAT(users.name," ",users.lastname) as nombreest, users.tel1 as telest, users.email as correoest FROM `asignacion_docente_caso` join asignacion_caso on `asignacion_docente_caso`.asig_caso_id = asignacion_caso.id join expedientes on asignacion_caso.asigexp_id = expedientes.expid JOIN users on expedientes.expidnumberest = users.idnumber where (expedientes.expestado_id = 1 or expedientes.expestado_id = 3 or expedientes.expestado_id = 4) and asignacion_docente_caso.`activo` = 1 and (users.cursando_id = 116 or users.cursando_id = 114) GROUP by `asignacion_docente_caso`.`docidnumber`, expedientes.expidnumberest ORDER BY users.cursando_id desc, expedientes.expidnumberest
        '));
       
       
       $horario = DB::select(DB::raw('SELECT `trnd_docidnumber` as hdocente, `trnd_dia` as dia, TIMEDIFF(`trnd_hora_fin`,`trnd_hora_inicio`) as horas, TRUNCATE(TIMEDIFF(`trnd_hora_fin`,`trnd_hora_inicio`)*0.0001,0)*60 as totalminutos, "0" as llenasd, "0" as llenass, `trnd_hora_inicio` as inicio, `trnd_hora_inicio` as inicio2, CONCAT(users.name," ",users.lastname) as nombredoc, users.tel1 as teldoc, users.email as correodoc FROM `turnos_docentes` JOIN users on turnos_docentes.trnd_docidnumber = users.idnumber where `trnd_hora_inicio` > "13:00" ORDER BY `trnd_docidnumber`, `trnd_dia`
       '));
       
       //dd($horario);
      // dd($estudiantes);
      
      $data=[];
      $dataest=[];
      $newestudiates =[];


 



       foreach ($estudiantes as $key => $estudiante) {
           if ($estudiante->asignado == 0) {
               $contador=0;
             while ($estudiante->asignado == 0 OR $estudiante->asignado ==2) { 
                // echo $estudiante->estudiante."<br>";
                
                $horario=array_sort($horario, 'llenass', SORT_ASC);
                
                foreach ($horario as $key2 => $docente) {
                    if ($estudiante->docente == $docente->hdocente ) {
        
                        $llenasdia=$docente->llenasd;
                        if ($llenasdia <= $docente->totalminutos) {
                            $minutosrequeridos=$docente->llenasd+$estudiante->tiempo;
                            if ($minutosrequeridos <= $docente->totalminutos) {
                                
                                if ( ($docente->llenass == 3 OR $docente->llenass == 6 OR $docente->llenass == 7) AND $docente->dia == "Lunes") {
                                    $docente->llenass++;
                                    
                                } else {
                                $docente->llenasd = $docente->llenasd+$estudiante->tiempo;
                                $inicio =$docente->inicio;
                                $inicio=Carbon::parse($inicio);
                                $horafin=Carbon::parse($inicio);
                                $horafin = $horafin->addMinute($estudiante->tiempo);
                                
                                 

                                if (isset($dataest) AND isset($dataest[$estudiante->estudiante]) ) {
                                    $sw=0;
                                    foreach ($dataest[$estudiante->estudiante] as $key3 => $value) {
                                        
                                       if ($value['dia'] == $docente->dia AND  $value['semana'] ==  $docente->llenass AND ($inicio->format('H:i:s') >= $value['inicio'] && $inicio->format('H:i:s') <= $value['fin']) ) {
                                         
                                            $sw=1;
                                            $estudiante->asignado=2; 
                                            //llena en el espacio otro estudiante
                                    
                                            foreach ($estudiantes as $key4 => $newestudiante) {
                                                if ($newestudiante->asignado == 0) {
                                                    if ($newestudiante->tiempo >= $estudiante->tiempo) {
                                                        if ($newestudiante->docente == $docente->hdocente ) {
                                                        if (isset($dataest) AND isset($dataest[$newestudiante->estudiante]) ) {
                                                            $sw2=0;
                                                            foreach ($dataest[$newestudiante->estudiante] as $key5 => $value2) {
                                                                if ($value2['dia'] == $docente->dia AND  $value2['semana'] ==  $docente->llenass AND ($inicio->format('H:i:s') >= $value2['inicio'] && $inicio->format('H:i:s') <= $value2['fin']) ) {
                                                                    $sw2=1;
                                                                } 
                                                            }
                                                            if ($sw2==0) {
                                                                
                                                                $dataest[$newestudiante->estudiante][]= ['dia'=>$docente->dia, 'inicio'=>$inicio->format('H:i:s'),'fin'=>$horafin->format('H:i:s'),'semana'=>$docente->llenass];
                                                                $data[]=['docente'=>$docente->hdocente,'nombredocente'=>$docente->nombredoc,'docentecorreo'=>$docente->correodoc,'docentetel'=>$docente->teldoc,'estudiante'=>$newestudiante->estudiante,'nombreestudiante'=>$newestudiante->nombreest,'estudiantecorreo'=>$newestudiante->correoest,'estudiantetel'=>$newestudiante->telest,'dia'=>$docente->dia, 'inicio'=>$inicio->format('H:i:s'), 'fin'=>$horafin->format('H:i:s'), 'semana'=>$docente->llenass];
                                                                $docente->inicio = $horafin;
                                                                $newestudiante->asignado=1;
                                                                break;
                                                            }

                                                        } else {
                                                            
                                                            $dataest[$newestudiante->estudiante][]= ['dia'=>$docente->dia, 'inicio'=>$inicio->format('H:i:s'),'fin'=>$horafin->format('H:i:s'),'semana'=>$docente->llenass];
                                                            $data[]=['docente'=>$docente->hdocente,'nombredocente'=>$docente->nombredoc,'docentecorreo'=>$docente->correodoc,'docentetel'=>$docente->teldoc,'estudiante'=>$newestudiante->estudiante,'nombreestudiante'=>$newestudiante->nombreest,'estudiantecorreo'=>$newestudiante->correoest,'estudiantetel'=>$newestudiante->telest,'dia'=>$docente->dia, 'inicio'=>$inicio->format('H:i:s'), 'fin'=>$horafin->format('H:i:s'), 'semana'=>$docente->llenass];
                                                            $docente->inicio = $horafin;
                                                            $newestudiante->asignado=1;
                                                            break;
                                                        }
                                                        }
                                                   }
                                                }
                                            }


                                            //no puede ser asignado se asigna 2 para buscar otro
                                           
                                        } 
                                        
                                    }
                                    if ($sw==0) {
                                        $dataest[$estudiante->estudiante][]= ['dia'=>$docente->dia, 'inicio'=>$inicio->format('H:i:s'),'fin'=>$horafin->format('H:i:s'),'semana'=>$docente->llenass];
                                        $data[]=['docente'=>$docente->hdocente,'nombredocente'=>$docente->nombredoc,'docentecorreo'=>$docente->correodoc,'docentetel'=>$docente->teldoc,'estudiante'=>$estudiante->estudiante,'nombreestudiante'=>$estudiante->nombreest,'estudiantecorreo'=>$estudiante->correoest,'estudiantetel'=>$estudiante->telest,'dia'=>$docente->dia, 'inicio'=>$inicio->format('H:i:s'), 'fin'=>$horafin->format('H:i:s'), 'semana'=>$docente->llenass];
                                        $docente->inicio = $horafin;
                                        $estudiante->asignado=1;

                                    } else {
                                        $sw=0;
                                    }
                                } else {
                                    $dataest[$estudiante->estudiante][]= ['dia'=>$docente->dia, 'inicio'=>$inicio->format('H:i:s'),'fin'=>$horafin->format('H:i:s'),'semana'=>$docente->llenass];
                                    $data[]=['docente'=>$docente->hdocente,'nombredocente'=>$docente->nombredoc,'docentecorreo'=>$docente->correodoc,'docentetel'=>$docente->teldoc,'estudiante'=>$estudiante->estudiante,'nombreestudiante'=>$estudiante->nombreest,'estudiantecorreo'=>$estudiante->correoest,'estudiantetel'=>$estudiante->telest,'dia'=>$docente->dia, 'inicio'=>$inicio->format('H:i:s'), 'fin'=>$horafin->format('H:i:s'),'semana'=>$docente->llenass];
                                    $docente->inicio = $horafin;
                                    $estudiante->asignado=1;
                                }
                                

                                //$dataest[$estudiante->estudiante][]= $docente->dia." inicio: ". $inicio->format('H:i:s')." fin: ".$horafin->format('H:i:s')." sem: ".$docente->llenass." doc: ".$docente->hdocente;

                               
                            break;
                            }
                            } else {
                                $docente->llenass++;
                                $docente->llenasd=0;
                                $docente->inicio = $docente->inicio2;

                            }

                        

                        } 
                    } 

                    
                }//cierra foreach horario
               $contador++;
             } //cierra while

           }
    


       }
       $estsinasig=[];
       foreach ($estudiantes as $key => $estudiante) {
           if ($estudiante->asignado == 2) {
            $estsinasig[]=$estudiante;
           }
       }


      // echo json_encode($data);

       //dd($estudiantes);
       
       $data2=[]; //estudiantes
       foreach ($data as $key => $value) {
         $data2[$value['estudiante']][]= $value['dia']." inicio: ". $value['inicio']." fin: ".$value['fin']." semana: ".$value['semana']." docente: ".$value['docente'];

       }

      dd($estsinasig,$data2,$estudiantes);

      
    }

    public function listCorreoCitasGen()
    {
        $url = "https://iurisapp.udenar.edu.co/dataesttarde.json";
        $datas = file_get_contents($url);
   
        $data=[];
        $data =  json_decode($datas);
        
        
        $fechasem=[0=>['Lunes'=>'2020-05-04','Martes'=>'2020-05-05','Miercoles'=>'2020-05-06','Jueves'=>'2020-05-07','Viernes'=>'2020-05-08'],
        1=>['Lunes'=>'2020-05-11','Martes'=>'2020-05-12','Miercoles'=>'2020-05-13','Jueves'=>'2020-05-14','Viernes'=>'2020-05-15'],
        2=>['Lunes'=>'2020-05-18','Martes'=>'2020-05-19','Miercoles'=>'2020-05-20','Jueves'=>'2020-05-21','Viernes'=>'2020-05-22'],
        3=>['Lunes'=>'2020-05-25','Martes'=>'2020-05-26','Miercoles'=>'2020-05-27','Jueves'=>'2020-05-28','Viernes'=>'2020-05-29'],//festivo
        4=>['Lunes'=>'2020-06-01','Martes'=>'2020-06-02','Miercoles'=>'2020-06-03','Jueves'=>'2020-06-04','Viernes'=>'2020-06-05'],
        5=>['Lunes'=>'2020-06-08','Martes'=>'2020-06-09','Miercoles'=>'2020-06-10','Jueves'=>'2020-06-11','Viernes'=>'2020-06-12'],
        6=>['Lunes'=>'2020-06-15','Martes'=>'2020-06-16','Miercoles'=>'2020-06-17','Jueves'=>'2020-06-18','Viernes'=>'2020-06-19'],//festivo
        7=>['Lunes'=>'2020-06-22','Martes'=>'2020-06-23','Miercoles'=>'2020-06-24','Jueves'=>'2020-06-25','Viernes'=>'2020-06-26'],//festivo
        8=>['Lunes'=>'2020-06-29','Martes'=>'2020-06-30','Miercoles'=>'2020-07-01','Jueves'=>'2020-07-02','Viernes'=>'2020-07-03'],
  
        ];
        $meses = array("","enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");

      
 
       $data2=[]; //estudiantes
        foreach ($data as $key => $value) {
          // $data2[$value->estudiante][]= $value->dia." inicio: ".$value->inicio." fin: ".$value->fin." fecha: ".$value->fecha." semana: ".$value->semana." docente: ".$value->docente;
          $data2[$value->estudiante][]= ['dia'=>$value->dia,"inicio"=>$value->inicio,"fin"=>$value->fin,"fecha"=>$fechasem[$value->semana][$value->dia],"semana"=>$value->semana,"docente"=>$value->docente,"nomdocente"=>$value->nombredocente,"teldoc"=>$value->docentetel,"nomest"=>$value->nombreestudiante,"correoest"=>$value->estudiantecorreo];
        }
        $data3=[]; //docentes
       
        foreach ($data as $key => $value) {
          // $data3[$value->docente][]= $value->dia." inicio: ".$value->inicio." fin: ".$value->fin." fecha: ".$value->fecha." semana: ".$value->semana." estudiante: ".$value->estudiante;
          $data3[$value->docente][]= ['dia'=>$value->dia,"inicio"=>$value->inicio,"fin"=>$value->fin,"fecha"=>$fechasem[$value->semana][$value->dia],"semana"=>$value->semana,"estudiante"=>$value->estudiante,"nomest"=>$value->nombreestudiante,"correoest"=>$value->estudiantecorreo,"telest"=>$value->estudiantetel,"nomdocente"=>$value->nombredocente,"correodoc"=>$value->docentecorreo];
        }
        

     foreach ($data2 as $key => $value) {
         $citas="";
         $text = "Cordial saludo, estudiante ".$value[0]['nomest'].",<br>Este correo cuenta con las citas asignadas para la revisión de los casos asegúrese de estar listo 5 minutos antes de la hora programada.<br><br>";
         foreach ($value as $key2 => $value2) {
             $fechaarray=explode("-",$value2['fecha']);
             $fecha=$fechaarray[2]." de ".$meses[intval($fechaarray[1])]." del ".$fechaarray[0];
      
             $horai = $this->horaFun($value2['inicio']);
             $horaf = $this->horaFun($value2['fin']);
            
             $citas .= "─ <b>Docente:</b> ".$value2['nomdocente']."<br>&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha:</b> ".$fecha."<br>&nbsp;&nbsp;&nbsp;&nbsp;<b>hora inicio:</b> ".$horai."<br>&nbsp;&nbsp;&nbsp;&nbsp;<b>hora fin:</b> ".$horaf. "<br>&nbsp;&nbsp;&nbsp;&nbsp;<b>Número celular docente:</b> ".$value2['teldoc']."<br><br>";
         }
 

         $motivo=$text.$citas;
         
        //$this->correoCitasGen($motivo, $value[0]['correoest'], $key);
       echo $motivo;

         
     }
     foreach ($data3 as $key => $value) {
         $citas="";
         $text = "Cordial saludo, Estimado(a) docente ".$value[0]['nomdocente'].",<br>Este correo cuenta con las citas asignadas para la revisión de los casos asegúrese de estar listo 5 minutos antes de la hora programada.<br><br>";
         foreach ($value as $key2 => $value2) {
             $fechaarray=explode("-",$value2['fecha']);
             $fecha=$fechaarray[2]." de ".$meses[intval($fechaarray[1])]." del ".$fechaarray[0];
             $horai = $this->horaFun($value2['inicio']);
             $horaf = $this->horaFun($value2['fin']);
             $citas .= "─ <b>Estudiante:</b> ".$value2['nomest']."<br>&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha:</b> ".$fecha."<br>&nbsp;&nbsp;&nbsp;&nbsp;<b>hora inicio:</b> ".$horai."<br>&nbsp;&nbsp;&nbsp;&nbsp;<b>hora fin:</b> ".$horaf. "<br>&nbsp;&nbsp;&nbsp;&nbsp;<b>Número celular estudiante:</b> ".$value2['telest']."<br>&nbsp;&nbsp;&nbsp;&nbsp;<b>Correo estudiante:</b> ".$value2['correoest']."<br><br>";
         }
         $motivo=$text.$citas;
        
        // $this->correoCitasGen($motivo, $value[0]['correodoc'], $key);
         // echo $motivo;
         
     }
  
      //  dd($data3,$data2);
    
    }



    public function horaFun($hora)
    {

        $hora = strtotime($hora);
        $hora = date("h:i A", $hora);
        return $hora;
    }
    public function correoCitasGen($motivo,$correo,$id){
              // dd('mail');
        $consulta = DB::table('logs')->where('cedula',$id)->first();
     if (!$consulta) {
           
            $citacion_notify = User::where('idnumber',$id)->first();
            $citacion_notify->motivo = $motivo;
            $citacion_notify->to = $correo;
            $citacion_notify->notify(new CitacionEstudiantesQueve($citacion_notify)); 
            $insert = DB::table('logs')->insert(['cedula'=>$id, 'correo'=>$correo]);

        }


    }
    public function comprobacioncitasgen($motivo,$correo,$id){

        $estudiantes = DB::select(DB::raw('SELECT `asignacion_docente_caso`.`docidnumber` as docente, COUNT(`asignacion_docente_caso`.`docidnumber`) as casos, COUNT(`asignacion_docente_caso`.`docidnumber`)*15 as tiempo, expedientes.expidnumberest as estudiante, users.cursando_id as curso, "0" as asignado, CONCAT(users.name," ",users.lastname) as nombreest, users.tel1 as telest, users.email as correoest FROM `asignacion_docente_caso` join asignacion_caso on `asignacion_docente_caso`.asig_caso_id = asignacion_caso.id join expedientes on asignacion_caso.asigexp_id = expedientes.expid JOIN users on expedientes.expidnumberest = users.idnumber where (expedientes.expestado_id = 1 or expedientes.expestado_id = 3 or expedientes.expestado_id = 4) and asignacion_docente_caso.`activo` = 1 and (users.cursando_id = 116 or users.cursando_id = 114) GROUP by `asignacion_docente_caso`.`docidnumber`, expedientes.expidnumberest ORDER BY users.cursando_id desc, expedientes.expidnumberest
        '));
       
       
       $horario = DB::select(DB::raw('SELECT `trnd_docidnumber` as hdocente, `trnd_dia` as dia, TIMEDIFF(`trnd_hora_fin`,`trnd_hora_inicio`) as horas, TRUNCATE(TIMEDIFF(`trnd_hora_fin`,`trnd_hora_inicio`)*0.0001,0)*60 as totalminutos, "0" as llenasd, "0" as llenass, `trnd_hora_inicio` as inicio, `trnd_hora_inicio` as inicio2, CONCAT(users.name," ",users.lastname) as nombredoc, users.tel1 as teldoc, users.email as correodoc FROM `turnos_docentes` JOIN users on turnos_docentes.trnd_docidnumber = users.idnumber where `trnd_hora_inicio` > "13:00" ORDER BY `trnd_docidnumber`, `trnd_dia`
       '));
       
       //dd($horario);
      // dd($estudiantes);
      
      $data=[];
      $dataest=[];
      $newestudiates =[];

      $url = "https://iurisapp.udenar.edu.co/dataesttarde.json";
      $datas = file_get_contents($url);
 
      $data=[];
      $data =  json_decode($datas);
      foreach ($estudiantes as $key => $estudiante) {
      foreach ($data as $key => $value) {
         
              if ($estudiante->estudiante == $value->estudiante) {
                if ($estudiante->docente == $value->docente) {
                    
                }
              }
          }


      }



}




   
}
