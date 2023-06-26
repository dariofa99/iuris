<div class="">

    <!-- Left navbar links -->
  
      
      @permission('asig_rol_permisos')
  
      <a href="{{url('/admin/asig')}}" class="btn btn-primary active">Asignaciónes</a>

  @endpermission
   @permission('ver_roles')
 
      <a href="{{url('/admin/roles')}}" class="btn btn-primary">Roles</a>

       @endpermission
 @permission('crear_permisos')
 

      <a href="{{url('/admin/permisos')}}" class="btn btn-primary ">Permisos</a>
      @endpermission
 
 
    <!-- SEARCH FORM -->
   {{--  <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form> --}}

    <!-- Right navbar links -->
  
  </div>