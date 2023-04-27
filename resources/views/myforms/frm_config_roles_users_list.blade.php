@extends('layouts.dashboard')
@section('area_forms')

@include('msg.success')


@section('titulo_general')
Configuración
@endsection

@section('titulo_area')
Roles y Usuarios
@endsection




<div class="row">

{!!Form::model(Request::all(),['route'=>'users.index', 'method'=>'GET'])!!}


 <div class="col-sm-2">
    <div class="btn-group" role="group" aria-label="...">
     

      <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Excel
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <li>{!! link_to('usuarios/importar', 'Importar')!!}</li>
         
        </ul>
      </div>

      {!! link_to('users/create', 'Nuevo', $attributes = array('type'=>'button', 'class'=>'btn btn-default'))!!}

          

    </div>



  </div>


                <div class="col-sm-2">

                 <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  {!!Form::text('fechaini', '2012-12-12', ['class' => 'form-control', 'required' => 'required','data-inputmask'=>"'alias': 'yyyy/mm/dd'" , 'data-mask'] ); !!}
                </div>
                <!-- /.input group -->
                </div>
            
              <!-- /.form group -->

              <div class="col-sm-2">
              <!-- Date mm/dd/yyyy -->
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask value="2018-12-12"
                  >
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <select class="form-control">
                    <option>Cedula</option>
                    <option>Nombre</option>
                   
                  </select>
                </div>
              </div>


              <div class="col-md-2">
                <div class="form-group">                 
                  {!!Form::text('criterio', null, ['class' => 'form-control', 'placeholder'=>'Criterio'] ); !!}
                </div>
              </div>


              <div class="col-md-2">
                <div class="form-group">
                  <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
              </div>


{!!Form::close()!!}


</div>

<br>


<div id='divc'>
<div class="row">
  <div class="col-sm-12">
  <div class="box-body table-responsive no-padding">
    <table id="tbl_users" class="table table-bordered table-striped dataTable" role="grid">
                

                  <thead>
                    <tr>
                      <th>Identificación</th>
                      <th>Nombre</th>
                      <th>Role</th>
                      <th>Fecha Reg</th>
                      <th>Editar</th>
                    </tr>
                  </thead>
                <tbody>
                
                @foreach($users as $user)             
                  <tr role="row" class="odd">
                    <td>{{ $user->idnumber }}</td>
                    <td>{{ $user->name }}</td>
                    <td>


                      @foreach($user->roles as $roles)                                       
                         <center><span class="pull-center badge bg-green">{{ $roles->display_name }}</span></center>                   
                      @endforeach 

   



                    </td>
                    <td>{{ $user->datecreated }}</td>
                    <td>{!! link_to_route('users.edit', $title = 'Editar', $parameters = $user->id, $attributes = ['class'=>'btn btn-primary btn-sm']) !!}</td>
                  </tr>
                @endforeach  
                </tbody>
                
    </table>
    </div>
    {!! $users->render()!!}
  </div>
</div>

<div>
              @stop