<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use \App\User;
use Intervention\Image\ImageManagerStatic as Image;
use DB; 
use App\TablaReferencia; 
use App\Mail\ConfirmarCorreo;
use App\Services\UsersService;
use Illuminate\Support\Facades\Mail;  

class MyusersController extends Controller 
{

  private $userService;

  public function __construct(UsersService $userService)
  {
    $this->userService = $userService;
     // $this->middleware('permission:edit_usuarios',   ['only' => ['edit']]);
      $this->middleware('permission:ver_usuarios',   ['only' => ['index']]);
  }
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function users(Request $request){
      $users_list = DB::table('tipo_nota')->get();
      return $users_list;
    }  

    public function index(Request $request)
    {


       //$users_list = $this->getUsers();
       $criterio =  '';
   
        $users = $this->getUsers($request);
       
       $active_users='active';
        
       if ($request->ajax()) {
        //return response()->json($users);
        return view('myforms.frm_myusers_list_ajax', compact('users'))->render();
       }
     
       return view('myforms.frm_myusers_list', compact('users', 'active_users','criterio'));

    }
    public function index_page(Request $request){
      $users = $this->getUsers($request);

       //$active_users='active';
       //dd($active_users);
       return view('myforms.frm_myusers_list_ajax', compact('users'))->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        
        $active_users='active';  
        $tipodoc = TablaReferencia::where(['categoria'=>'tipo_doc','tabla_ref'=>'users'])
        ->pluck('ref_nombre','id'); 
         return view('myforms.frm_myusers', compact('active_users','tipodoc'));
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       //return $request->all();
      if(!$request->ajax()){
      
        $messages = [
          'email.unique' => 'El :attribute  ya existe en otra cuenta.',
          'email.required' => 'El :attribute es requerido.',
          'idnumber.unique' => 'El número de documento ya existe en otra cuenta.',
      ];
      $validator = Validator::make($request->all(), [
          'email' => [
                  'required','unique:users'
          ],
          'idnumber' => [
            'required','unique:users'
    ]
          ],$messages);


    if ($validator->fails()) {
        return redirect()->back()
                ->withErrors($validator)
                ->withInput();
    }
  }
        $date = Carbon::now();
        $user = User::create([
            'active' => $request['active'],
            'tipodoc_id' => $request['tipodoc'], 
            'idnumber' => $request['idnumber'],
            'name' => $request['name'],
            'lastname' => $request['lastname'],
            'password' => $request->has('password') ? $request['password'] : bcrypt($request['idnumber']),
            'accesofvir' => $request['accesofvir'],
            'description' => $request['description'],
            'codigo_estudiantil' => $request['institution'],
            'cursando_id' => 1,
            'email' => $request['email'],
            'tel1' => $request['tel1'],
            'tel2' => $request['tel2'],
            'genero_id' => '6',
            'estrato_id' => '9',
            'estadocivil_id' =>'16',             
            'address' => $request['address'],
            'estrato' => $request['estrato'],
            'fechanacimien' => $request['fechanacimien'],
            'datecreated' =>$date = $date->format('Y-m-d'),
        ]);

          //  $user = User::where('idnumber', '=', $request['idnumber'])->first();
          $user->roles()->attach($request['idrol']); 
          if(session()->has('sede')){
            $user->sedes()->attach(session('sede')->id_sede);
          }
        Session::flash('message-success', ' Registrado');
        return Redirect::to('/users/create');
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

    public function userStore(Request $request)
    {
      //return response()->json($request->all());

      if ($request->ajax()) {   
        if($request->has('id') and $request->id!=''){
          $user =  User::find($request->id);
        }  else{
          $request['genero_id'] = ($request->has('genero_id')) ? $request->genero_id : 6;
          $request['estrato_id'] = ($request->has('estrato_id')) ? $request->estrato_id : 9;
          $request['estadocivil_id'] = ($request->has('estadocivil_id')) ? $request->estadocivil_id : 16;
          $request['cursando_id'] = ($request->has('cursando_id')) ? $request->cursando_id : 1;
          $request['password'] = ($request->has('password')) ? $request->password : 'iuris'.$request->idnumber;
          $user = new User($request->all());   
          $user->save();
        }       
        if($request->has('oficina_id')) $user->oficinas()->sync($request->oficina_id);
        if($request->has('idrol')) $user->roles()->attach($request['idrol']);
        return response()->json($request->all());
      } 
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
        $user = User::find($id);
       // dd($user->id);
        if ($user->id != \Auth::user()->id and !currentUser()->can("edit_usuarios")) {
            return view('errors.error'); 
        }

        $active_users='active'; 
         
        return view('myforms.frm_myusers_edit', ['user'=>$user], compact('active_users')  );
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
 
      $email_request = false;
       $user = User::find($id);


       $messages = [
        'email.unique' => 'El :attribute  ya existe en otra cuenta.',
        'email.required' => 'El :attribute es requerido.',     
        'idnumber.unique' => 'El número de documento ya existe en otra cuenta.',   
    ];
    $validator = Validator::make($request->all(), [
      'email' => [
        'required',
          Rule::unique('users')->ignore($user->id)        
      ],
      'idnumber' => [
                        Rule::unique('users')->ignore($user->id)    
    ]
        ],$messages);
               

                if ($validator->fails()) {
                    return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
                }
              //  dd($request->all());
          if($request->email!=$user->email){
            $user->confirm_token = (str_random(50));
            Mail::to($request->email)->send(new ConfirmarCorreo($user)); 
            $email_request = true; 
            Session::flash('message-success', "Actualizado con éxito. Por favor confirma nuevamente tu cuenta de correo electrónico."); 
          }

        $user->fill($request->all()); 
        $user->save();    
      if($request->get('id_rol')){
          $user->role()->sync($request['id_rol']);
        //dd($user->role()->sync($request['id_rol']));
      }
      if($request->has('sede_id') and $request->get('sede_id')!=null){
        $user->sedes()->sync($request->sede_id);
      }

      if($request->get('ramaderecho_id')){
          $user->ramas_derecho()->sync($request['ramaderecho_id']);
     
      }
       
       



      if($request->image!=''){
         //   $thumbnail = User::find($id);
         $path = public_path().'/thumbnails/';

         /*if ($thumbnail->image!='') {
             //\File::delete($path.''.$thumbnail->idnumber.'.jpg');
         }*/
 
         // $file = \Input::file('image');
          //Creamos una instancia de la libreria instalada   
         // Image::configure(array('driver' => 'profile_files'));
           $image = Image::make($request->image);
          //Ruta donde queremos guardar las imagenes
          
 
          // Guardar Original
          //$image->save($path.$file->getClientOriginalName());
          // Cambiar de tamaño
          $image->resize(215,215);
          // Guardar
          $image->save($path.''.$user->idnumber.'.jpg');
          
          //Guardamos nombre y nombreOriginal en la BD
          //$thumbnail = User::find($id);
          
          $user->image = $user->idnumber.'.jpg';
     $user->save();
  }

        $asig=true;
        $asigt = true;
       
        if (currentUser()->hasRole('estudiante') and (!$user->turno) ) {
           $asigt = $user->asignarTurno($request);
        }
        
        if (currentUser()->hasRole('estudiante') and (!$user->docente_asignado)) {
            $asig = $user->asignarDocente($request);

        }

        if(!$email_request) Session::flash('message-success', 'Actualizado con éxito..');
        if (!$asig and !$asigt) {          
          //Session::flash('message-warning', 'Atención.! Consulta con el coordinador para la asignación de DOCENTE y TURNO');
        }elseif(!$asig){
          //Session::flash('message-warning', 'Atención.! Consulta con el coordinador para la asignación de DOCENTE');
        }elseif (!$asigt) {
          Session::flash('message-warning', 'Atención.! Consulta con el coordinador para la asignación de TURNO');
        }



       //$user->roles()->sync($request['idrol']);


       
       return Redirect::to('users/'.$user->id.'/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return Redirect::to('/users'); 

    }

    public function changeStateUser (Request $request){

        $user = User::find($request->id);
        if ($user->active) {
           $user->active = false;
        }else{
             $user->active = true;
        }
        $user->save();
        return response()->json($user);

    }

    function getUsers(Request $request){
       
             $users= User::leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
           ->leftjoin('roles' , 'role_user.role_id','=','roles.id')
           ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
           ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
           ->Criterio($request->data_search,$request->criterio)           
           ->select('users.active','users.id','users.idnumber',
            'users.name', 'users.lastname', 'users.email',
             'users.tel1','users.tel2','users.datecreated'
             ,'role_user.role_id', 'roles.display_name',
             'sedes.nombre as sede')           
             ->where('sedes.id_sede',session('sede')->id_sede)
            ->groupBy('users.id')
            ->orderBy('users.created_at', 'desc')
            ->paginate(20);
    

        return $users;

    }

    public function indexEst(Request $request){

        if (isset($request->data_search)) {
            $users= DB::table('users')
           ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
           ->leftjoin('roles' , 'role_user.role_id','=','roles.id')
           ->leftjoin('referencias_tablas' , 'referencias_tablas.id','=','users.cursando_id')
           ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
           ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
           ->where('role_user.role_id', '=','6')
            ->where('users.active', '=',1)
           ->where('users.cursando_id', '=',$request->data_search)
           ->where('sedes.id_sede',session('sede')->id_sede)           
           ->select('users.active','users.id','users.idnumber',
            'users.name', 'users.lastname', 'users.email','referencias_tablas.ref_nombre',
             'users.tel1','users.tel2','users.datecreated'
             ,'role_user.role_id', 'roles.display_name')->orderBy(DB::raw("users.name"), 'asc')
           ->paginate(100);
          $data_search = $request->data_search; 
        }else{
            $users= DB::table('users')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('roles' , 'role_user.role_id','=','roles.id')
            ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
            ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
            ->leftjoin('referencias_tablas' , 'referencias_tablas.id','=','users.cursando_id')
            ->where('role_user.role_id', '=','6')
            ->where('users.cursando_id','<>','1')
            ->where('users.active', '=',1)
            ->where('sedes.id_sede',session('sede')->id_sede)
            ->select('users.active','users.id','users.idnumber',
              'users.name', 'users.lastname', 'users.email','referencias_tablas.ref_nombre',
             'users.tel1','users.tel2','users.datecreated'
             ,'role_user.role_id', 'roles.display_name')
            ->orderBy(DB::raw("referencias_tablas.ref_nombre"), 'asc')
            ->paginate(20);
        }
      $active_config = 'active';
      return view('myforms.frm_mystudents_list',compact('users','active_config'));


    }

    public function cursoEmpty(Request $request){
     
        foreach ($request->idnumber as $key => $idnumber) {         
                $user = User::where('idnumber',$idnumber)->first();
                if ($request->curso_selected=='116' || $request->curso_selected=='117') {
                  $user->active=0;
                }                
                $user->cursando_id=1;
                $user->save();             
         }
        return redirect('/students');

    }
 
    public function getEstudiantes(Request $request){
          $users = $this->userService->getEstudiantes();

        if ($request->ajax()) {
            return response()->json($users);
        }    
      return ($users);
     }

  public function getDocentes(Request $request){
            $users = $this->userService->getDocentes();
            if (currentUser()->hasRole("amatai") or currentUser()->hasRole("diradmin") || currentUser()->hasRole('dirgral')) {
              array_push($users,[
                'active'=>1,
                'display_name'=>"Docente",
                "full_name"=>auth()->user()->name." ".auth()->user()->lastname,
                "id"=>auth()->user()->id,
                "idnumber"=>auth()->user()->idnumber,
                "ref_nombre"=>"Sin definir",
                "role_id"=>4
              ]);
            }
          

           // return response()->json(["name"=>'user']);

       if ($request->ajax()) {
            return response()->json($users);
        }    
      return ($users);
     }


    public function getSolicitantes(Request $request){
       $users= DB::table('users')
           ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
           ->leftjoin('roles' , 'role_user.role_id','=','roles.id')
           ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
           ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
           ->join ('expedientes', 'idnumber', '=', 'expidnumber')
           ->where ('role_id', '8' )
           ->where ('users.name','<>', 'Sin asignar')
           ->where ('users.lastname','<>', 'Sin asignar')
           ->where ('users.name','<>','Vacio')
           ->where ('expestado_id','<>','2')
           ->where('sedes.id_sede',session('sede')->id_sede)
           ->select('users.id','users.idnumber',            
             DB::raw('CONCAT(users.name," ",users.lastname) as full_name')
             ,'role_user.role_id', 'roles.display_name')->orderBy('users.created_at', 'desc')->get();
            if ($request->ajax()) {
            return response()->json($users);
        }    
      return ($users);
    }

    public function getAllusers(Request $request,$id){

      $users= DB::table('users')
          ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
          ->leftjoin('roles' , 'role_user.role_id','=','roles.id')
          ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
          ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
          ->where ('role_id','>', '5' )         
          ->where ('users.name','<>', 'Sin asignar')
          ->where ('users.lastname','<>', 'Sin asignar')
          ->where ('users.name','<>','Vacio')
          ->where ('users.name', 'like', "%{$id}%")
          ->where('sedes.id_sede',session('sede')->id_sede)
          ->orWhere ('users.lastname', 'like', "%{$id}%")
          ->select('users.id','users.idnumber',            
            DB::raw('CONCAT(users.name," ",users.lastname) as full_name')
            ,'role_user.role_id')->orderBy('users.lastname', 'asc')->get();
           if ($request->ajax()) {
           return response()->json($users);
       }    
     return ($users);
   }

public function confirm_email(Request $request,$token){
      $user = \Auth::user();
      if($user!==null){
        if($user->confirm_token!=null and $user->confirm_token == $token){
          $user->confirm_token = null;
          $user->save();
          Session::flash('message-success', 'Tu cuenta ha sido confirmada con éxito.');
          return redirect('/users/'.$user->id.'/edit');     
        }else{
          return redirect('/login');
        }
        return redirect('/users/'.$user->id.'/edit');  
      }

        return redirect('/login');
      }


      public function findUser(Request $request){
      $user =User::where(['tipodoc_id'=>$request->tipodoc_id,'idnumber'=>$request->idnumber])->first();
        if($user){
          $user->roles;
          return response()->json(['encontrado'=>true,'user'=>$user]);   
        }  
          return  response()->json(['encontrado'=>false]);
      }
}

