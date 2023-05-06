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
           'name' => [
              'required'
          ],
          'lastname' => [
            'required'
        ],
          'email' => [
                  'required','unique:users'
          ],
          'idnumber' => [
            'required','unique:users'
    ]
          ],$messages);


    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()->all()]);
    }
    
 
        $user = $this->userService->store($request);
      
        
   
          //$this->aditionalData($request,$user->id);
          if($request->has('data') and is_array($request->data)){    
                  
            foreach ($request->data as $key => $rq) {
              $rq['user_id'] = $user->id; 
               $ref_data = ReferencesData::where(['name'=>$rq['name'],'section'=>$rq['section']])->first();
                if($ref_data) {  
                    $this->storeData($ref_data,$rq);
                }
            }
        }
          //  $user = User::where('idnumber', '=', $request['idnumber'])->first();         
          $user->roles()->attach( $request->has('idrol') ? $request['idrol'] : 8); 
         
          if(session()->has('sede')){
            $user->sedes()->attach(session('sede')->id_sede);
          }
        Session::flash('message-success', ' Registrado');
        if(Auth::guest()) Auth::login($user);
        return response()->json(['user' => $user]);
        return Redirect::to('/users/create');
    }

    private function storeData($ref_data,$request){

      $data = UserAditionalData::where([
          'reference_data_id'=>$ref_data->id,                
          'user_id'=>$request['user_id']
          ])->first();           
  

      if($data){
          $data->fill([                    
              'value'=>$request["value"],
              'reference_data_option_id'=>$request["option_id"],
              'value_is_other'=>array_key_exists('value_is_other', $request) ? $request["value_is_other"] : "",
          ]);
          $data->save();
      }else{
        if(array_key_exists('option_id', $request) and $request["option_id"]!=null){
          $data = UserAditionalData::create([
            'reference_data_id'=>$ref_data->id,
            'reference_data_option_id'=>$request["option_id"],
            'user_id'=>$request["user_id"],
            'value'=>$request["value"],
            'value_is_other'=>array_key_exists('value_is_other', $request) ? $request["value_is_other"] : "",
        ]);
        }
         
      }
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
      
        $user = User::find($id);
       // dd($user->id);
        if ($user->id != Auth::user()->id and !currentUser()->can("edit_usuarios")) {
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

 
    public function findUser(Request $request){
      //  return  response()->json(['encontrado'=>$request->all()]);
      $user =User::where(['tipodoc_id'=>$request->tipodoc_id,'idnumber'=>$request->idnumber])->first();
        if($user){
          $user->roles;
          //$view = view('myforms.user.componentes.user_form',compact('user'))->render();          
          return response()->json(['encontrado'=>true,'user'=>$user]);   
        }  
          return  response()->json(['encontrado'=>false]);
      }

      public function findUserByNameOrLastNameAndRole(Request $request){
        $users = $this->userService->findUserByNameOrLastNameAndRole($request->name,$request->role);
          
        if(count($users)>0){
          return response()->json(['encontrado'=>true,'users'=>$users]);
        }    
            return  response()->json(['encontrado'=>false]);
        }
}

