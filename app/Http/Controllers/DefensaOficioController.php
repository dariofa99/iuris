<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Expediente;
use DB;
use App\User;
use App\AsignacionCaso;
use Session;
use Validator;
use Facades\App\Facades\NewPush;
use App\Notifications\UserNotification;


class DefensaOficioController extends Controller
{
  public function __construct()
  {
      
      //$this->middleware('permission:edit_usuarios',   ['only' => ['edit']]);
      $this->middleware('permission:crear_defensas_oficio',   ['only' => ['create']]);
  }
    /**
     * Display a listing of the resource. 
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
    {
       
array_map('unlink', glob(public_path('act_temp/'.currentUser()->id.'___*')));//elimina los archivos que el usuario a visualizado anteriormente.(provisional)
    // dd($request->all());  

    //$date = Carbon::now();
/*
      $users = User::where('id', currentUser()->id)
        ->get(['id', 'name', 'lastname' ,'idrol']);


     foreach ($users as $user) 
     {
       $idrol=$user->idrol;
     }*/
     //dd($idrol);
     
   


//dd($request->all());
     //validacion fechas en caso que aun no envien variables get
      if (empty($request->get('tipo_busqueda'))){

        $criterio= '';
        $fechaini= fechasSem('fechaIni');
        $fechafin= fechasSem('fechaFin');
     $numpaginate='20';

      }else{

        $fechaini= fechasSem('fechaIni');
        $fechafin= fechasSem('fechaFin');
        $criterio= $request->data;
        //$fechaini=$request->get('fechaini');
        //$fechafin=$request->get('fechafin');
     $numpaginate='100';


      }


      if (currentUser()->hasRole("estudiante")) {
          
          if (!empty($request->get('tipo_busqueda'))) {
            
            if ((is_null($request->dataIni))) {
                //Si no es rango de fechas
             $expedientes= Expediente::where('expidnumberest', '=', currentUser()->idnumber)->Criterio($request->data,$request->tipo_busqueda)->orderBy(DB::raw("FIELD(expestado,'3','1','4','2')"))->paginate(10);
            $numEx= Expediente::where('expidnumberest', '=', currentUser()->idnumber)->Criterio($request->data,$request->tipo_busqueda)->count();
            
            }else{

            $expedientes= Expediente::where('expidnumberest', '=', currentUser()->idnumber)->RangoFechas($request->dataIni,$request->dataFin)->orderBy(DB::raw("FIELD(expestado,'3','1','4','2')"))->paginate(10);

            $numEx= Expediente::where('expidnumberest', '=', currentUser()->idnumber)->RangoFechas($request->dataIni,$request->dataFin)->count();


            }
            

          }else{
            //Por defecto.. estudiante 
             $expedientes= Expediente::where('expidnumberest', '=', currentUser()->idnumber)->orderBy(DB::raw("FIELD(expestado,'3','1','4','2')"))->orderBy(DB::raw("created_at"), 'desc')->paginate(10);
             $numEx= Expediente::where('expidnumberest', '=', currentUser()->idnumber)->count(); 

          }

          //$numEx = count($expedientes);
      }elseif (currentUser()->hasRole("docente")){
         //$expedientes= Expediente::Criterio($criterio)->orderBy('created_at', 'desc')->paginate($numpaginate);
         
         if (!empty($request->get('tipo_busqueda'))) {
            /*$expedientes= Expediente::Criterio($request->data,$request->tipo_busqueda)->orderBy(DB::raw("FIELD(expestado,'4','1','2','3')"))->orderBy(DB::raw("created_at"), 'desc')->paginate($numpaginate); 
*/
            if (is_null($request->dataIni)) {
              //si la consulta no es rango
             $expedientes= Expediente::Criterio($request->data,$request->tipo_busqueda)->orderBy(DB::raw("created_at"), 'desc')->orderBy(DB::raw("FIELD(expestado,'4','1','3','2')"))->paginate($numpaginate);

            $numEx= Expediente::Criterio($request->data,$request->tipo_busqueda)->count();
            }else{
//rango
              $expedientes= Expediente::RangoFechas($request->dataIni,$request->dataFin)->orderBy(DB::raw("created_at"), 'desc')->orderBy(DB::raw("FIELD(expestado,'4','1','3','2')"))->paginate($numpaginate);

            $numEx= Expediente::RangoFechas($request->dataIni,$request->dataFin)->count();
            }
                     
          }else{
            //Por defecto docente
       
      $expedientes= Expediente::orderBy(DB::raw("FIELD(expestado,'4','1','3','2')"))->orderBy(DB::raw("created_at"), 'desc')->paginate($numpaginate);
        //es para colocar de primero los que se registran dia a dia
        //$date = Carbon::now();
       //$fechaactual=$date->toDateString();
      //$exped_abiertos_ac=Expediente::where('expfecha', '=', $fechaactual)->where('expestado', '=', '1');

          $numEx= Expediente::orderBy('created_at', 'desc')->count();



          }


          //$numEx = count($expedientes);     
//$expediente= Expediente::orderBy(DB::raw("FIELD(expestado,'2','1','4','3')"))->orderBy(DB::raw("created_at"), 'desc')->first();

  

      }else{


         if (!empty($request->get('tipo_busqueda'))) {

          if (is_null($request->dataIni)) {
            $expedientes= Expediente::Criterio($request->data,$request->tipo_busqueda)->orderBy(DB::raw("created_at"), 'desc')->paginate($numpaginate);
            $numEx= Expediente::Criterio($request->data,$request->tipo_busqueda)->count();
          }else{
            $expedientes= Expediente::RangoFechas($request->dataIni,$request->dataFin)->orderBy(DB::raw("created_at"), 'desc')->paginate($numpaginate);
            $numEx= Expediente::RangoFechas($request->dataIni,$request->dataFin)->count();
          }
            
 
          }else{

          $expedientes= Expediente::orderBy(DB::raw("created_at"), 'desc')->where('exptipoproce',3)->paginate($numpaginate);
          $numEx= Expediente::orderBy('created_at', 'desc')->count();
          }
      }
      $request = $request->all();
     // $expedientes = Expediente::orderBy('expestado','ASC')->get()->toArray();
    //  sort($expedientes);
     
