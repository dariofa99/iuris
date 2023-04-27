<div id="list_users_table">
<div class="box-body table-responsive no-padding">
<table id="tbl_users" class="table table-bordered table-striped dataTable" role="grid">


                  <thead>
                    <tr>
                      <th>Identificación</th>
                      <th>Nombre</th>
                      <th>Email</th>
                      <th>Teléfono1</th>
                      <th>Rol</th>
                      <th>Fecha Reg</th>
                      <th>Activo</th>
                      <th>Editar</th>
                    </tr>
                  </thead>
                <tbody>

                @foreach($users as $user)
                  <tr role="row" class="odd" id="{{ $user->idnumber }}">
                    <td>{{ $user->idnumber }}</td>
                    <td>{{ $user->name }} {{ $user->lastname }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->tel1 }}</td>
                    <td>

                      

                      <center><span class="pull-center badge bg-green">{{$user->display_name}}</span></center>
                      


                      

                    </td>
                    <td>{{ $user->datecreated }}</td>
                    <td>
                      @if($user->active) 
                      <i class="fa fa-toggle-on switch-on btn_switch_estdoc" id="{{$user->id}}"></i>
                        @else
                      <i class="fa fa-toggle-on switch-off btn_switch_estdoc" id="{{$user->id}}"></i>
                      @endif
                    </td>
 
                    <td>{!! link_to_route('users.edit', $title = 'Editar', $parameters = $user->id, $attributes = ['class'=>'btn btn-primary btn-sm']) !!}
  
                    <a onclick='return confirm("¿Está seguro de eliminar el registro..?")' href="{{ route('users.destroy',$user->id) }}" >
                      <button disabled="" class="btn btn-danger btn-sm">
                         Eliminar
                      </button>

                   </a>

                      
                    </td>
                  </tr>
                @endforeach
                </tbody>

    </table> 
    </div>
    {!! $users->appends(request()->query())->links()!!}  
</div>
