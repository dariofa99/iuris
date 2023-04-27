@extends('layouts.dashboard')
@section('area_forms')

@include('msg.success')



{!!Form::model(Request::all(),['route'=>'users.index', 'method'=>'GET','id'=>'myFormSearch'])!!}

<div class="row">
 <div class="col-sm-2">
    <div class="btn-group" role="group" aria-label="...">     
     {{--  <div class="btn-group" role="group">
        <ul class="dropdown-menu">
          <li>{!! link_to('usuarios/importar', 'Importar')!!}</li>
         
        </ul>
      </div> --}}
      {!! link_to('users/create', 'Nuevo', $attributes = array('type'=>'button', 'class'=>'btn btn-default'))!!}
    </div>
  </div>


              {{--   <div class="col-sm-2">

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
              </div> --}}
<div class="col-md-1 col-md-offset-3">
  Busqueda
</div>
              <div class="col-md-2">
                <div class="form-group">
                <select class="form-control" name="criterio" id="criterio">
                    <option>Seleccione...</option>
                    <option @if($criterio=='idnumber') selected @endif value="idnumber">No de Documento</option>
                    <option @if($criterio=='name') selected @endif value="name">Nombres</option>
                    <option @if($criterio=='rol') selected @endif value="rol">Rol</option>
                </select>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">  
                  <div id="documento" @if($criterio=='idnumber' || $criterio=='name' ) style="display: block;" @else style="display: none;"  @endif>
                    {!!Form::text('data_search', null, ['class' => 'form-control', 'id'=>'data_search'] ); !!}
                  </div>          
                  <div id="roles" @if($criterio=='rol') style="display: block;" @else style="display: none;"  @endif>
                      {!!Form::select('data_search',$roles, null,  ['class' => 'form-control selectpicker disabled-fun', 'data-live-search'=>'true','required' => 'required','id'=>'rol','disabled'])!!}
                  </div>           
                </div>
              </div>
              <div class="col-md-2">                
                  <button type="submit"  class="btn btn-primary">Buscar</button>
                  <a href="/users" id="btn_seeall" class="btn btn-default">Ver Todo</a>
              </div>

            </div>
{!!Form::close()!!}




<br>


<div id='divc'>
<div class="row">
  <div class="col-sm-12">
    <div id="table_list_model"> 
      @include('myforms.frm_myusers_list_ajax')
    </div>
    
  </div>
</div>

<div>
              @stop