      $userSel = $this->getEstudiantes();
      $solicitantesSel = $this->getSolicitantes();
      //$numEx = count($expedientes);
       $active_expe ='active';
       return view('myforms.frm_defensa_oficio_list',compact('expedientes','active_expe','numEx','request','userSel','solicitantesSel'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

 $active_expe ='active';
         $users = $this->getEstudiantes();
          // $user = User::with('role')->where('role.id',6)->get();

           //dd($user);

        return view('myforms.frm_defensa_oficio_create',['active_expe'=>$active_expe,'users'=>$users]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //dd($request->all());
        $date = date('Y-m-d');
        $ced = time(); 
        $messages = [
            'expid' => 'El número de Expediente ya existe.',
        ];    

        $validator = Validator::make($request->all(), [
            'expid' => 'required|unique:expedientes',
        ],$messages);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($messages)
                        ->withInput();
        }


         $user=User::create([
            'idnumber'=>'00'.$ced,
            'name'=>'Vacío',
            'lastname'=>'Vacío',
            'email'=>$ced.'user@correo.com',
            'password'=>bcrypt('1234'),
            'genero_id' => '6',
            'estrato_id' => '9',
            'estadocivil_id' =>'16',  
            'tipodoc_id' => 1,             
            'cursando_id'=>1
         ]);
        $user->roles()->attach(8); 

          $expediente = new Expediente();
          $expediente->expid = $request['expid'];
          $expediente->expusercreated = currentUser()->idnumber;
          $expediente->expuserupdated = currentUser()->idnumber;
          $expediente->expidnumber = $user->idnumber;
          $expediente->expidnumberest = $request['expidnumberest'];
          $expediente->expramaderecho_id = $request['expramaderecho_id'];
          $expediente->expfechalimite = $request['expfechalimite'];
          $expediente->expestado_id = 1;
          $expediente->exptipoproce_id =3;
          $expediente->exptipocaso_id = 22;
          $expediente->exptipovivien_id=90;
          $expediente->expdepto_id=96;
          $expediente->expmunicipio_id=24;
          $expediente->expfecha = $date ;
          $expediente->save();
 
        $asignacion_caso = new AsignacionCaso();
        $asignacion_caso->anotacion='asignado';
        $asignacion_caso->asigest_id = $request['expidnumberest'];
        $asignacion_caso->asiguser_id=currentUser()->idnumber;
        $asignacion_caso->asigexp_id=$request['expid'];
        $asignacion_caso->periodo_id=$request['periodo_id'];
        $asignacion_caso->ref_asig_id=1;
        $asignacion_caso->ref_mot_asig_id=1;
        $asignacion_caso->save();
        if(session()->has('sede')){
          $expediente->sedes()->attach(session('sede')->id_sede);
        }
      $expedientes = $this->getExpEstu($request['expidnumberest']);
      $numEx = count($expedientes);
      $render = view('myforms.frm_expediente_list_ajax',compact('expedientes','numEx'))->render();
       $user = $expediente->estudiante;
        $user->notification = 'Nueva notificación de caso';
        $user->link_to = '/defensas/oficio/'.$expediente->expid.'/edit';
        $user->mensaje = 'Se ha asignado una defensa de oficio. Número: '.$expediente->expid;     
        $user->notify(new UserNotification($user)); 
        $notifications = view('layouts.notifications',compact('user'))->render();
        NewPush::channel('notifications_'.$request['expidnumberest']) 
        ->message(['render'=>$render,'notifications'=>$notifications])->publish();   

         return redirect('/expedientes');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $active_expe ='active';
         $expediente = Expediente::where('expid',$id)->first();
         $user = $this->getestudiantes(); 

         if(currentUser()->hasRole('estudiante')){
            if($expediente->expidnumberest != \Auth::user()->idnumber){
              $url = '/expedientes/';
              return view('errors.error',compact('url'));  
            }
         }
        return view('myforms.frm_defensa_oficio_show',compact('expediente','active_expe','user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
    $expediente = Expediente::where('expid',$id)->first();
    $active_expe ='active'; 
    $user = $this->getEstudiantes(); 
    $asignacion = $expediente->asignaciones()->where(['asigest_id'=>$expediente->expidnumberest,'activo'=>1])->first();

    if (currentUser()->hasRole("estudiante")) {
         if (\Auth::user()->id != $expediente->estudiante->id) {
          $url = '/expedientes/';
           return view('errors.error',compact('url'));
         }
         if ($expediente->expestado =='4') {
          Session::flash('message-success', 'Actualizado con éxito...!');
           return redirect('/defensas/oficio/'.$expediente->id);
         }
       } 

       return view('myforms.frm_defensa_oficio_edit',compact('user','expediente','active_expe','asignacion'));
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }


    public function getEstudiantes(){
       $users= DB::table('users')
           ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
           ->leftjoin('roles' , 'role_user.role_id','=','roles.id')
           ->where ('role_id', '6' )
           
           ->select('users.id','users.idnumber',
            
             DB::raw('CONCAT(users.name," ",users.lastname) as full_name')
             ,'role_user.role_id', 'roles.display_name')->orderBy('users.created_at', 'desc')->pluck( 'full_name' ,'users.idnumber');
           return $users;
    }

    public function getSolicitantes(){
       $users= DB::table('users')
           ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
           ->leftjoin('roles' , 'role_user.role_id','=','roles.id')
           ->where ('role_id', '8' )
           
           ->select('users.id','users.idnumber',
            
             DB::raw('CONCAT(users.name," ",users.lastname) as full_name')
             ,'role_user.role_id', 'roles.display_name')->orderBy('users.created_at', 'desc')->pluck( 'full_name' ,'users.idnumber');
           return $users;
    }

    private function getExpEstu($idnumber){
      return $expedientes= Expediente::join('asignacion_caso','expedientes.expid','=','asignacion_caso.asigexp_id')
      ->where('asignacion_caso.asigest_id', '=', $idnumber)
      ->where('expidnumberest', '=', $idnumber)
      ->orderBy(DB::raw("FIELD(expestado_id,'3','1','4','2','5')"))
      ->orderBy(DB::raw("asignacion_caso.created_at"), 'desc')
      ->groupBy ('asignacion_caso.asigexp_id')  
      ->paginate(10);  
    }
}
