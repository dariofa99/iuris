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
                    <td id="useremail-{{ $user->id }}">{{ $user->email }}</td>
                    <td>{{ $user->tel1 }}</td>
                    <td>

                      

                      <center><span class="pull-center badge bg-green">
                        {{ (count($user->roles)>0) ? $user->roles[0]->display_name : "Sin rol"}}</span></center>
                      


                      

                    </td>
                    <td>{{ getSmallDate($user->created_at) }}</td>
                    <td>
                      @if($user->active) 
                      <i class="fa fa-toggle-on switch-on btn_switch_estdoc" data-estado="{{$user->active}}" id="{{$user->id}}"></i>
                        @else
                      <i class="fa fa-toggle-on switch-off btn_switch_estdoc" data-estado="{{$user->active}}" id="{{$user->id}}"></i>
                      @endif
                    </td>
 
                    <td>{!! link_to_route('users.edit', $title = 'Editar', $parameters = $user->id, $attributes = ['class'=>'btn btn-primary btn-block btn-sm']) !!}
  
                   {{--  <a onclick='return confirm("¿Está seguro de eliminar el registro..?")' href="{{ route('users.destroy',$user->id) }}" >
                      <button disabled="" class="btn btn-block btn-danger btn-sm">
                         Eliminar
                      </button>

                   </a> --}}

                      
                    </td>
                  </tr>
                @endforeach
                </tbody>

    </table> 
    </div>
    {!! $users->appends(request()->query())->links()!!}  
</div>
