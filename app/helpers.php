


<?php


use Carbon\Carbon;
use App\ReferencesData;
use App\ReferencesStaticData;

function currentUser()
{
     return auth()->user();
}


function currentUserInConciliacion($conciliacion,$roles)
{
    $role = auth()->user()->tipo_conciliacion()->where('conciliacion_id',$conciliacion)->get();
   // dd($role);
    if(is_array($roles)){
        foreach ($role as $key => $rol) {   
            
            if(in_array(strtolower($rol->ref_value),$roles)){
                return true;
           }
        } 
    }
     return  false;

}





function fechasSem($criterio)
{
       
    $date=Carbon::now();
 

    if($criterio=='fechaIni')

    {
          $fecha = $date->subDays(30)->format('Y-m-d'); 

    }elseif ($criterio=='fechaFin') {
        
          $fecha = $date->format('Y-m-d'); 
    }

    return $fecha;

}


function TiempoTrans($criterio)
{       

    Carbon::setLocale('es');       
    $date=Carbon::parse($criterio); 
    $fecha = $date->diffForHumans();  

    return $fecha;

}
function getSmallDate($date){
    $created_at = Carbon::parse($date);   

    $meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    $fecha = $created_at->day.' '.$meses[($created_at->month)-1].". ".$created_at->year;
   
    return $fecha;
}

function getSmallDateWithHour($date){
    $created_at = Carbon::parse($date);   

    $meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    $fecha = $created_at->day.' '.$meses[($created_at->month)-1].". ".$created_at->year.". ".$created_at->format('g:i A');
   
    return $fecha;
}


function getLongDateWithHour($date){
    $created_at = Carbon::parse($date);   

    $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    $fecha = $created_at->day.' '.$meses[($created_at->month)-1]." del ".$created_at->year." a las ".$created_at->format('g:i A');
   
    return $fecha;
}

function getMonthAndYear($date){
    $created_at = Carbon::parse($date);   

    $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    $fecha = $meses[($created_at->month)-1]." de ".$created_at->year;
   
    return $fecha;
}

function fechaActual()
{       


       
    $date=Carbon::now(); 
    $fecha = $date->format('Y-m-d');  

    return $fecha;

}


function parseLongDate($fecha)
{    
if($fecha!==null)  {
    $date=Carbon::parse($fecha); 
    //$fecha = $date->format('Y-m-d');  
    $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    $dia = $date->day;
    if($date->day<10){
        $dia = '0'.$date->day;
    }

    $fecha = $dia.', días del mes de ' .$meses[$date->month - 1].' del año '.$date->year;
     
        return $fecha;
}
return 'sin fecha';

}

function getLettersDays($fecha){
    
    if($fecha!==null)  {
        $date=Carbon::parse($fecha); 
        $dia = $date->day;
         
         //   return $fecha;
    

     $dias = [

        0=>'',

        1=>'un',

        2=>'dos',

        3=>'tres',

        4=>'cuatro',

        5=>'cinco',

        6=>'seis',

        7=>'siete',

        8=>'ocho',

        9=>'nueve',

        10=>'diez',

        11=>'once',

        12=>'doce',

        13=>'trece',

        14=>'catorce',

        15=>'quince',

        16=>'dieciseis',

        17=>'diecisiete',

        18=>'dieciocho',

        19=>'diecinueve',

        20=>'veinte',
        21=>'veintiuno',
        22=>'veintidos',
        23=>'veintitres',
        24=>'veinticuatro',
        25=>'veinticinco',
        26=>'veintiseis',
        27=>'veintisiete',
        28=>'veintiocho',
        29=>'veintinueve',
        30=>'treinta',
        31=>'treintaiuno'

    ];

    return $dias[$dia];
}
return 'sin fecha';


}


function fechaFortatoPer($criterio)
{       
    Carbon::setLocale('es');   
    $date=Carbon::now(); 
    $fecha = $date->format($criterio);  

    return $fecha;

}



function idAleatorio($criterio)
{       
    
    return $criterio;

}




function FullName($criterio1, $criterio2)
{
     return $criterio1." ".$criterio2;
}



function active($url){
    // dd(($url));
     return $url === request()->is($url);
 }





if ( ! function_exists('icon_link_to_route')) {
    /**
     * Create link to named route with glyphicon icon.
     * 
     * @param  string $icon
     * @param  string $route
     * @param  string $title
     * @param  array  $parameters
     * @param  array  $attributes
     * @return string
     */
    function icon_link_to_route($icon, $route, $title = null, $parameters = array(), $attributes = array())
    {
        $url = route($route, $parameters);

        $title = (is_null($title)) ? $url : e($title);

        $attributes = HTML::attributes($attributes);

        $iconpart = '<i class="fa fa-'.e($icon).'"></i>';

        return '<a href="'.$url.'"'.$attributes.'>'.$iconpart.'<span>'.$title.'</span></a>';

    }
}
  function getAditionalDataByShortName($short_name,$table){
    return $aditional_data = ReferencesData::where('short_name',$short_name)
    ->where('table',$table)
    ->first();

  }

  function getReferencesStaticTableBySection($section,$table){
    $ref_data = ReferencesStaticData::where([
        'section'=>$section,
        'table'=>$table,
        'is_visible'=>1
        ])->get();
    if ($ref_data) return $ref_data;            
    
   return false; 
}

function getReferencesDataBySection($section,$table){
    $rdata_enf_dif = ReferencesData::where([
        'section' => $section, 
        'table'=>$table,
        'is_visible'=>1
        ])->get();
    if ($rdata_enf_dif) return $rdata_enf_dif;            
    
   return false; 
}

function getReferencesTableByCategory($category){
    $rollist = DB::table('referencias_tablas')
    ->select('id','ref_nombre')
    ->where('categoria',$category)
    //->where('categoria',$category)
    ->get();

    return $rollist;

}

?>