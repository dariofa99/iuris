<table class="table table-hover" >
                <thead>
                  <tr>
                    <th>Nombre largo</th>
                    <th>Nombre corto</th>
                    <th>Descripción</th>                   
                    <th>Acciones</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($permissions as $permission)
                      <tr>
                      <td>
                      {{$permission->display_name}}
                      </td>
                       <td>
                      {{$permission->name}}
                      </td>
                       <td>
                      {{$permission->description}}
                      </td>
                       <td>
                     <a class="btn btn-primary btn_edit_permission" id="btn_edit_permission-{{$permission->id}}">Editar</a>
                     <a class="btn btn-danger btn_delete_permission" id="btn_delete_permission-{{$permission->id}}">Eliminar</a>
                      </td>
                      </tr>
                  @endforeach
                  </tbody>
                  </table>

                 {{ $permissions->appends(request()->query())->links() }}