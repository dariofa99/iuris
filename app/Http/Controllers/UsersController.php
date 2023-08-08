<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use \App\User;
use Intervention\Image\ImageManagerStatic as Image;
use App\TablaReferencia; 
use App\Mail\ConfirmarCorreo;
use App\ReferenceDataOptions;
use App\ReferencesData; 
use App\Services\UsersService;
use App\UserAditionalData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller 
{

  private $userService; 

  public function __construct(UsersService $userService)
  {
    $this->userService = $userService;
    $this->middleware('auth', ['except' => ['store', 'anotherMethod']]);
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
       
      //$user = User::find(9);
     // Auth::login(($user));
       
      //return response()->json([ 'user' =>$request->all() ]);
      
        $messages = [          
          'name.required' => 'El nombre es requerido.',
          'lastname.required' => 'El apellido es requerido.',
          'email.unique' => 'El :attribute  ya existe en otra cuenta.',
          'email.required' => 'El :attribute es requerido.',
          'idnumber.required' => 'El número de documento es requerido.',
          'idnumber.unique' => 'El número de documento ya existe en otra cuenta.',
          ];
      $validator = Validator::make($request->all(), [
          'name' => ['required'],
          'lastname' => ['required'],
          'email' => ['required','unique:users'],
          'idnumber' => ['required','unique:users']
          ],$messages);


    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()->all()]);
    }
    $user = $this->userService->store($request);
    if(Auth::guest()) Auth::login($user);
    return response()->json(['user' => $user]);
       // return Redirect::to('/users/create');
    }




