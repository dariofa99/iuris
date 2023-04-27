<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;



use Session;
use Redirect;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Turno;
use App\turnos_docentes;
use App\AsistenciaDocentes;
use App\User;
use DB;



class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index() {
  return redirect('horarios/estudiantes');

}
    
public function calendario($tipo)
{
  $docentes = DB::table('users')
  ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
  ->leftjoin('roles' , 'role_user.role_id','=','roles.id')
  ->leftjoin('referencias_tablas' , 'referencias_tablas.id','=','users.cursando_id')
  ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
  ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
  ->join('turnos_docentes','users.idnumber','=','turnos_docentes.trnd_docidnumber')
  ->where ('role_id', '4' )
  ->where ('users.active', true) 
  ->where ('users.idnumber','<>', '2020')
  ->where('sedes.id_sede',session('sede')->id_sede)
  ->select('users.active','users.id','ref_nombre','users.idnumber',
    DB::raw('CONCAT(users.name," ",users.lastname) as full_name')
    ,'role_user.role_id', 'roles.display_name')->groupBy('users.idnumber')->orderBy('users.created_at', 'desc')->get();


  $periodo= DB::table('periodo')
  ->join('sede_periodos as sp','sp.periodo_id','=','periodo.id')
	->where('sp.sede_id',session('sede')->id_sede)
  ->where('estado', '=', '1')
  ->first();
  //dd($periodo);
  if($periodo==null) {
    //return redirect('/periodos')->with('message-danger', 'Registra un periodo y un corte!');;
  }
  if ($tipo=="estudiantes") {

    $fecha=$periodo->prdfecha_inicio;
    $fecha_i=$fecha;
    $fecha_f=$periodo->prdfecha_fin;
    $fecha2=array();
    $events="";
    $nombre_turnos=array("Turno1","Turno2","Turno3","Turno4","Turno5" );
    $colores_turnos=array("#fdd835","#0073b7","#00a65a","#a0afb3","#f56954" );
    $colores_id=array("105","106","107","108","109");
    $festivos=array("2017-08-21","2017-10-16","2017-11-06","2017-11-13","2017-12-08");

    //hace el vector con los dias de la semana que estan en la fecha dada

    $dt3 = Carbon::parse($fecha_i);

    if ($dt3->dayOfWeek == Carbon::SATURDAY) {//si la fecha se establece en horario fuera del laboral 
      $dt3 = $dt3->addDay(2);
      $fecha = strtotime ( '+2 day' , strtotime ( $fecha ) ) ;
      $fecha = date ( 'Y-m-d' , $fecha );
    } elseif ($dt3->dayOfWeek == Carbon::SUNDAY) {//si la fecha se establece en horario fuera del laboral
      $dt3 = $dt3->addDay();
      $fecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
      $fecha = date ( 'Y-m-d' , $fecha );
    }

    if ($dt3->dayOfWeek == Carbon::MONDAY) {
      for ($i=0; $i <= 4; $i++) {
        $fecha2[$i]=$fecha;
        $fecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
        $fecha = date ( 'Y-m-d' , $fecha );

      }
    }elseif ($dt3->dayOfWeek == Carbon::TUESDAY) {
      $fecha = strtotime ( '-1 day' , strtotime ( $fecha ) ) ;
      $fecha = date ( 'Y-m-d' , $fecha );
      for ($i=0; $i <= 4; $i++) {
        $fecha2[$i]=$fecha;
        $fecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
        $fecha = date ( 'Y-m-d' , $fecha );

      }
    }elseif ($dt3->dayOfWeek == Carbon::WEDNESDAY) {
      $fecha = strtotime ( '-2 day' , strtotime ( $fecha ) ) ;
      $fecha = date ( 'Y-m-d' , $fecha );
      for ($i=0; $i <= 4; $i++) {
        $fecha2[$i]=$fecha;
        $fecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
        $fecha = date ( 'Y-m-d' , $fecha );

      }
    }elseif ($dt3->dayOfWeek == Carbon::THURSDAY) {
      $fecha = strtotime ( '-3 day' , strtotime ( $fecha ) ) ;
      $fecha = date ( 'Y-m-d' , $fecha );
      for ($i=0; $i <= 4; $i++) {
        $fecha2[$i]=$fecha;
        $fecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
        $fecha = date ( 'Y-m-d' , $fecha );

      }
    }elseif ($dt3->dayOfWeek == Carbon::FRIDAY) {
      $fecha = strtotime ( '-4 day' , strtotime ( $fecha ) ) ;
      $fecha = date ( 'Y-m-d' , $fecha );
      for ($i=0; $i <= 4; $i++) {
        $fecha2[$i]=$fecha;
        $fecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
        $fecha = date ( 'Y-m-d' , $fecha );

      }
    }

    //determina las semanas entre la fecha de inicio y la final para el for
    $dt = Carbon::parse($fecha_i);
    $dt2 = Carbon::parse($fecha_f);
    $semanas = $dt->diffInDaysFiltered(function(Carbon $date) {
    return $date;
    }, $dt2);
    $semanas=($semanas/7)+1;
    $semanas=intval($semanas);


    for ($i = 0; $i <= 4; $i++) {//for dias
    if ($fecha2[$i]>=$fecha_i) {
    if ($i==1){
      $events=$events. "
      {
      title          : '".$nombre_turnos[$i]."',
      start          : new Date('".$fecha2[$i]."  08:00:00'),
      end            : new Date('".$fecha2[$i]."  10:00:00'),
      backgroundColor: '".$colores_turnos[$i]."',
      borderColor    : '".$colores_turnos[$i]."',
      url            : '',
      clbd           : '".$colores_id[$i]."',
      hrbd           : '110',
      datev          : '".$fecha2[$i]." 08:00:00',
      modal          : 'turnosest'
      },
      {
      title          : '".$nombre_turnos[$i]."',
      start          : new Date('".$fecha2[$i]."  10:00:00'),
      end            : new Date('".$fecha2[$i]."  12:00:00'),
      backgroundColor: '".$colores_turnos[$i]."',
      borderColor    : '".$colores_turnos[$i]."',
      url            : '',
      clbd           : '".$colores_id[$i]."',
      hrbd           : '111',
      datev          : '".$fecha2[$i]." 10:00:00',
      modal          : 'turnosest'
      },
      {
      title          : '".$nombre_turnos[$i]."',
      start          : new Date('".$fecha2[$i]."  14:00:00'),
      end            : new Date('".$fecha2[$i]."  16:00:00'),
      backgroundColor: '".$colores_turnos[$i]."',
      borderColor    : '".$colores_turnos[$i]."',
      url            : '',
      clbd           : '".$colores_id[$i]."',
      hrbd           : '112',
      datev          : '".$fecha2[$i]." 14:00:00',
      modal          : 'turnosest'
      },
      {
      title          : '".$nombre_turnos[$i]."',
      start          : new Date('".$fecha2[$i]."  16:00:00'),
      end            : new Date('".$fecha2[$i]."  18:00:00'),
      backgroundColor: '".$colores_turnos[$i]."',
      borderColor    : '".$colores_turnos[$i]."',
      url            : '',
      clbd           : '".$colores_id[$i]."',
      hrbd           : '113',
      datev          : '".$fecha2[$i]." 16:00:00',
      modal          : 'turnosest'
      },

      ";
    }else{
      $events=$events. "
      {
      title          : '".$nombre_turnos[$i]."',
      start          : new Date('".$fecha2[$i]."  08:00:00'),
      end            : new Date('".$fecha2[$i]."  10:00:00'),
      backgroundColor: '".$colores_turnos[$i]."',
      borderColor    : '".$colores_turnos[$i]."',
      url            : '',
      clbd           : '".$colores_id[$i]."',
      hrbd           : '110',
      datev          : '".$fecha2[$i]." 08:00:00',
      modal          : 'turnosest'
      },
      {
      title          : '".$nombre_turnos[$i]."',
      start          : new Date('".$fecha2[$i]."  10:00:00'),
      end            : new Date('".$fecha2[$i]."  12:00:00'),
      backgroundColor: '".$colores_turnos[$i]."',
      borderColor    : '".$colores_turnos[$i]."',
      url            : '',
      clbd           : '".$colores_id[$i]."',
      hrbd           : '111',
      datev          : '".$fecha2[$i]." 10:00:00',
      modal          : 'turnosest'
      },
      {
      title          : '".$nombre_turnos[$i]."',
      start          : new Date('".$fecha2[$i]."  14:00:00'),
      end            : new Date('".$fecha2[$i]."  16:00:00'),
      backgroundColor: '".$colores_turnos[$i]."',
      borderColor    : '".$colores_turnos[$i]."',
      url            : '',
      clbd           : '".$colores_id[$i]."',
      hrbd           : '112',
      datev          : '".$fecha2[$i]." 14:00:00',
      modal          : 'turnosest'
      },
      {
      title          : '".$nombre_turnos[$i]."',
      start          : new Date('".$fecha2[$i]."  16:00:00'),
      end            : new Date('".$fecha2[$i]."  18:00:00'),
      backgroundColor: '".$colores_turnos[$i]."',
      borderColor    : '".$colores_turnos[$i]."',
      url            : '',
      clbd           : '".$colores_id[$i]."',
      hrbd           : '113',
      datev          : '".$fecha2[$i]." 16:00:00',
      modal          : 'turnosest'
      },

      ";
    }
    }
    for ($j = 1; $j <= $semanas; $j++) { //for turnos
    if(date('D',strtotime($fecha2[$i])) == "Mon" ){
    $fecha2[$i] = strtotime ( '+11 day' , strtotime ( $fecha2[$i] ) ) ;
    $fecha2[$i] = date ( 'Y-m-d' , $fecha2[$i] );
    }else{
    $fecha2[$i] = strtotime ( '+6 day' , strtotime ( $fecha2[$i] ) ) ;
    $fecha2[$i] = date ( 'Y-m-d' , $fecha2[$i] );
    }

    if ($fecha2[$i]<=$fecha_f) {
    if ($fecha2[$i]>=$fecha_i) {
      $events=$events. "
      {
      title          : '".$nombre_turnos[$i]."',
      start          : new Date('".$fecha2[$i]."  08:00:00'),
      end            : new Date('".$fecha2[$i]."  10:00:00'),
      backgroundColor: '".$colores_turnos[$i]."',
      borderColor    : '".$colores_turnos[$i]."',
      url            : '',
      clbd           : '".$colores_id[$i]."',
      hrbd           : '110',
      datev          : '".$fecha2[$i]." 08:00:00',
      modal          : 'turnosest'
      },
      {
      title          : '".$nombre_turnos[$i]."',
      start          : new Date('".$fecha2[$i]."  10:00:00'),
      end            : new Date('".$fecha2[$i]."  12:00:00'),
      backgroundColor: '".$colores_turnos[$i]."',
      borderColor    : '".$colores_turnos[$i]."',
      url            : '',
      clbd           : '".$colores_id[$i]."',
      hrbd           : '111',
      datev          : '".$fecha2[$i]." 10:00:00',
      modal          : 'turnosest'
      },
      {
      title          : '".$nombre_turnos[$i]."',
      start          : new Date('".$fecha2[$i]."  14:00:00'),
      end            : new Date('".$fecha2[$i]."  16:00:00'),
      backgroundColor: '".$colores_turnos[$i]."',
      borderColor    : '".$colores_turnos[$i]."',
      url            : '',
      clbd           : '".$colores_id[$i]."',
      hrbd           : '112',
      datev          : '".$fecha2[$i]." 14:00:00',
      modal          : 'turnosest'
      },
      {
      title          : '".$nombre_turnos[$i]."',
      start          : new Date('".$fecha2[$i]."  16:00:00'),
      end            : new Date('".$fecha2[$i]."  18:00:00'),
      backgroundColor: '".$colores_turnos[$i]."',
      borderColor    : '".$colores_turnos[$i]."',
      url            : '',
      clbd           : '".$colores_id[$i]."',
      hrbd           : '113',
      datev          : '".$fecha2[$i]." 16:00:00',
      modal          : 'turnosest'
      },
      ";
    }
    }//compara la que la nueva fecha no supere a la fecha final para no mostrar el turno

    }//cierra for turnos

    }//cierra for dias

    //dd($events);
    $pdias="";


    $active_calendar='active';
    return view('myforms.frm_calendariogen', compact('active_calendar', 'events','docentes','tipo'));


  } elseif ($tipo=="docentes") {

    $colores=array("#008000","#13caca","#008080","#0000FF","#000080","#FFA07A","#FF00FF","#808080","#000000","#FF0000","#800000", "#CD5C5C","#aeae14","#808000","#1ae91a","#800080","#FA8072","#9944e7","#3adb1c","#db1c63","#1cafdb","#8b144b","#a5530d","#a36a14","#347e0e","#c4ce2c" );
   
    $turnos_doc = turnos_docentes::join('users','turnos_docentes.trnd_docidnumber','=','users.idnumber')
      ->select('turnos_docentes.id','trnd_docidnumber',DB::raw("CONCAT(name,' ', lastname) AS nombre_completo"),'name','trnd_dia','trnd_hora_inicio', 'trnd_hora_fin')
      ->where('trndid_periodo',$periodo->id)
      ->orderBy('trnd_docidnumber','DESC')
      ->get();
    $colores_docentes = [];
    $keys_datos=[];
    $con=0;
    foreach ($turnos_doc as $key => $value) {
      if (!isset($colores_docentes[$value->trnd_docidnumber])) {
        $colores_docentes+=[$value->trnd_docidnumber=>$colores[$con]];
        $con++;
      }
      
      if ($value->trnd_dia == "Lunes") {
        $diaskey1[]=$key;
      } elseif ($value->trnd_dia == "Martes") {
        $diaskey2[]=$key;
      } elseif ($value->trnd_dia == "Miercoles") {
        $diaskey3[]=$key;
      } elseif ($value->trnd_dia == "Jueves") {
        $diaskey4[]=$key;
      } elseif ($value->trnd_dia == "Viernes") {
        $diaskey5[]=$key;
      }
    }
    if (isset($diaskey1)) {$keys_datos +=["Lunes"=> $diaskey1];}
    if (isset($diaskey2)) {$keys_datos +=["Martes"=> $diaskey2];}
    if (isset($diaskey3)) {$keys_datos +=["Miercoles"=> $diaskey3];}
    if (isset($diaskey4)) {$keys_datos +=["Jueves"=> $diaskey4];}
    if (isset($diaskey5)) {$keys_datos +=["Viernes"=> $diaskey5];}

    $asistenciadocentes=AsistenciaDocentes::join('users','asistencia_docentes.docidnumber','=','users.idnumber')
    ->select('asistencia_docentes.id','asistencia_docentes.docidnumber',DB::raw("CONCAT(name,' ', lastname) AS nombre_completo"), 'asistencia_docentes.tipo_asis', 'asistencia_docentes.reposicion', 'asistencia_docentes.inicio', 'asistencia_docentes.fin', 'asistencia_docentes.descripcion')
    ->orderBy('asistencia_docentes.docidnumber','DESC')
    ->get();

    

    $fecha_in=$periodo->prdfecha_inicio;
    $fecha_fi=$periodo->prdfecha_fin;
    $diff_dias = Carbon::parse($fecha_in)->diffInDays(Carbon::parse($fecha_fi));
    $diff_semanas = ceil($diff_dias/7);
    $dt3 = Carbon::parse($fecha_in);
    $dias=[];
    for ($i=0; $i < 7; $i++) { 
      $dia = $dt3->dayOfWeek;
      if ($dia == Carbon::MONDAY) {
        $dias+=["Lunes" => $dt3->format('Y-m-d')];
      }elseif ($dia == Carbon::TUESDAY) {
        $dias+=["Martes" => $dt3->format('Y-m-d')];
      }elseif ($dia == Carbon::WEDNESDAY) {
        $dias+=['Miercoles' => $dt3->format('Y-m-d')];
      }elseif ($dia == Carbon::THURSDAY) {
        $dias+=['Jueves' => $dt3->format('Y-m-d')];
      }elseif ($dia == Carbon::FRIDAY) {
        $dias+=['Viernes' => $dt3->format('Y-m-d')];
      }
      $dt3 = $dt3->addDay(1); 
    }
    
    $events= "";
    $asistencia_array=[];
    foreach ($asistenciadocentes as $key => $value) {
      if ($value->reposicion == '1') {
        $backgroundColor="#cb1919";
        $textcolor="#fff";
        $title="";
        if (isset($colores_docentes[$value->docidnumber])) {
          $textcolor = $colores_docentes[$value->docidnumber];
          $backgroundColor="#fff";
        } else {
          $title = "ERROR NO TIENE HORARIO: ";
        }
        
        $events = $events."
        {
        title          : '".$title.$value->nombre_completo."',
        start          : new Date('".$value->inicio."'),
        end            : new Date('".$value->fin."'),
        backgroundColor: '".$backgroundColor."',
        borderColor    : '#b2b2b2',
        textColor      : '".$textcolor."',
        url            : '',
        clbd           : '".$value->docidnumber."',
        hrbd           : '".$value->id."',
        datev          : '".$value->inicio."',
        modal          : 'turnosdoc',
        registableasis : '1'
        },";
      } else {
        $asistencia_array[$value->docidnumber.$value->inicio.$value->fin]=['type'=>$value->tipo_asis,'id'=>$value->id];
      }
      
    }


    for ($i=0; $i < $diff_semanas; $i++) {
      if (isset($keys_datos["Lunes"])) {
        foreach ($keys_datos["Lunes"] as $key => $value) {
          //comprueba si el turno ya fue registrado en la tabla asistencia
          $backgroundColor="";
          $regis_table_asis="0";
          $regis_asistio="0";
          $idregisdoc="";
          if (isset($asistencia_array[$turnos_doc[$value]->trnd_docidnumber.$dias["Lunes"]." ".$turnos_doc[$value]->trnd_hora_inicio.$dias["Lunes"]." ".$turnos_doc[$value]->trnd_hora_fin])) { 
            $datosasisdoc= $asistencia_array[$turnos_doc[$value]->trnd_docidnumber.$dias["Lunes"]." ".$turnos_doc[$value]->trnd_hora_inicio.$dias["Lunes"]." ".$turnos_doc[$value]->trnd_hora_fin];
            $regis_table_asis="1";
            $idregisdoc = $datosasisdoc['id'];
            if ($datosasisdoc['type'] == '149') {//asistio
              $backgroundColor = "#cef2d9";
              $regis_asistio="1";
            } elseif ($datosasisdoc['type'] == '150') {//permiso
              $backgroundColor = "#f0e1ab";
            } else {
              $backgroundColor = "#f3f2ff";
            }
            
          } else {
            $idregisdoc = $turnos_doc[$value]->id;
            $backgroundColor = "#f3f2ff";
          }
          if (  (\Auth::user()->hasRole('diradmin') || \Auth::user()->hasRole('dirgral') || \Auth::user()->hasRole('amatai')) ||  $regis_asistio=="1" || $regis_table_asis == "0") { //los demas roles no puede ver el turno cuando esta en permiso
          $events = $events."
          {
          title          : '".$turnos_doc[$value]->nombre_completo."',
          start          : new Date('".$dias["Lunes"]." ".$turnos_doc[$value]->trnd_hora_inicio."'),
          end            : new Date('".$dias["Lunes"]." ".$turnos_doc[$value]->trnd_hora_fin."'),
          backgroundColor: '".$backgroundColor."',
          borderColor    : '#c8c8c8',
          textColor      : '".$colores_docentes[$turnos_doc[$value]->trnd_docidnumber]."',
          url            : '',
          clbd           : '".$turnos_doc[$value]->trnd_docidnumber."',
          hrbd           : '".$idregisdoc."',
          datev          : '".$dias["Lunes"]." ".$turnos_doc[$value]->trnd_hora_inicio."',
          modal          : 'turnosdoc',
          registableasis : '".$regis_table_asis."'
          },";
          }
        }
      }
      if (isset($keys_datos["Martes"])) {
        foreach ($keys_datos["Martes"] as $key => $value) {
          //comprueba si el turno ya fue registrado en la tabla asistencia
          $backgroundColor="";
          $regis_table_asis="0";
          $regis_asistio="0";
          $idregisdoc="";
          if (isset($asistencia_array[$turnos_doc[$value]->trnd_docidnumber.$dias["Martes"]." ".$turnos_doc[$value]->trnd_hora_inicio.$dias["Martes"]." ".$turnos_doc[$value]->trnd_hora_fin])) { 
            $datosasisdoc= $asistencia_array[$turnos_doc[$value]->trnd_docidnumber.$dias["Martes"]." ".$turnos_doc[$value]->trnd_hora_inicio.$dias["Martes"]." ".$turnos_doc[$value]->trnd_hora_fin];
            $regis_table_asis="1";
            $idregisdoc = $datosasisdoc['id'];
            if ($datosasisdoc['type'] == '149') {//asistio
              $backgroundColor = "#cef2d9";
              $regis_asistio="1";
            } elseif ($datosasisdoc['type'] == '150') {//permiso
              $backgroundColor = "#f0e1ab";
            } else {
              $backgroundColor = "#f3f2ff";
            }
            
          } else {
            $idregisdoc = $turnos_doc[$value]->id;
            $backgroundColor = "#f3f2ff";
          }
          if (  (\Auth::user()->hasRole('diradmin') || \Auth::user()->hasRole('dirgral') || \Auth::user()->hasRole('amatai')) ||  $regis_asistio=="1" || $regis_table_asis == "0") { //los demas roles no puede ver el turno cuando esta en permiso
          $events = $events."
          {
          title          : '".$turnos_doc[$value]->nombre_completo."',
          start          : new Date('".$dias["Martes"]." ".$turnos_doc[$value]->trnd_hora_inicio."'),
          end            : new Date('".$dias["Martes"]." ".$turnos_doc[$value]->trnd_hora_fin."'),
          backgroundColor: '".$backgroundColor."',
          borderColor    : '#c8c8c8',
          textColor      : '".$colores_docentes[$turnos_doc[$value]->trnd_docidnumber]."',
          url            : '',
          clbd           : '".$turnos_doc[$value]->trnd_docidnumber."',
          hrbd           : '".$idregisdoc."',
          datev          : '".$dias["Martes"]." ".$turnos_doc[$value]->trnd_hora_inicio."',
          modal          : 'turnosdoc',
          registableasis : '".$regis_table_asis."'
          },";
          }
        }
      }
      if (isset($keys_datos["Miercoles"])) {
        foreach ($keys_datos["Miercoles"] as $key => $value) {
          //comprueba si el turno ya fue registrado en la tabla asistencia
          $backgroundColor="";
          $regis_table_asis="0";
          $regis_asistio="0";
          $idregisdoc="";
          if (isset($asistencia_array[$turnos_doc[$value]->trnd_docidnumber.$dias["Miercoles"]." ".$turnos_doc[$value]->trnd_hora_inicio.$dias["Miercoles"]." ".$turnos_doc[$value]->trnd_hora_fin])) { 
            $datosasisdoc= $asistencia_array[$turnos_doc[$value]->trnd_docidnumber.$dias["Miercoles"]." ".$turnos_doc[$value]->trnd_hora_inicio.$dias["Miercoles"]." ".$turnos_doc[$value]->trnd_hora_fin];
            $regis_table_asis="1";
            $idregisdoc = $datosasisdoc['id'];
            if ($datosasisdoc['type'] == '149') {//asistio
              $backgroundColor = "#cef2d9";
              $regis_asistio="1";
            } elseif ($datosasisdoc['type'] == '150') {//permiso
              $backgroundColor = "#f0e1ab";
            } else {
              $backgroundColor = "#f3f2ff";
            }
            
          } else {
            $idregisdoc = $turnos_doc[$value]->id;
            $backgroundColor = "#f3f2ff";
          }
          if (  (\Auth::user()->hasRole('diradmin') || \Auth::user()->hasRole('dirgral') || \Auth::user()->hasRole('amatai')) ||  $regis_asistio=="1" || $regis_table_asis == "0") { //los demas roles no puede ver el turno cuando esta en permiso
          $events = $events."
          {
          title          : '".$turnos_doc[$value]->nombre_completo."',
          start          : new Date('".$dias["Miercoles"]." ".$turnos_doc[$value]->trnd_hora_inicio."'),
          end            : new Date('".$dias["Miercoles"]." ".$turnos_doc[$value]->trnd_hora_fin."'),
          backgroundColor: '".$backgroundColor."',
          borderColor    : '#c8c8c8',
          textColor      : '".$colores_docentes[$turnos_doc[$value]->trnd_docidnumber]."',
          url            : '',
          clbd           : '".$turnos_doc[$value]->trnd_docidnumber."',
          hrbd           : '".$idregisdoc."',
          datev          : '".$dias["Miercoles"]." ".$turnos_doc[$value]->trnd_hora_inicio."',
          modal          : 'turnosdoc',
          registableasis : '".$regis_table_asis."'
          },";
          }
        }
      }
      if (isset($keys_datos["Jueves"])) {
        foreach ($keys_datos["Jueves"] as $key => $value) {
          //comprueba si el turno ya fue registrado en la tabla asistencia
          $backgroundColor="";
          $regis_table_asis="0";
          $regis_asistio="0";
          $idregisdoc="";
          if (isset($asistencia_array[$turnos_doc[$value]->trnd_docidnumber.$dias["Jueves"]." ".$turnos_doc[$value]->trnd_hora_inicio.$dias["Jueves"]." ".$turnos_doc[$value]->trnd_hora_fin])) { 
            $datosasisdoc= $asistencia_array[$turnos_doc[$value]->trnd_docidnumber.$dias["Jueves"]." ".$turnos_doc[$value]->trnd_hora_inicio.$dias["Jueves"]." ".$turnos_doc[$value]->trnd_hora_fin];
            $regis_table_asis="1";
            $idregisdoc = $datosasisdoc['id'];
            if ($datosasisdoc['type'] == '149') {//asistio
              $backgroundColor = "#cef2d9";
              $regis_asistio="1";
            } elseif ($datosasisdoc['type'] == '150') {//permiso
              $backgroundColor = "#f0e1ab";
            } else {
              $backgroundColor = "#f3f2ff";
            }
            
          } else {
            $idregisdoc = $turnos_doc[$value]->id;
            $backgroundColor = "#f3f2ff";
          }
          if (  (\Auth::user()->hasRole('diradmin') || \Auth::user()->hasRole('dirgral') || \Auth::user()->hasRole('amatai')) ||  $regis_asistio=="1" || $regis_table_asis == "0") { //los demas roles no puede ver el turno cuando esta en permiso
          $events = $events."
          {
          title          : '".$turnos_doc[$value]->nombre_completo."',
          start          : new Date('".$dias["Jueves"]." ".$turnos_doc[$value]->trnd_hora_inicio."'),
          end            : new Date('".$dias["Jueves"]." ".$turnos_doc[$value]->trnd_hora_fin."'),
          backgroundColor: '".$backgroundColor."',
          borderColor    : '#c8c8c8',
          textColor      : '".$colores_docentes[$turnos_doc[$value]->trnd_docidnumber]."',
          url            : '',
          clbd           : '".$turnos_doc[$value]->trnd_docidnumber."',
          hrbd           : '".$idregisdoc."',
          datev          : '".$dias["Jueves"]." ".$turnos_doc[$value]->trnd_hora_inicio."',
          modal          : 'turnosdoc',
          registableasis : '".$regis_table_asis."'
          },";
          }
        }
      }
      if (isset($keys_datos["Viernes"])) {
        foreach ($keys_datos["Viernes"] as $key => $value) {
          //comprueba si el turno ya fue registrado en la tabla asistencia
          $backgroundColor="";
          $regis_table_asis="0";
          $regis_asistio="0";
          $idregisdoc="";
          if (isset($asistencia_array[$turnos_doc[$value]->trnd_docidnumber.$dias["Viernes"]." ".$turnos_doc[$value]->trnd_hora_inicio.$dias["Viernes"]." ".$turnos_doc[$value]->trnd_hora_fin])) { 
            $datosasisdoc= $asistencia_array[$turnos_doc[$value]->trnd_docidnumber.$dias["Viernes"]." ".$turnos_doc[$value]->trnd_hora_inicio.$dias["Viernes"]." ".$turnos_doc[$value]->trnd_hora_fin];
            $regis_table_asis="1";
            $idregisdoc = $datosasisdoc['id'];
            if ($datosasisdoc['type'] == '149') {//asistio
              $backgroundColor = "#cef2d9";
              $regis_asistio="1";
            } elseif ($datosasisdoc['type'] == '150') {//permiso
              $backgroundColor = "#f0e1ab";
            } else {
              $backgroundColor = "#f3f2ff";
            }
            
          } else {
            $idregisdoc = $turnos_doc[$value]->id;
            $backgroundColor = "#f3f2ff";
          }
          if (  (\Auth::user()->hasRole('diradmin') || \Auth::user()->hasRole('dirgral') || \Auth::user()->hasRole('amatai')) ||  $regis_asistio=="1" || $regis_table_asis == "0") { //los demas roles no puede ver el turno cuando esta en permiso
          $events = $events."
          {
          title          : '".$turnos_doc[$value]->nombre_completo."',
          start          : new Date('".$dias["Viernes"]." ".$turnos_doc[$value]->trnd_hora_inicio."'),
          end            : new Date('".$dias["Viernes"]." ".$turnos_doc[$value]->trnd_hora_fin."'),
          backgroundColor: '".$backgroundColor."',
          borderColor    : '#c8c8c8',
          textColor      : '".$colores_docentes[$turnos_doc[$value]->trnd_docidnumber]."',
          url            : '',
          clbd           : '".$turnos_doc[$value]->trnd_docidnumber."',
          hrbd           : '".$idregisdoc."',
          datev          : '".$dias["Viernes"]." ".$turnos_doc[$value]->trnd_hora_inicio."',
          modal          : 'turnosdoc',
          registableasis : '".$regis_table_asis."'
          },";
          }
        }
      }
      
      $dias["Lunes"] = Carbon::parse($dias["Lunes"])->addDay(7)->format('Y-m-d');
      $dias["Martes"] = Carbon::parse($dias["Martes"])->addDay(7)->format('Y-m-d');
      $dias["Miercoles"] = Carbon::parse($dias["Miercoles"])->addDay(7)->format('Y-m-d');
      $dias["Jueves"] = Carbon::parse($dias["Jueves"])->addDay(7)->format('Y-m-d');
      $dias["Viernes"] = Carbon::parse($dias["Viernes"])->addDay(7)->format('Y-m-d');
     
    }

    $asistenciadocentes=AsistenciaDocentes::all();//solo llamar las reposisiones
   
    $active_calendar='active';
    return view('myforms.frm_calendariogen', compact('active_calendar', 'events','docentes','tipo'));
  } else {
    return redirect('horarios/estudiantes');
  }
      



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
     //return response()->json($request->idnumberestasis);
      //dd($request->all());
        foreach ($request->idnumberestasis as $key => $idnumber) {
          foreach ($request->idasisestasis as $key_2 => $idasisest) {
           foreach ($request->idlugarestasis as $key_3 => $idlugar) {
             foreach ($request->comentarioestasis as $key_4 => $comentario) {
              foreach ($request->idasis as $key_5 => $id) {
                  if ($key == $key_2 and $key==$key_3 and $key==$key_4 and $key==$key_5) {
                    $data = [
                      'astdescrip_asist'=>$comentario,
                      'astid_estudent'=>$idnumber,
                      'astid_lugar'=>$idlugar,
                      'astfecha'=>$request->fechaestasis,
                      'astid_tip_asist'=>$idasisest,
                      'astusercreated'=>\Auth::user()->idnumber,
                      'astuserupdated'=>\Auth::user()->idnumber,
                    ];
                    if ($id=='undefined' || $id==null) {
                      $asistencia = DB::table('asistencia')
                    ->insert($data);
                    }else{
                      $asistencia = DB::table('asistencia')->where('id',$id)
                    ->update($data);
                    }

                    

                    //echo "num $idnumber -- $idasisest  -- $idlugar -- $comentario  <br>";
                  }
              }
             }
           }
          }
        }
        return redirect('horarios/');
        //return response()->json($request->all());
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
        //
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
        //
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
    public function consultach($color,$horario,$fecha)
{
    $fecha = Carbon::parse($fecha)->format('Y-m-d H:i:s');
      $asistencia= DB::table('asistencia')
                ->join('users',  'users.idnumber','=','asistencia.astid_estudent')
                ->join('referencias_tablas as ref','ref.id','=','users.cursando_id')
                ->select('ref.ref_nombre','asistencia.id as id','users.name', 'users.lastname', 'users.cursando_id', 'users.idnumber', 'asistencia.astid_tip_asist', 'asistencia.astid_lugar', 'astdescrip_asist' )
                ->where('asistencia.astfecha', '=' , $fecha)
                //->orderBy('users.cursando_id', 'asc')
                ->get();
                if (count($asistencia) > 0) {
                    return response()->json($asistencia);
                } else {
        if ($color != "" & $horario !=""){
        if ( $horario == "110" || $horario == "111" ) { $curso1="115"; } elseif ( $horario == "112" || $horario == "113" ) { $curso1="114"; }
        if ( $horario == "110" || $horario == "111" ) { $curso2="117"; } elseif ( $horario == "112" || $horario == "113" ) { $curso2="116"; }
       $turnos= DB::table('turnos')
                ->join('users',  'users.idnumber','=','turnos.trnid_estudent')
                ->join('referencias_tablas as ref','ref.id','=','users.cursando_id')
                ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
                ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
                ->select('ref.ref_nombre','users.name', 'users.lastname', 'users.cursando_id', 'users.idnumber')
                ->where(function ($queryor)use ($color,$horario,$curso1) {
                $queryor->where('turnos.trnid_color', '=' , $color)
                        ->where('turnos.trnid_horario', '=' , $horario)
                        ->where('users.cursando_id', '=' , $curso1);})
                ->orwhere(function ($queryor)use ($color,$horario,$curso2) {
                $queryor->where('turnos.trnid_color', '=' , $color)
                        ->where('users.cursando_id', '=' , $curso2);})
                        ->where('sedes.id_sede',session('sede')->id_sede)
    ->orderBy('users.cursando_id', 'asc')
    ->orderBy('users.idnumber', 'asc')
                ->get();
                //dd($turnos);
           return response()->json(

           $turnos->toArray()

            );
        }
                }




                //dd($asistencia);







}
public function consultahordoc($color,$horario,$fecha)
{
  
    $fecha = Carbon::parse($fecha)->format('Y-m-d H:i:s');
    $turnos_doc = turnos_docentes::join('users','turnos_docentes.trnd_docidnumber','=','users.idnumber')
      ->select('turnos_docentes.id as id','trnd_docidnumber as docidnumber',DB::raw("CONCAT(name,' ', lastname) AS nombre_completo"),'name','trnd_dia as dia','trnd_hora_inicio as inicio', 'trnd_hora_fin as fin')
      ->where('turnos_docentes.id',$horario)
      ->get();

 
           return response()->json(

            $turnos_doc->toArray()

            );
        
                




                //dd($asistencia);







}
public function consultahordocasis($color,$horario,$fecha)
{
  
    $fecha = Carbon::parse($fecha)->format('Y-m-d H:i:s');


      $asistenciadocentes=AsistenciaDocentes::join('users','asistencia_docentes.docidnumber','=','users.idnumber')
      ->select('asistencia_docentes.id','asistencia_docentes.docidnumber',DB::raw("CONCAT(name,' ', lastname) AS nombre_completo"), 'asistencia_docentes.tipo_asis', 'asistencia_docentes.reposicion', 'asistencia_docentes.inicio', 'asistencia_docentes.fin', 'asistencia_docentes.descripcion')
      ->where('asistencia_docentes.id',$horario)
      ->get();

           return response()->json(

            $asistenciadocentes->toArray()

            );
        
}

public function updatehordocasis(Request $request)
{

 // return response()->json($request->all());
    
      $asistenciadocentes=AsistenciaDocentes::where('id',$request->id)
          ->update($request->all());
        

      return response()->json(
        $request->all()
      );
        
}

public function regishordocasis(Request $request)
{
 // return response()->json($request->all());
      $asistenciadocentes=AsistenciaDocentes::create($request->all());
      $request['id']=$asistenciadocentes->id;


      return response()->json(
        $request->all()
      );
        
}

public function agendaper($id)
{
    //
}

public function upagendaper($id)
{
    //
}


}