@extends('layouts.dashboard')
@section('area_forms')

@include('msg.success')

<div class="row">

{!!Form::model(Request::all(),['route'=>'students.index', 'method'=>'GET'])!!}

               <div class="col-md-1">
                  Buscar un curso
               </div>


 
              <div class="col-md-2">

                <div class="form-group">
                 {!!Form::select('data_search',$cursando,null,['placeholder' => 'Selecciona...', 'class' => 'form-control', 'required' => 'required','id'=>'sel_cur_search' ]); !!}  
                </div>
              </div>


              {{-- <div class="col-md-2">
                <div class="form-group">                 
                  {!!Form::text('criterio', null, ['class' => 'form-control', 'placeholder'=>'Criterio'] ); !!}
                </div>
              </div> --}}


              <div class="col-md-3">
                
                  <button type="submit" class="btn btn-success">Buscar</button>
               
                 <a href="/students" class="btn btn-default">Ver todo</a>

              </div>


{!!Form::close()!!}


</div>

<br>
{!!Form::open(['route'=>'curso.empty', 'method'=>'GET','id'=>'myForm_empty_curso'])!!}
{!! Form::hidden('curso_selected',null,['id'=>"curso_selected"]) !!}
<div class="row">
  <div class="col-md-2 col-md-offset-10">
   
    <button type="submit" @if(count($users)<=0 || !Request::get('data_search')) disabled  @endif class="btn btn-warning">
      <i class="fa  fa-hourglass-o" ></i>Vaciar Curso</button>
   
</div>
</div>


<div class="row"> 
  <div class="col-md-12" >
  <div class="box-body table-responsive no-padding">
    <table id="tbl_users"  class="table">


                  <thead>
                    <tr>
                      <th>Identificación</th>
                      <th>Nombre</th>
                      <th>Email</th>
                      <th>Teléfono1</th>
                      <th>Curso</th>
                      <th>Fecha Reg</th>
                      {{-- <th>Activo<br> <i class="fa fa-toggle-on switch-on " id="btn_toggle_active"></i> </th> --}}
                      <th>Editar</th>
                    </tr>
                  </thead>
                <tbody>

                @foreach($users as $user)
                  <tr role="row" class="odd" id="{{ $user->idnumber }}">
                    <td>{{ $user->idnumber }}
                      <input type="hidden" name="idnumber[]" value="{{$user->idnumber}}">
                    </td>
                    
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->tel1 }}</td>
                    <td>

                      

                      <center><span class="pull-center badge bg-green">{{$user->ref_nombre }}</span></center>
                      


                      

                    </td>
                    <td>{{ $user->datecreated }}</td>
                  {{--   <td>

                      @if($user->active) 
                      <input type="text" style="display: none" name="active[]" value="{{$user->active}}" id="inputusac_{{$user->id}}">
                      <input type="checkbox" @if(!isset($data_search) ) disabled @endif class="checkbox_usac" id="checkboxusac_{{$user->id}}"  value="{{$user->active}}" checked>
                      
                        @else   
                      <input type="text" style="display: none" name="active[]" id="inputusac_{{$user->id}}" value="{{$user->active}}">                   
                      <input type="checkbox" @if(!isset($data_search) ) disabled @endif class="checkbox_usac" id="checkboxusac_{{$user->id}}" value="{{$user->active}}">
                      @endif
                    </td> --}}
 
                    <td>
                      {!! link_to_route('users.edit', $title = 'Editar', $parameters = $user->id, $attributes = ['class'=>'btn btn-primary btn-sm']) !!}                   
                    </td>

                  </tr> 
                @endforeach
                </tbody>

    </table>
    </div>
    {!! $users->render()!!}
  </div>
</div>

  {!!Form::close()!!}

              @stop