private function aditionalData($request,$id){
      if($request->has('reference_data_id')){   
        foreach ($request->reference_data_id as $key => $rd_id) {
            $var = "value_".$rd_id;
            $data = $request->$var;             
            $var2 = "value_text_".$rd_id;
            $data_text = $request->$var2; 
            $value_other_text = 'value_other_text_'.$rd_id;
            $data_value_other_text = $request->$value_other_text;
            $reference = ReferencesData::find($rd_id); 
            $uad = UserAditionalData::where([
                'reference_data_id'=>$rd_id,
                'user_id'=>$id,             
                ])
            ->first(); 
            if($data){                
                if($uad){                   
                    if($reference->type_data_id==168){
                        $uad->value = $data_text[0];
                        $uad->save();
                    }elseif ($reference->type_data_id==169) {
                        //si es opcion multiple unica respuesta                
                        $op_value = ReferenceDataOptions::find($data[0]);
                        if($op_value){
                            $uad->value = $op_value->value;
                            $uad->value_is_other = $data_value_other_text != null ? $data_value_other_text[0] : '';
                            $uad->reference_data_option_id = $data[0];
                            $uad->save();
                        }else{
                            $uad_del = UserAditionalData::where([
                                'reference_data_id'=>$rd_id,
                                'user_id'=>$id,
                                //'reference_data_option_id'=>$option
                                ])
                            ->delete();
                        }
                       
                    }elseif ($reference->type_data_id==170) {
                        //si es opcion multiple varias respuestas
                        $uad_del = UserAditionalData::where([
                            'reference_data_id'=>$rd_id,
                            'user_id'=>$id,
                            //'reference_data_option_id'=>$option
                            ])
                        ->delete();
                        foreach ($data as $key_2 => $option) {
                            $op_value = ReferenceDataOptions::find($option);
                                $uad = UserAditionalData::create([
                                    'reference_data_id'=>$rd_id,
                                    'user_id'=>$id,
                                    'reference_data_option_id'=>$option,
                                    'value'=>$op_value->value,
                                    'value_is_other'=>$data_value_other_text != null ? $data_value_other_text[0] : '',
                                    ]);
                        }

                    }
                }else{
                    if($reference->type_data_id==168){
                        //dd($data_value_other_text);
                        $uad = UserAditionalData::create([
                            'reference_data_id'=>$rd_id,
                            'user_id'=>$id,
                            'reference_data_option_id'=>$data[0],
                            'value'=>$data_text != null ? $data_text[0] : '',
                            'value_is_other'=>$data_value_other_text != null ? $data_value_other_text[0] : '',
                        ]);
                    }else{
                        foreach ($data as $key_2 => $option) {
                            $op_value = ReferenceDataOptions::find($option);
                            if($op_value){
                                $uad = UserAditionalData::create([
                                    'reference_data_id'=>$rd_id,
                                    'user_id'=>$id,
                                    'reference_data_option_id'=>$option,
                                    'value'=>$op_value->value,
                                    'value_is_other'=>$data_value_other_text != null ? $data_value_other_text[0] : '',
                                    ]);
                            }
                              
                        }
                    }
                }        
            }else{
                if($uad){
                    $uad_del = UserAditionalData::where([
                        'reference_data_id'=>$rd_id,
                        'user_id'=>$id,
                        //'reference_data_option_id'=>$option
                        ])
                    ->delete();
                }
            }
        }   
    }
    return true;
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
      
/*         $user = User::find($id);
        if(!$user){
          Session::flash('message-warning', "Ups! No se ha encontrado al usuario"); 
          return redirect("/conciliaciones"); 
      } 
        if ($user->id != Auth::user()->id and !currentUser()->can("edit_usuarios")) {
            return view('errors.error'); 
        }

        $active_users='active'; 
         
        return view('myforms.frm_myusers_edit', ['user'=>$user], compact('active_users')  );
     */
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
       $user = $this->userService->setValidateSede(false)->find($id); 
      
       $messages = [
        'email.unique' => 'El :attribute  ya existe en otra cuenta.',
        'email.required' => 'El :attribute es requerido.',     
        'idnumber.unique' => 'El número de documento ya existe en otra cuenta.',   
      ];
    $validator = Validator::make($request->all(), [
      'email' => ['required',Rule::unique('users')->ignore($user->id)        
      ],
      'idnumber' => [
                        Rule::unique('users')->ignore($user->id)    
    ]
        ],$messages);
               

                if ($validator->fails()) {
                  
                    if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
                      return response()->json(['errors' => $validator->errors()->all()]);
                    }
                    return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
                }
             
    $user = $this->userService->update($user,$request);
  
    if($request->get('ramaderecho_id')){
        $user->ramas_derecho()->sync($request['ramaderecho_id']);
   
    }
    if($request->has('sede_id') and $request->get('sede_id')!=null){
      $user->sedes()->sync($request['sede_id']);     
    }

    if($request->get('id_rol')){
      $user->roles()->sync($request['id_rol']);
    }
       
    if ((currentUser()->hasRole('estudiante') and $user->hasRole('estudiante'))  and (!$user->turno) ) {
      $user->asignarTurno($request);
    }
   
   if ((currentUser()->hasRole('estudiante') and $user->hasRole('estudiante')) and (!$user->docente_asignado)) {
       $user->asignarDocente($request);
   }



        


       //$user->roles()->sync($request['idrol']);

  if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
    return response()->json(['user'=>$user]);
  }
    if(!$email_request) Session::flash('message-success', 'Actualizado con éxito..');
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

 
    public function findUserWithFilter(Request $request){
      $response=[]; 
      $encontrado = false;
      $sin_sede = false;
      try {
        $user = $this->userService->findWithFilter([
          'tipodoc_id'=>$request->tipodoc_id,
          'idnumber'=>$request->idnumber]);
      } catch (\Throwable $th) {
        $user = false;
      }
    
    if($user){ 
      $encontrado = true;
      } else{
        try {
          $sin_sede = true;
          $response['sin_sede'] = true;
          $user = $this->userService->setValidateSede(false)->findWithFilter([
            'tipodoc_id'=>$request->tipodoc_id,
            'idnumber'=>$request->idnumber]);
        } catch (\Throwable $th) {
          $user = false;
        }        
          if($user){ 
            $encontrado = true;               
          }
        } 
      if($encontrado){
        $user->roles;       
        if($request->has('view')){
          $response['view'] = view($request->get('view'),compact('user','sin_sede'))->render(); 
        }
            $response['encontrado'] =true;
            
            $response['user'] = $user;   
            return response()->json($response);  
      }
        return  response()->json(['encontrado'=>false]);
      }

      public function findUserByNameOrLastNameAndRole(Request $request){
        //return  response()->json(['encontrado'=>$request->all()]);
        
        $users = $this->userService->findUserByNameOrLastNameAndRole($request->name,
        $request->role,
        $request->has('validate_active')?$request->get('validate_active'):true);
        if(count($users)>0){
          return response()->json(['encontrado'=>true,'users'=>$users]);
        }    
            return  response()->json(['encontrado'=>false]);
        }

        public function getUsersByRoleName(Request $request){
          //return  response()->json(['encontrado'=>$request->all()]);
      //    $users = $this->userService->getDocentes();
         $users = $this->userService->getUsersByRoleName($request->role);
          if(count($users)>0){
            return response()->json(['encontrado'=>true,'users'=>$users]);
          }    
              return  response()->json(['encontrado'=>false]);
          }

        public function findUsersByIdNumber(Request $request){
          
          $user = $this->userService->getWithFilter(['idnumber'=>$request->idnumber]);
          if(($user)!==null){
            return response()->json(['encontrado'=>true,'users'=>$user]);
          }    
              return  response()->json(['encontrado'=>false]);
          }

    public function addSede(Request $request){
          $user = $this->userService->setValidateSede(false)->find($request->id);
          if($user){
            if($request->has('action')){
              if($request->get('action')=='add'){
                $add = $this->userService->addSede($user);
              }
              if($request->get('action')=='change'){
                $add = $this->userService->changeSede($user);
              }
            }
           
            if($add){
              if($request->has('view')){
                $view = view($request->get('view'))->render(); 
                return response()->json(['agregado'=>true,'user'=>$user,'view'=>$view]);
              }
              return response()->json(['agregado'=>true,'user'=>$user]);   
            }       
          }
         return  response()->json(['agregado'=>false]);
    }

    public function uploadProfilePicture(Request $request){
      try {
        $user = $this->userService->find($request->id);
        if($user){
          $user = $this->userService->updateProfilePicture($user,$request);

          return  response()->json(['user'=>$user]);

        }
      } catch (\Throwable $th) {
        return  response()->json(['errors'=>$th]);
      }
      return  response()->json(['errors'=>false]);
  }

  public function pruebas(Request $request){
    try {
      $users = $this->userService->setValidateSede(false)
      ->findUserByNameOrLastNameAndRole("User","solicitante");

      /* $users = User::whereHas('roles', function ($query) {
        return $query->where('roles.name', 'docente');
    })->get(); */
    dd($users);
      return  response()->json($users);
    } catch (\Throwable $th) {
      dd($th);
      return  response()->json(['errors'=>$th]);
    }
    return  response()->json(['errors'=>false]);
}

}

