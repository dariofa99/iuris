<?php namespace App\Http\Controllers;


use Redirect;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Input;
use App\User;
use App\Expediente;
use App\TablaReferencia;
use Carbon\Carbon; 
use DB;
use Session;
use App\Periodo;
use App\Segmento;
use App\Exports\ExpedientesExport;


 
class ExcelController extends Controller
{


    public function index()
    {
        return view('myforms.frm_reportes_excel');
    }

    public function notas_download()
    {
      $periodo = Periodo::where('estado',1)->first();
      $segmento = Segmento::where('perid',$periodo->id)->first();

      $users = DB::table('users')
      ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
      ->leftjoin('roles' , 'role_user.role_id','=','roles.id')
      ->leftjoin('referencias_tablas' , 'referencias_tablas.id','=','users.cursando_id')
      ->where ('role_id', '6' )
      ->where ('users.active', true)
      ->select('users.active','users.id','ref_nombre','users.idnumber',
        DB::raw('CONCAT(users.name," ",users.lastname) as full_name')
        ,'role_user.role_id', 'roles.display_name')
       ->orderBy('users.created_at', 'desc')
       ->get();

       $nota_caso = DB::select(
        DB::raw("SELECT `estidnumber`, AVG(`nota`) as nota
        FROM `notas` JOIN expedientes on notas.expidnumber=expedientes.expid        
        WHERE `segid` = 8 AND `cptnotaid` != 4 AND expedientes.exptipoproce_id != '3'
        GROUP BY `estidnumber`"));

      $nota_defensas = DB::select(DB::raw(
        "SELECT `estidnumber`, AVG(`nota`)  as nota FROM `notas`
        JOIN expedientes on notas.expidnumber=expedientes.expid 
        WHERE `segid` = 8 AND `cptnotaid` != 4 AND expedientes.exptipoproce_id = '3'
         GROUP BY `estidnumber`"));

$notas_oficina = DB::select(DB::raw(
      "SELECT `estidnumber`, AVG(`nota`) as nota FROM `notas_ext`       
      WHERE `segid` = 8 AND `cptnotaid` != 4  
      GROUP BY `estidnumber`"));

//dd( $nota_ofi );
        $to_excel = [];
         foreach ($users as $key => $user) {
          $data_user = [];
          $data_user[] = $user->idnumber;
          $data_user[] = $user->full_name;
          $data_user[] = $user->ref_nombre;
          $has_ncaso="";
          $nota_final = 0;
         
          if(count($nota_caso)>0) $has_ncaso = array_search($user->idnumber, array_column($nota_caso, 'estidnumber'));
            if($has_ncaso!=""){
              $nota_c = round($nota_caso[$has_ncaso]->nota,1);
              $nota_final += ($nota_c * 0.6);
            }else{
              $nota_c = "N/A";              
              
            }
            $has_ndefensa="";
            if(count($nota_defensas)>0) $has_ndefensa = array_search($user->idnumber, array_column($nota_defensas, 'estidnumber'));
            if($has_ndefensa!=""){
              $nota_d = round($nota_defensas[$has_ndefensa]->nota,1); 
              $nota_final += ($nota_d * 0.2);
            }else{
              $nota_d = "N/A";
              if(is_numeric($nota_c))$nota_final += ($nota_c * 0.2);
            }

            //$nota_con = "N/A";
           // $percent_nota_caso += 20;
           // if(is_numeric($nota_c))$nota_final += ($nota_c * 0.2);


            $has_nota_ofi = "";
            if(count($notas_oficina)>0) $has_nota_ofi = array_search($user->idnumber, array_column($notas_oficina, 'estidnumber'));
            if($has_nota_ofi!=""){
              $nota_ofi = round($notas_oficina[$has_nota_ofi]->nota,1);
              $nota_final += ($nota_ofi * 0.2);
            }else{
              $nota_ofi = "N/A";
              if(is_numeric($nota_c))$nota_final += ($nota_c * 0.2);
            }

           // $final = ()
           //$ncp = ($percent_nota_caso/100);
           $data_user[] = $nota_c;
           $data_user[] = $nota_d;
         //  $data_user[] = $nota_con;
           $data_user[] = $nota_ofi;
           $data_user[] = is_numeric($nota_c) ? round($nota_final,1) : "N/A";
           $to_excel[]  =  $data_user;




          //echo $key_2."--".$user->idnumber."--".$nota."<br>";
         }
         $cabeza = ['cedula','nombres','curso','casos','defensas','otros','final'];
        $this->download($to_excel,$cabeza,'notas_general');


        //return Excel::shareView('myforms.reportes.notas')->create();
        //return redirect()->back();
    }
    


    public function search_data()
    {

     $fillables = [
      'expedientes'=>[
                0 =>[
                  'label'=>'No Expediente', 
                  'value'=>'expid'
                ],
                1=>[
                  'label'=>'Rama del Derecho', 
                  'value'=>'expramaderecho_id'
                ],
                2=>[
                  'label'=>'Fecha de Creación', 
                  'value'=>'expfecha'
                ],
                3=>[
                  'label'=>'Estado', 
                  'value'=>'expestado_id'
                ],
                4=>[
                  'label'=>'Tipo de Proceso', 
                  'value'=>'exptipoproce_id'
                ],
                5=>[
                  'label'=>'Usuario que registro', 
                  'value'=>'expusercreated'
                ],
                6=>[
                  'label'=>'Último usuario que actualizo', 
                  'value'=>'expuserupdated'
                ],
                7=>[
                  'label'=>'Persona Demandada', 
                  'value'=>'exppersondemandada'
                ],
                8=>[
                  'label'=>'Documento de Estudiante', 
                  'value'=>'expidnumberest'
                ],
                9=>[
                  'label'=>'Descripción Corta', 
                  'value'=>'expdesccorta'
                ],
                10=>[
                  'label'=>'Departamento', 
                  'value'=>'expdepto_id'
                ],
                11=>[
                  'label'=>'Municipio', 
                  'value'=>'expmunicipio_id'
                ],
                12=>[
                  'label'=>'Tipo de Vivienda', 
                  'value'=>'exptipovivien_id'
                ],
                13=>[
                  'label'=>'Personas a Cargo', 
                  'value'=>'expperacargo'
                ],
                14=>[
                  'label'=>'Ingresos Mensuales', 
                  'value'=>'expingremensual'
                ],
                15=>[
                  'label'=>'Egresos Mensuales', 
                  'value'=>'expegremensual'
                ],
                 16=>[
                  'label'=>'Hechos', 
                  'value'=>'exphechos'
                ],
                 17=>[
                  'label'=>'Respuesta Estudiante', 
                  'value'=>'exprtaest'
                ],
                 18=>[
                  'label'=>'Juzgado', 
                  'value'=>'expjuzoent'
                ],
                 19=>[
                  'label'=>'Número de Proceso', 
                  'value'=>'expnumproc'
                ],
                 20=>[
                  'label'=>'Persona Demandante', 
                  'value'=>'exppersondemandante'
                ],
                 /*21=>[
                  'label'=>'Persona Demandada', 
                  'value'=>'exppersondemandada'
                ]*/      
      ],
      'usuarios'=>[
                0 =>[
                  'label'=>'Tipo de Documento', 
                  'value'=>'tipodoc_id' 
                ],
                1=>[
                  'label'=>'Documento', 
                  'value'=>'idnumber'
                ],
                2=>[
                  'label'=>'Nombres', 
                  'value'=>'name'
                ],
                3=>[
                  'label'=>'Apellidos', 
                  'value'=>'lastname'
                ],
                4=>[
                  'label'=>'Correo Electrónico', 
                  'value'=>'email'
                ],
                5=>[
                  'label'=>'Teléfono 1', 
                  'value'=>'tel1'
                ],
                6=>[
                  'label'=>'Teléfono 2', 
                  'value'=>'tel2'
                ],
                7=>[
                  'label'=>'Dirección', 
                  'value'=>'address'
                ],
                8=>[
                  'label'=>'Orientación Sexual', 
                  'value'=>'genero_id'
                ],
                9=>[
                  'label'=>'Estrato', 
                  'value'=>'estrato_id'
                ],
                10=>[
                  'label'=>'Estado Civil', 
                  'value'=>'estadocivil_id'
                ],
                11=>[
                  'label'=>'Fecha de Nacimiento', 
                  'value'=>'fechanacimien'
                ],
                12=>[
                  'label'=>'Sisben', 
                  'value'=>'pbesena'
                ],
                13=>[
                  'label'=>'Persona Discapacitada', 
                  'value'=>'pbepersondiscap'
                ],
                14=>[
                  'label'=>'Victima del Desplazamiento y Conflicto', 
                  'value'=>'pbevictimconflic'
                ],
                15=>[
                  'label'=>'Adulto Mayor', 
                  'value'=>'pbeadultomayor'
                ],
                16=>[
                  'label'=>'Minoria Étnica', 
                  'value'=>'pbeminoetnica'
                ],
                17=>[
                  'label'=>'Madre Comunitaria', 
                  'value'=>'pbemadrecomuni'
                ],
                18=>[
                  'label'=>'Cabeza de Familia', 
                  'value'=>'pbecabezaflia'
                ]
           ],
      'actuaciones'=>[
                0 =>[
                  'label'=>'No Expediente', 
                  'value'=>'actexpid' 
                ],
                1=>[
                  'label'=>'Nombre', 
                  'value'=>'actnombre'
                ],
                2=>[
                  'label'=>'Descripción', 
                  'value'=>'actdescrip'
                ],
                3=>[
                  'label'=>'Fecha Creación', 
                  'value'=>'actfecha'
                ],
                4=>[
                  'label'=>'Fecha Modif. Docente', 
                  'value'=>'actdocenfechamod'
                ],
                5=>[
                  'label'=>'Recomendación Docente', 
                  'value'=>'actdocenrecomendac'
                ],
                6=>[
                  'label'=>'Estado', 
                  'value'=>'actestado_id'
                ]
           ],
           'requerimientos'=>[
                0 =>[
                  'label'=>'No Expediente', 
                  'value'=>'reqexpid' 
                ],
                1=>[
                  'label'=>'No Documento Solicitante', 
                  'value'=>'reqidsolicitan'
                ],
                2=>[
                  'label'=>'No Documento Estudiante', 
                  'value'=>'reqidest'
                ],
                3=>[
                  'label'=>'Motivo', 
                  'value'=>'reqmotivo'
                ],
                4=>[
                  'label'=>'Descripción', 
                  'value'=>'reqdescrip'
                ],
                5=>[
                  'label'=>'Fecha', 
                  'value'=>'reqfecha'
                ],
                6=>[
                  'label'=>'Hora', 
                  'value'=>'reqhora'
                ],
                7=>[
                  'label'=>'Comentario Estudiante',
                  'value'=>'reqcomentario_est'
                ],
                8=>[
                  'label'=>'Comentario Coord. de Práctica',
                  'value'=>'reqcomentario_coorprac'
                ],
                9=>[
                  'label'=>'Estado',
                  'value'=>'reqentregado'
                ]
           ]           

     ]; 

  

   return response()->json($fillables);

    }   

public function download($data,$cabeza,$table){
  ob_end_clean(); // this
  ob_start();
  return Excel::download(new ExpedientesExport($data,$cabeza,$table),'Reporte.xlsx');

  set_time_limit(0); 
  ini_set('memory_limit', '1GB');
  if (ob_get_level() > 0) { ob_end_clean(); }
  
			 \Excel::create($table, function($excel) use($data,$cabeza) {
    		$excel->sheet('HOJA_1', function($sheet) use($data,$cabeza) {
    			$sheet->row(1,$cabeza);
    			//$sheet->fromArray($data,null,'A2',false,false);
    			$sheet->fromArray($data,null,'A2',false,false);      		 
       		});    		
			})->download('xlsx'); 
    }
 
    public function generate_data(Request $request){
//
      //dd($request->all());
     // set_time_limit(0);
      if ($request->select_table_excel=='expedientes') {
        
        if ($request->input('fecha_ini') and $request->input('fecha_fin')) {
         if ($request->select_option_table_excel=='todo') {
           $datos = DB::table('expedientes')
          ->join('users','users.idnumber','=','expedientes.expidnumber')
            ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
            ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
            ->orderBy('expedientes.created_at','asc')->get($request->data);


         }else{
          $datos = DB::table('expedientes')
          ->join('users','users.idnumber','=','expedientes.expidnumber')
          ->where($request->filter,$request->select_options_filtro_excel)
            ->whereDate('expedientes.created_at','>=',$request->fecha_ini)
            ->whereDate('expedientes.created_at','<=',$request->fecha_fin)
            ->orderBy('expedientes.created_at','asc')->get($request->data);
         }
        }else{
          if ($request->select_option_table_excel=='todo') {
           $datos = DB::table('expedientes')
          ->join('users','users.idnumber','=','expedientes.expidnumber')
          ->orderBy('expedientes.created_at','asc')->get($request->data);


         }else{
          $datos = DB::table('expedientes')
          ->join('users','users.idnumber','=','expedientes.expidnumber')
          ->where($request->filter,$request->select_options_filtro_excel)
          ->orderBy('expedientes.created_at','asc')->get($request->data);
         }
        }
      }
      
      if ($request->select_table_excel=='actuaciones') {

        if ($request->input('fecha_ini') and $request->input('fecha_fin')) {          
         if ($request->select_option_table_excel=='todo') {
            $datos = DB::table('actuacions')
            ->whereDate('actuacions.created_at','>=',$request->fecha_ini)
            ->whereDate('actuacions.created_at','<=',$request->fecha_fin)
            ->orderBy('actuacions.created_at','asc')->get($request->data);
         }else{
          $datos = DB::table('actuacions')
            ->where($request->filter,$request->select_options_filtro_excel)
            ->whereDate('actuacions.created_at','>=',$request->fecha_ini)
            ->whereDate('actuacions.created_at','<=',$request->fecha_fin)
            ->orderBy('actuacions.created_at','asc')->get($request->data);
         }
       }else{
        if ($request->select_option_table_excel=='todo') {
           $datos = DB::table('actuacions')
            ->orderBy('actuacions.created_at','asc')->get($request->data);
        }else{
          $datos = DB::table('actuacions')
          ->where($request->filter,$request->select_options_filtro_excel)
          ->orderBy('actuacions.created_at','asc')->get($request->data);
        }
       }
     }

      if ($request->select_table_excel=='requerimientos') {

        if ($request->input('fecha_ini') and $request->input('fecha_fin')) {          
         if ($request->select_option_table_excel=='todo') {
            $datos = DB::table('requerimientos')
            ->whereDate('requerimientos.created_at','>=',$request->fecha_ini)
            ->whereDate('requerimientos.created_at','<=',$request->fecha_fin)
            ->orderBy('requerimientos.created_at','asc')->get($request->data);
         }else{
          $datos = DB::table('requerimientos')
            ->where($request->filter,$request->select_options_filtro_excel)
            ->whereDate('requerimientos.created_at','>=',$request->fecha_ini)
            ->whereDate('requerimientos.created_at','<=',$request->fecha_fin)
            ->orderBy('requerimientos.created_at','asc')->get($request->data);
         }
       }else{
        if ($request->select_option_table_excel=='todo') {
           $datos = DB::table('requerimientos')
            ->orderBy('requerimientos.created_at','asc')->get($request->data);
        }else{
          $datos = DB::table('requerimientos')
          ->where($request->filter,$request->select_options_filtro_excel)
          ->orderBy('requerimientos.created_at','asc')->get($request->data);
        }
       }
     }


      $consultas = $this->getReferencias();
    	$data = [];
		foreach ($datos as $key => $value) {
			$con=0;			
			$row = [];
			$cabeza=[];
			foreach ($request->data as $llave => $valor) {
        if ($value->$valor==='null' || $value->$valor===null) {

					$row[$con] = $value->$valor;
				}elseif ($valor=='expramaderecho_id') {
          $label = $this->getValue($consultas['rama_derecho']['options'],$value->$valor);
           $row[$con] = $label;
        }elseif ($valor=='expestado_id') {
           $label = $this->getValue($consultas['estados']['options'],$value->$valor);
          $row[$con] = $label;
        }elseif ($valor=='exptipoproce_id') {
          $label =  $label = $this->getValue($consultas['tipo_procedimiento']['options'],$value->$valor);
          $row[$con] = $label;
        }elseif ($valor=='expdepto_id') {
          $label =  $label = $this->getValue($consultas['departamento']['options'],$value->$valor);
          $row[$con] = $label;
        }elseif ($valor=='expmunicipio_id') {
          $label =  $label = $this->getValue($consultas['municipio']['options'],$value->$valor);
          $row[$con] = $label;
        }elseif ($valor=='exptipovivien_id') {
          $label =  $label = $this->getValue($consultas['tipo_vivienda']['options'],$value->$valor);
          $row[$con] = $label;
        }elseif ($valor=='tipodoc_id') {
          $label =  $label = $this->getValue($consultas['tipo_doc']['options'],$value->$valor);
          $row[$con] = $label;
        }elseif ($valor=='genero_id') {
          $label =  $label = $this->getValue($consultas['genero']['options'],$value->$valor);
          $row[$con] = $label;
        }elseif ($valor=='estadocivil_id') {
          $label =  $label = $this->getValue($consultas['estado_civil']['options'],$value->$valor);
          $row[$con] = $label;
        }elseif ($valor=='estrato_id') {
          $label =  $label = $this->getValue($consultas['estrato']['options'],$value->$valor);
          $row[$con] = $label;
        }elseif ($valor=='actestado_id') {
          $label =  $label = $this->getValue($consultas['estado_act']['options'],$value->$valor);
          $row[$con] = $label;
        }elseif ($valor=='reqentregado') {
          $label =  $label = $this->getValue($consultas['estado_req']['options'],$value->$valor);
          $row[$con] = $label;
        }else{
					$row[$con] = $value->$valor;
          
				}
						
				$con++;
			}
			$data[] = $row;
		}

    if (count($datos)<=0) {
        Session::flash('message-danger', ' La consulta no arrojó datos');
        return redirect('/excel');
    }else{
        return $this->download($data,$request->cabecera,$request->select_table_excel);
    }
    
}

public function get_options($option){
  $options = '';
  if ($option=='rama_derecho') {
    $options = DB::table('rama_derecho')
    ->select('ramadernombre as value','id')->orderBy('ramadernombre','asc')->get();
  }
  if ($option=='estado') {
    $options = DB::table('ref_estados')
              ->select('nombre_estado as value','id')
              ->get(); 
  }
  if ($option=='tipo_procedimiento') {
    $options = DB::table('ref_tipproceso')
              ->select('ref_tipproceso as value','id')
              ->get();  
  }
  if ($option=='tipo_doc') {
    
       $options = DB::table('referencias_tablas')
       ->select('ref_nombre as value','id')
       ->where(['tabla_ref'=>'users','categoria'=>'tipo_doc'])
       ->get();  
    
  }
  if ($option=='genero') {
    
       $options = DB::table('referencias_tablas')
       ->select('ref_nombre as value','id')
       ->where(['tabla_ref'=>'users','categoria'=>'genero'])
       //->where('ref_nombre','<>','Sin definir')
       ->get();  
    
  }
  if ($option=='departamento') {
    
       $options = DB::table('referencias_tablas')
       ->select('ref_nombre as value','id')
       ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_depto'])
       //->where('ref_nombre','<>','Sin definir')
       ->get();     
  }
  if ($option=='municipio') {
    
       $options = DB::table('referencias_tablas')
       ->select('ref_nombre as value','id')
       ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_municipios'])
      // ->where('ref_nombre','<>','Sin definir')
       ->get();     
  }
  if ($option=='tipo_vivienda') {
    
       $options = DB::table('referencias_tablas')
       ->select('ref_nombre as value','id')
       ->where(['tabla_ref'=>'expedientes','categoria'=>'exp_tipo_vivienda'])
      // ->where('ref_nombre','<>','Sin definir')
       ->get();     
  }
  if ($option=='estrato') {
    
       $options = DB::table('referencias_tablas')
       ->select('ref_nombre as value','id')
       ->where(['tabla_ref'=>'users','categoria'=>'estrato'])
     //  ->where('ref_nombre','<>','Sin definir')
       ->get();     
  }
  if ($option=='estado_civil') {
    
       $options = DB::table('referencias_tablas')
       ->select('ref_nombre as value','id')
       ->where(['tabla_ref'=>'users','categoria'=>'estado_civil'])
      // ->where('ref_nombre','<>','Sin definir')
       ->get();     
  }

  if ($option=='estado_act') {
    
       $options = DB::table('referencias_tablas')
       ->select('ref_nombre as value','id')
       ->where(['tabla_ref'=>'actuaciones','categoria'=>'act_estado'])
      // ->where('ref_nombre','<>','Sin definir')
       ->get();     
  }
  if ($option=='estado_req') {    
       $options = [
        0=>(object)([
          'id'=>0,
          'value'=>'Sin Entregar'
        ]),
        1=>(object)([
          'id'=>1,
          'value'=>'Entregado'
        ])
       ]; 
       $options = collect($options);    
  }
  $data = [
    'options'=>$options
  ];
  return ($data);
}

public function search_options(Request $request){

   $data = $this->get_options($request->option);

  return response()->json($data);
}

function getReferencias(){
   
    $consultas = [
      'rama_derecho'=> $this->get_options('rama_derecho'),
      'estados'=>$this->get_options('estado'),
      'tipo_procedimiento'=>$this->get_options('tipo_procedimiento'),
      'tipo_doc'=>$this->get_options('tipo_doc'),
      'departamento'=>$this->get_options('departamento'),
      'municipio'=>$this->get_options('municipio'),
      'tipo_vivienda'=>$this->get_options('tipo_vivienda'),
      'estrato'=>$this->get_options('estrato'),
      'estado_civil'=>$this->get_options('estado_civil'),
      'genero'=>$this->get_options('genero'),
      'estado_act'=>$this->get_options('estado_act'),
      'estado_req'=>$this->get_options('estado_req'),
    ];

    return $consultas;
}

function getValue($options,$id){     
              $value = $id;
              foreach ($options as $key => $data) {
                if ($data->id==$id) {
                  $value = $data->value;
                }
              }
    return $value;
}

public function actionIndex()
 {
  Excel::create('Mi primer archivo excel desde Laravel', function($excel)
  {
   $excel->sheet('Sheetname', function($sheet)
   {
    $sheet->mergeCells('A1:C1');
 
    $sheet->setBorder('A1:F1', 'thin');
 
    $sheet->cells('A1:F1', function($cells)
    {
     $cells->setBackground('#000000');
     $cells->setFontColor('#FFFFFF');
     $cells->setAlignment('center');
     $cells->setValignment('center');
    });
 
    $sheet->setWidth(array
     (
      'D' => '50'
     )
    );
 
    $sheet->setHeight(array
     (
      '1' => '50'
     )
    );
 
    $data=[];
 
    array_push($data, array('Kevin', '', '', 'Arnold', 'Arias', 'Figueroa'));
 
    $sheet->fromArray($data, null, 'A1', false, false);
   });
  })->export('xlsx');
 
  //return View::make('index');
 }

    
}
