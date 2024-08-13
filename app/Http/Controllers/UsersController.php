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
use App\Mail\ValidateAccount;
use App\ReferenceDataOptions;
use App\ReferencesData;
use App\Services\ExpedientesService;
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
  private $expedienteService;

  public function __construct(UsersService $userService, ExpedientesService $expedienteService)
  {
    $this->userService = $userService;
    $this->expedienteService = $expedienteService;
    $this->middleware('permission:ver_usuarios',   ['only' => ['index']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function users(Request $request)
  {
    $users_list = DB::table('tipo_nota')->get();
    return $users_list;
  }

  public function index(Request $request)
  {


    //$users_list = $this->getUsers();
    $criterio =  '';

    $users = $this->getUsers($request);

    $active_users = 'active';

    if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
      $view = view('myforms.frm_myusers_list_ajax', compact('users'))->render();

      return response()->json($view);
    }

    return view('myforms.frm_myusers_list', compact('users', 'active_users', 'criterio'));
  }
  public function index_page(Request $request)
  {
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

    $active_users = 'active';
    $tipodoc = TablaReferencia::where(['categoria' => 'tipo_doc', 'tabla_ref' => 'users'])
      ->pluck('ref_nombre', 'id');
    return view('myforms.frm_myusers', compact('active_users', 'tipodoc'));
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

    /*  return response()->json([
      'itemsInData' =>$itemsInData,
      'itemsInRequest' =>$itemsInRequest,
      'itemsDiff' =>$itemsDiff,

   ]); */

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
      'email' => ['required', 'unique:users'],
      'idnumber' => ['required', 'unique:users']
    ], $messages);


    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()->all()]);
    }
    $user = $this->userService->store($request);
    if (Auth::guest()) Auth::login($user);
    return response()->json(['user' => $user]);
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
      'email' => [
        'required',
        Rule::unique('users')->ignore($user->id)
      ],
      'idnumber' => [
        Rule::unique('users')->ignore($user->id)
      ]
    ], $messages);


    if ($validator->fails()) {

      if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
        return response()->json(['errors' => $validator->errors()->all()]);
      }
      return redirect()->back()
        ->withErrors($validator)
        ->withInput();
    }



    if ($request->get('ramaderecho_id')) {
      $user->ramas_derecho()->sync($request['ramaderecho_id']);
    }
    if ($request->has('sede_id') and $request->get('sede_id') != null) {
      $user->sedes()->sync($request['sede_id']);
    }

    if ($request->get('id_rol')) {
      $user->roles()->sync($request['id_rol']);
    }
    $user = $this->userService->update($user, $request);

    if ((currentUser()->hasRole('estudiante')
        and $user->hasRole('estudiante'))
      and ($user->turno === null)
    ) {
      $user->asignarTurno($request);
    } elseif ($request->has('cursando_id') and $user->turno) {
      $request['cursando_id'] = $user->cursando_id;
      $user = $this->userService->update($user, $request);
    }

    if ((currentUser()->hasRole('estudiante') and $user->hasRole('estudiante')) and (!$user->docente_asignado)) {
      $user->asignarDocente($request);
    }


    if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
      return response()->json(['user' => $user]);
    }
    if (!$email_request) Session::flash('message-success', 'Actualizado con éxito..');
    return Redirect::to('users/' . $user->id . '/edit');
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

  public function changeStateUser(Request $request)
  {

    $user = User::find($request->id);
    if ($user->active) {
      $user->active = false;
    } else {
      $user->active = true;
    }
    $user->save();
    return response()->json($user);
  }


  public function findUserWithFilter(Request $request)
  {
    $response = [];
    $encontrado = false;
    $sin_sede = false;
    try {
      $user = $this->userService->findWithFilter([
        'tipodoc_id' => $request->tipodoc_id,
        'idnumber' => $request->idnumber
      ]);
    } catch (\Throwable $th) {
      $user = false;
    }

    if ($user) {
      $encontrado = true;
    } else {
      try {
        $sin_sede = true;
        $response['sin_sede'] = true;
        $user = $this->userService->setValidateSede(false)->findWithFilter([
          'tipodoc_id' => $request->tipodoc_id,
          'idnumber' => $request->idnumber
        ]);
      } catch (\Throwable $th) {
        $user = false;
      }
      if ($user) {
        $encontrado = true;
      }
    }
    if ($encontrado) {
      $user->roles;
      $expedientes = $this->expedienteService->getExpeUser($user);
      $response['expedientes'] = $expedientes;
      if ($request->has('view')) {
        $response['view'] = view($request->get('view'), compact('user', 'sin_sede'))->render();
      }
      $response['encontrado'] = true;
      $response['user'] = $user;

      return response()->json($response);
    }
    return  response()->json(['encontrado' => false]);
  }

  public function findUserByNameOrLastNameAndRole(Request $request)
  {
    //return  response()->json(['encontrado'=>$request->all()]);

    $users = $this->userService->findUserByNameOrLastNameAndRole(
      $request->name,
      $request->role,
      $request->has('active') ? $request->get('active') : true
    );
    if (count($users) > 0) {
      return response()->json(['encontrado' => true, 'users' => $users]);
    }
    return  response()->json(['encontrado' => false]);
  }

  public function getUsersByRoleName(Request $request)
  {
    //return  response()->json(['encontrado'=>$request->all()]);
    //    $users = $this->userService->getDocentes();
    if ($request->has('active') and ($request->input('active') === true or $request->input('active') == 1)) {
      $users = $this->userService->verifyStatus(true)
        ->getUsersByRoleName($request->role);
    } else {
      $users = $this->userService->getUsersByRoleName($request->role);
    }

    if (count($users) > 0) {
      $auth = auth()->user();
      return response()->json(['encontrado' => true, 'users' => $users, 'auth' => $auth]);
    }
    return  response()->json(['encontrado' => false]);
  }

  public function getUsersByIdNumber(Request $request)
  {

    if ($request->has('active') and ($request->input('active') === true or $request->input('active') == 1)) {
      $user = $this->userService
        ->getWithFilter(['idnumber' => $request->idnumber, 'active' => 1]);
    } else {
      $user = $this->userService->getWithFilter(['idnumber' => $request->idnumber]);
    }

    $user = $this->userService->getWithFilter(['idnumber' => $request->idnumber]);
    if (($user) !== null) {
      return response()->json(['encontrado' => true, 'users' => $user]);
    }
    return  response()->json(['encontrado' => false]);
  }

  public function addSede(Request $request)
  {
    $user = $this->userService->setValidateSede(false)->find($request->id);
    if ($user) {
      if ($request->has('action')) {
        if ($request->get('action') == 'add') {
          $add = $this->userService->addSede($user);
        }
        if ($request->get('action') == 'change') {
          $add = $this->userService->changeSede($user);
        }
      }

      if ($add) {
        if ($request->has('view')) {
          $view = view($request->get('view'))->render();
          return response()->json(['agregado' => true, 'user' => $user, 'view' => $view]);
        }
        return response()->json(['agregado' => true, 'user' => $user]);
      }
    }
    return  response()->json(['agregado' => false]);
  }

  public function uploadProfilePicture(Request $request)
  {
    try {
      $user = $this->userService->find($request->id);
      if ($user) {
        $user = $this->userService->updateProfilePicture($user, $request);

        return  response()->json(['user' => $user]);
      }
    } catch (\Throwable $th) {
      return  response()->json(['errors' => $th]);
    }
    return  response()->json(['errors' => false]);
  }

  public function validateSolicitudEmail(Request $request)
  {


    try {
      $user = $this->userService->findWithFilter([
        "confirm_token" => $request->token,
        "email" => $request->oldemail
      ]);
      //return  response()->json($user);
      $messages = [
        'email.unique' => 'El :attribute  ya existe en otra cuenta.',
        'email.required' => 'El :attribute es requerido.',

      ];
      $validator = Validator::make($request->all(), [
        'email' => [
          'required',
          Rule::unique('users')->ignore($user->id)
        ],
        'idnumber' => [
          Rule::unique('users')->ignore($user->id)
        ]
      ], $messages);

      if ($validator->fails()) {

        if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
          return response()->json(['errors' => $validator->errors()->all()]);
        }
        return redirect()->back()
          ->withErrors($validator)
          ->withInput();
      }
      if ($user) {

        $user->confirm_token = str_replace("/", "", bcrypt(\Str::random(5)));
        $user->save();
        //Session::flash('message-danger', 'Error! Recuerda escribir un correo electrónico valido, ya que se enviará una confirmación.');
        Mail::to($request->email)->send(new ValidateAccount($user, $request->email));
      }

      //dd($user);
      return  response()->json($user);
    } catch (\Throwable $th) {
      
      return  response()->json(['errors' => ["El token es invalido, vuelva a iniciar sesión para recuperarlo"]]);
    }
    return  response()->json(['errors' => ["El token es invalido, vuelva a iniciar sesión para recuperarlo"]]);
  }
  public function activateAccount(Request $request, $token)
  {
    $user = $this->userService->findWithFilter([
      "confirm_token" => $token
    ]);   
    try {
    

      if ($user and $request->has("email")) {
        //
        $user->email = $request->email;
        $user->active = 1;
        $user->confirm_token = null;
        $user->save();
     
        Session::flash('message-info', 'La cuenta se activó con éxito, ahora puede iniciar sesión');
        return  redirect("/login");
      } else {
        abort(404);
      }
    } catch (\Throwable $th) {
      abort(404);
      return  response()->json(['errors' => $th]);
    }
    abort(404);
    return  response()->json(['errors' => false]);
  }
}
