<?php

namespace App\Repositories;

use App\Mail\ConfirmarCorreo;
use App\ReferencesData;
use App\Sede;
use Illuminate\Http\Request;
use App\Services\UsersService;
use App\User;
use App\UserAditionalData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManagerStatic as Image;

class UsersRepository extends BaseRepository implements UsersService
{


  private $verifyStatus;
  public function __construct(User $user)
  {
    parent::__construct($user);
    $this->model = $user;
  }


  ////////
  public function verifyStatus($verifyStatus)
  {
    $this->verifyStatus = $verifyStatus;
    return $this;
  }


  function getAllUsers($request, $relations = null, $perPage = 10): LengthAwarePaginator
  {
    $query = User::criterio($request->data_search, $request->criterio)
      ->whereHas('sedes', function ($query1) {
        $query1->where([
          'sede_id' => session('sede')->id_sede,
        ]);
      })->where(function ($query2) {
        if (!currentUser()->hasRole("amatai")) {
          $query2->whereHas('roles', function ($query3) {
            $query3->where('roles.id', '<>', 1);
          });
        }
      });
    if (!empty($relations)) {
      $query = $this->applyFiltro($query, $relations);
    }
    $query = $this->orderiBy($query, 'created_at', 'DESC');
    return $this->paginateS($query, $perPage);
  }

  protected function orderiBy($query, $col, $type)
  {
    return  $query->orderBy($col, $type);
  }

  protected function applyFiltro($query, $relations = [])
  {
    return $query->with($relations);
  }

  protected function paginateS($query, $perPage)
  {
    $page = Paginator::resolveCurrentPage('page');
    $results = $query->paginate($perPage, ['*'], 'page', $page);
    $results->appends(request()->except('page'));
    return $results;
  }

  public function store(Request $request): User
  {

    $user =  User::create([
      'active' => $request->has('active') ? $request['active'] : 0,
      'tipodoc_id' => $request->has('tipodoc_id') ? $request['tipodoc_id'] : 2,
      'tipopers_id' => $request->has('tipopers_id') ? $request['tipopers_id'] : 237,
      'idnumber' => $request['idnumber'],
      'name' => $request['name'],
      'lastname' => $request['lastname'],
      'password' => $request->has('password') ? bcrypt($request['password']) : ($request['idnumber']),
      'accesofvir' => $request->has('accesofvir') ? $request['accesofvir'] : '',
      'description' =>  $request->has('description') ?  $request['description'] : '',
      'codigo_estudiantil' => $request->has('codigo_estudiantil') ? $request['codigo_estudiantil'] : '',
      'cursando_id' => $request->has('cursando_id') ?  $request['cursando_id'] : 1,
      'email' => $request['email'],
      'tel1' =>  $request->has('tel1') ? $request['tel1'] : '',
      'tel2' =>  $request->has('tel2') ? $request['tel2'] : '',
      'genero_id' => $request->has('genero_id') ? $request['genero_id'] : '1',
      'estrato_id' => $request->has('estrato_id') ? $request['estrato_id'] : '9',
      'estadocivil_id' => $request->has('estadocivil_id') ? $request['estadocivil_id'] : '16',
      'address' => $request->has('address') ? $request['address'] : '',
      'fechanacimien' => $request->has('estrato') ? $request['fechanacimien'] : null,
      'datecreated' => Carbon::now()->format('Y-m-d'),
    ]);

    if ($request->has('data') and is_array($request->data)) {
      foreach ($request->data as $key => $rq) {
        $rq['user_id'] = $user->id;
        $ref_data = ReferencesData::where(['name' => $rq['name'], 'section' => $rq['section']])->first();
        if ($ref_data) {
          $this->storeData($ref_data, $rq);
        }
      }
    }
    $user->roles()->attach($request->has('idrol') ? $request['idrol'] : 8);
    if ($request->has('sede_id')) {
      $sede = Sede::find($request->get('sede_id'));
      session(["sede" => $sede]);
      $user->sedes()->attach(session('sede')->id_sede);
    } else {
      if (session()->has('sede')) {
        $user->sedes()->attach(session('sede')->id_sede);
      }
    }
    return $user;
  }

  public function getUsersByRoleName($role): array
  {

    $this->applyValidateSede();
    $users = $this->query->with(['roles', 'curso'])->whereHas('roles', function ($query) use ($role) {
      return $query->where('roles.name', $role);
    })
      ->where(function ($query) {
        if ($this->verifyStatus) {
          return $query->where('users.active', true);
        }
      })
      ->where(function ($query) {
        if (!currentUser()->hasRole('amatai')) {
          return $query->where('users.idnumber', '<>', 3030);
        }
      })->select(
        'users.active',
        'users.id',
        'users.idnumber',
        'users.cursando_id',
        DB::raw('CONCAT(users.name," ",users.lastname) as full_name')
      )->get();
    return $users->toArray();
  }

  public function findUserByNameOrLastNameAndRole($name, $role, $verify_status = false): array
  {
    $this->applyValidateSede();
    $users = $this->query->with('roles')->whereHas('roles', function ($query) use ($role) {
      return $query->where('roles.name', $role);
    })
      ->where(function ($query) use ($name) {
        return $query->orWhere('users.lastname', 'like', "%{$name}%")
          ->orWhere('users.name', 'like', "%{$name}%");
      })
      ->where(function ($query) use ($verify_status) {
        if ($verify_status) {
          return $query->where('users.active', true);
        }
      })->select(
        'users.active',
        'users.id',
        'users.idnumber',
        DB::raw('CONCAT(users.name," ",users.lastname) as full_name')
      )->get();
    return $users->toArray();
  }

