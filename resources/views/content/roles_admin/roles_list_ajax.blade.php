<table class="table table-hover">
                <thead>
                  <tr>
                    <th>Nombre largo</th>
                    <th>Nombre corto</th>
                    <th>Descripción</th>                   
                    <th>Acciones</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($roles as $role)
                      <tr>
                      <td>
                      {{$role->display_name}}
                      </td>
                       <td>
                      {{$role->name}}
                      </td>
                       <td>
                      {{$role->description}}
                      </td>
                       <td>
                    @permission('edit_roles')   
                     <a class="btn btn-primary btn_edit_role" id="btn_edit_role-{{$role->id}}">Editar</a>
                   
                   @endpermission
                    @permission('delete_roles')  
                     <a class="btn btn-danger btn_delete_role" id="btn_delete_role-{{$role->id}}">Eliminar</a>
                      
                      @endpermission
                      </td>
                      </tr>
                  @endforeach
                  </tbody>
                  </table>

                 {{ $roles->appends(request()->query())->links() }}