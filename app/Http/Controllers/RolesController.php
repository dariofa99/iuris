<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Permission;
use DB;

class RolesController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
        $this->middleware('permission:ver_roles',   ['only' => ['index']]);
        $this->middleware('permission:asig_rol_permisos',   ['only' => ['admin']]);
        $this->middleware('permission:crear_permisos',   ['only' => ['admin']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin(Request $request)
    {
        //dd('');
         $roles = $this->getRoles($request);
          if($request->ajax()){
              $data = $this->getRolesPermissions($request);
            return $data;
        }

        return view('content.roles_admin.admin_panel',compact('roles'));
    } 

    public function index(Request $request)
    {
        $roles = $this->getRoles($request);
        if($request->ajax()){
            return view('content.roles_admin.roles_list_ajax',compact('roles'))->render();
        }
        return view('content.roles_admin.rol_admin',compact('roles'));
        
    }

    private function getRoles(Request $request){
      $roles = Role::orderBy('description','asc')->orderBy('name','asc')->paginate(6);
        $roles->each(function($roles){
            $roles->permissions;
        });
        return $roles;      
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
      //   return response()->json($request->name);
        $role = new Role($request->all());
        /* $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->description = $request->description; */
        $role->save();

        return response()->json($role);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $role = Role::find($id);
         return response()->json($role);
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
        $role =  Role::find($id);
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->description = $request->description; 
        $role->save();

        return response()->json($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $role =  Role::whereId($id)->delete();

        return response()->json($role);
    }

    public function getRolesPermissions(Request $request){

        $roles = Role::orderBy('description','asc')->get();
        $permissions = Permission::orderBy('description','asc')->get();

    $data = [
       'roles'=>$roles,
       'permissions'=>$permissions,        
    ];
return $data;
   //return response()->json($data);

   }

   public function syncPermissionRole(Request $request){
        $role = Role::find($request->role_id);
        if ($request->fire=='create') {
            $role = DB::table('permission_role')->insert(['permission_id'=>$request->permission_id,'role_id'=>$request->role_id]);
        }

        if ($request->fire=='delete') {
            $role = DB::table('permission_role')->where(['permission_id'=>$request->permission_id,'role_id'=>$request->role_id])->delete();
        }
        

        return response()->json($request->all());
    }

    public function getPermissionsRole(Request $request){
       
        $roles = Role::orderBy('description','asc')->orderBy('name','asc')->get();
        $roles->each(function($roles){
            $roles->permissions;
        });
        return response()->json($roles);
    }

    public function change_permissions(Request $request){
        $permisos = [];
        if ($request->type_s=='delete') {
           $permissions = DB::table('permission_role')->where('role_id',$request->role_id)->delete();
        }else{
           $permissions = DB::table('permission_role')->where('role_id',$request->role_id)->delete(); 
           $permissions = DB::table('permissions')->get();           
           foreach ($permissions as $key => $permission) {
               $permisos [] = [
                'permission_id'=>$permission->id,
                'role_id'=>$request->role_id,                
               ];

           }
           DB::table('permission_role')->insert($permisos);

        }

       return response()->json(1);
    }
}