  public function getUsersByPermissionName($permission): Collection
  { 

    $users = User::join('role_user as ru', 'users.id', '=', 'ru.user_id')
      ->join('roles', 'roles.id', '=', 'ru.role_id')
      ->join('permission_role', 'permission_role.role_id', '=', 'roles.id')
      ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
      //->where('users.type_status_id','<>',15)
      ->where('permissions.name', $permission)
      ->select("users.id", 'users.email', 'users.name')
      ->get();

    return $users;
  }

  public function getDocentes(): array
  {
    $users = DB::table('users')
      ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
      ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
      ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
      ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
      ->leftjoin('referencias_tablas', 'referencias_tablas.id', '=', 'users.cursando_id')
      ->where('role_id', '4')
      ->where('users.active', true)
      ->where('sedes.id_sede', session('sede')->id_sede)
      ->select(
        'users.active',
        'users.id',
        'ref_nombre',
        'users.idnumber',
        DB::raw('CONCAT(users.name," ",users.lastname) as full_name'),
        'role_user.role_id',
        'roles.display_name'
      )
      ->orderBy('users.created_at', 'desc')->get();

    return $users->toArray();
  }

  public function getEstudiantes(): array
  {
    $users = DB::table('users')
      ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
      ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
      ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
      ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
      ->leftjoin('referencias_tablas', 'referencias_tablas.id', '=', 'users.cursando_id')
      ->where('role_id', '6')
      ->where('users.active', true)
      ->where('sedes.id_sede', session('sede')->id_sede)
      ->select(
        'users.active',
        'users.id',
        'ref_nombre',
        'users.idnumber',
        DB::raw('CONCAT(users.name," ",users.lastname) as full_name'),
        'role_user.role_id',
        'roles.display_name'
      )
      ->orderBy('users.created_at', 'desc')
      ->get();
    return $users->toArray();
  }

  public function getDocentesByRama($rama): array
  {
    $doceWithRama = $this->model
      ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
      ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id')
      ->leftjoin('user_has_ramasderecho', 'user_has_ramasderecho.user_id', '=', 'users.id')
      ->leftjoin('rama_derecho', 'rama_derecho.id', '=', 'ramaderecho_id')
      ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
      ->where('role_id', '4')
      ->where('rama_derecho.subrama', $rama)
      ->where('users.active', true)
      ->where('users.active_asignacion', true)
      ->where('sede_usuarios.sede_id', session('sede')->id_sede)
      ->select('users.id', 'users.idnumber')
      ->orderBy('users.created_at', 'desc')->get()->toArray();
    return $doceWithRama;
  }






  public function addSede(User $user)
  {
    if ($user) {
      if (!$user->sedes()->wherePivot('sede_id', session('sede')->id_sede)->exists()) {
        $user->sedes()->attach(session('sede')->id_sede);
        return true;
      }
    }
    return false;
  }

  public function changeSede(User $user)
  {
    $user->sedes()->sync(session('sede')->id_sede);
    return true;
  }

  public function update(User $user, Request $request): User
  {
    $user->fill($request->all());
    $user->save();
    if ($request->has('data') and is_array($request->data)) {
      foreach ($request->data as $key => $rq) {
        $rq['user_id'] = $user->id;
        $ref_data = ReferencesData::where(['name' => $rq['name'], 'section' => $rq['section']])->first();
        if ($ref_data) {
          $this->storeData($ref_data, $rq);
        }
      }
    }
    if ($request->email != $user->email) {
      $user->confirm_token = Str::random(50);
      Mail::to($request->email)->send(new ConfirmarCorreo($user));
    }
    if ($request->has('sede_id')) {
      if (count($user->sedes) <= 0) {
        $user->sedes()->attach($request->get('sede_id'));
      } else {
        $user->sedes()->sync($request->get('sede_id'));
      }
    } else {
      if (count($user->sedes) <= 0) {
        if (session()->has('sede')) {
          $user->sedes()->attach(session('sede')->id_sede);
        }
      }
    }
    return $user;
  }

  public function updateProfilePicture(User $user, Request $request): User
  {
    if ($request->image != '') {
      //   $thumbnail = User::find($id);
      $path = public_path() . '/thumbnails/';

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
      $image->resize(215, 215);
      // Guardar
      $image->save($path . '' . $user->idnumber . '.jpg');

      //Guardamos nombre y nombreOriginal en la BD
      //$thumbnail = User::find($id);

      $user->image = $user->idnumber . '.jpg';
      $user->save();
    }
    return $user;
  }

  protected function storeData($ref_data, $request)
  {

    $data = UserAditionalData::where([
      'reference_data_id' => $ref_data->id,
      'user_id' => $request['user_id']
    ])->first();


    if ($data) {
      $data->fill([
        'value' => $request["value"],
        'reference_data_option_id' => $request["option_id"],
        'value_is_other' => array_key_exists('value_is_other', $request) ? $request["value_is_other"] : "",
      ]);
      $data->save();
    } else {
      if (array_key_exists('option_id', $request) and $request["option_id"] != null) {
        $data = UserAditionalData::create([
          'reference_data_id' => $ref_data->id,
          'reference_data_option_id' => $request["option_id"],
          'user_id' => $request["user_id"],
          'value' => $request["value"],
          'value_is_other' => array_key_exists('value_is_other', $request) ? $request["value_is_other"] : "",
        ]);
      }
    }
  }
}
