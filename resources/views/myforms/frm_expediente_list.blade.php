@extends('layouts.dashboard')

@section('titulo_general')
@if(currentUser()->hasRole('solicitante'))
    Casos
  @else
    Expedientes
  @endif

@endsection

@section('titulo_area')
  @if(currentUser()->hasRole('solicitante'))
    Mis Casos
  @else
    Listar
  @endif
@endsection




@section('area_forms') 

@include('msg.success')
@include('msg.info') 


@if(!currentUser()->hasRole('solicitante'))
<div class="row"> 

@permission('crear-expedien')
{{--------------------------------excel y nuevo expe--------------------------------------}}

 
<!--       <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Excel
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <li>{!! link_to('usuarios/importar', 'Importar')!!} {!! link_to('expedientes/create', 'Nuevo', $attributes = array('type'=>'button', 'class'=>'btn btn-primary'))!!}</li>
         
        </ul>
      </div> -->    
@endpermission

  {!!Form::open(['route'=>'expedientes.index', 'method'=>'GET','id'=>'myformExpFilter'])!!}

 
  <div class="col-md-8"> 
   
    <table class="table-buscar-expe">
   
      <tr>
        <td colspan=""><b>Busqueda</b></td>
         @if(currentUser()->hasRole('docente'))
         <td>
         
         <input type="checkbox" @if((isset($request['search_onlyMy_exp']) and $request['search_onlyMy_exp'] == 'on') || empty($request)) checked @endif name="search_onlyMy_exp" id="search_onlyMy_exp">Mis casos
         </td>
         @endif
      </tr>
      <tr>
       <td width="35%">
          <div class="form-grou">     
            <select name="tipo_busqueda" id='tipo_busqueda' class="form-control" placeholder="Seleccione..." required="required">
              <option value="">Seleccione...</option>
              @if(currentUser()->hasRole("diradmin") OR currentUser()->hasRole("dirgral") OR currentUser()->hasRole("amatai"))
              <option @if(isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'idnumber_doc' ) selected @endif  value="idnumber_doc">Casos por docente</option>
              @endif
              <option @if(isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'codido_exp' ) selected @endif  value="codido_exp">Número de Expediente</option>
             
              @if(!currentUser()->hasRole("estudiante"))
              <option value="estudiante_num" @if(isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'estudiante_num' ) selected @endif >Documento de identificación</option>
             {{--  <option value="estudiante" @if(isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'estudiante' ) selected @endif >Nombre Estudiante</option> --}}
             
              @endif
              <option @if(isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'consultante' ) selected @endif value="consultante">Nombre o apellidos (consultante)</option> 

            {{--  <option @if(isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'consultante_num' ) selected @endif value="consultante_num">Buscar por Documento</option> --}}

              <option @if(isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'estado' ) selected @endif  value="estado">Estado</option>
              <option @if(isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'tipo_consulta' ) selected @endif  value="tipo_consulta">Tipo de Consulta</option>
              <option @if(isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'rama_derecho' ) selected @endif  value="rama_derecho">Rama del Derecho</option>
              <option @if(isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'fecha_creacion' ) selected @endif  value="fecha_creacion">Fecha de Creación</option>

              <option @if(isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'fecha_rango' ) selected @endif  value="fecha_rango">Rango Fechas</option>
              
              <option @if((isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'all')) selected @endif value="all">Todo</option>
              

            </select>
          
            </div>
        </td>
        <td width="35%">

@php
  $disabled='';
@endphp

          @if(isset($request['tipo_busqueda']) and ($request['tipo_busqueda'] == 'consultante_num'  || $request['tipo_busqueda'] == 'codido_exp' || $request['tipo_busqueda'] == 'estudiante_num'))
     
          <div id="input_text" class="inputs"  > 
                     
                   {!!Form::text('data',null, ['class' => 'form-control input-search', 'required' => 'required','id'=>'input_data',$disabled] ); !!}
                
          </div>    
          @else
        
          <div id="input_text" class="inputs" @if(isset($request) and (empty($request ) || (isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'all'))) style="display: block;" @else style="display: none;"  @endif>                       
                   {!!Form::text('data',null, ['class' => 'form-control input-search', 'disabled' => 'disabled','id'=>'input_data','placeholder'=>'No de Documento'] ); !!}
                
          </div>
          @endif
 
           <div id="input_select_users" class="inputs" @if(isset($request['tipo_busqueda']) and ($request['tipo_busqueda'] == 'estudiante')) style="display: block;" <?php $disb = '' ?> @else style="display: none;"  <?php $disb = 'disabled' ?> @endif>
                <div class="input-group">
                   {!!Form::select('data',[],null,['class' => ' input-search  selectpicker input-select disabled-fun1', 'data-live-search'=>'true', 'required' => 'required','id'=>'select_data_users', 'data-width'=>'500px', 'data-live-search-placeholder'=>'Escriba el nombre',$disb] ); !!}
                </div> 
            </div>
 
            <div id="input_select_consultantes" class="inputs" @if(isset($request['tipo_busqueda']) and ($request['tipo_busqueda'] == 'consultante' || $request['tipo_busqueda'] == 'idnumber_doc')) style="display: block;" <?php $disb2 = '' ?> @else style="display: none;"  <?php $disb2 = 'disabled' ?> @endif>
                <div class="input-group">
                   {!!Form::select('data',[],null,['class' => ' input-search  selectpicker input-select disabled-fun2', 'data-live-search'=>'true', 'data-select-origen'=>'consultante', 'required' => 'required','id'=>'select_data_consultantes','data-width'=>'500px','data-live-search-placeholder'=>'Escriba el nombre',$disb2] ); !!}
                </div>
            </div>

    

          <div id="input_select_estado" class="inputs" @if(isset($request['tipo_busqueda']) and ($request['tipo_busqueda'] == 'estado')) style="display: block;" <?php $disabled = '' ?> @else style="display: none;"  <?php $disabled = 'disabled' ?> @endif> 
                     <div class="input-group">
                   {!!Form::select('data',[
                    ''=>'Seleccione...',
                   "1"=>"Abierto",
                   "2"=>"Cerrado",
                   "4"=>"En solicitud de cierre",
                   "3"=>"Rechazado"
                    ],null,['class' => 'form-control input-search', 'required' => 'required','id'=>'select_data_estado',$disabled] ); !!}              
                </div>
                </div>


                 <div id="input_select_tipo_consulta" class="inputs" @if(isset($request['tipo_busqueda']) and ($request['tipo_busqueda'] == 'tipo_consulta')) style="display: block;" <?php $disabled = '' ?> @else style="display: none;"  <?php $disabled = 'disabled' ?> @endif> 
                     <div class="input-group">
                  {!!Form::select('data',[
                    ''=>'Seleccione...',
                   "2"=>"Seguimiento",
                   "1"=>"Asesoría",
                   '3'=>'Defensa de Oficio'
                   ],null,['class' => 'form-control input-search', 'required' => 'required','id'=>'select_data_tipo_consulta',$disabled] ); !!}                   
                </div>
                </div>

                <div id="input_select_rama_derecho" class="inputs" @if(isset($request['tipo_busqueda']) and ($request['tipo_busqueda'] == 'rama_derecho')) style="display: block;" <?php $disabled = '' ?> @else style="display: none;"  <?php $disabled = 'disabled' ?> @endif> 
                     <div class="input-group">
                  {!!Form::select('data',$rama_derecho,null,['class' => 'form-control input-search', 'required' => 'required','id'=>'select_data_rama_derecho',$disabled] ); !!}                   
                </div>
                </div>
 
                <div id="input_date" class="inputs" @if(isset($request['tipo_busqueda']) and ($request['tipo_busqueda'] == 'fecha_creacion')) <?php $disabled = '' ; $fecha = '';  ?> style="display: block;"  @else style="display: none;" <?php $disabled = 'disabled' ?> @endif> 
                     <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  {!!Form::text('data',null,['id'=>'date_data','class' => 'form-control datepicker input-search', 'required' => 'required',$disabled] ); !!}
                </div>
                </div>

                <div id="input_date_rango" class="inputs" @if(isset($request['tipo_busqueda']) and ($request['tipo_busqueda'] == 'fecha_rango')) <?php $disabled = '' ; $fecha = '';  ?> style="display: block;"  @else style="display: none;" <?php $disabled = 'disabled' ?> @endif> 
                <table>
                 
                  <tr>
                    <td>
                      <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  {!!Form::text('dataIni',null,['id'=>'date_data_inicio','class' => 'form-control datepicker input-search', 'required' => 'required',$disabled,'placeholder'=>'Desde'] ); !!}
                </div>
                    </td>
                    <td>
                      <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  {!!Form::text('dataFin',null,['id'=>'date_data_final','class' => 'form-control datepicker input-search', 'required' => 'required',$disabled,'placeholder'=>'Hasta'] ); !!}
                </div>
                    </td>
                  </tr>
                </table>
                          </div>

        </td>
        <td>
          <button type="submit" class="btn btn-success"><i class="fa fa-search"> </i> Buscar </button>
          @if(currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('coordprac') || currentUser()->hasRole('amatai'))
          <button type="button" id="btn_exp_bus_avz" class="btn btn-default"><i class="fa fa-cogs"> </i> Avanzada </button>
          @endif
        </td>
      </tr>
      
    </table>

  </div>
  {!!Form::close()!!}

  <div class="col-md-4 dis-block">
  <table style="text-align:right;width:100%;font-size:14px;"> 
    <tr>
      <td>
     
      <label >No de Expedientes <span class="badge bg-blue" id="badgeCount"> {{ number_format($numEx,0,'.','.') }} </span></label>
  
      </td>
    </tr>
     <tr>
      <td>
     
    @if(count($count_colors)>0 and $count_colors!="")  
   <div >
   <label >Asesorías asignadas</label><br>
   <span class="badge btn_search_color" id="green" style="border:1px solid #2ECC71">{{($count_colors[0]->verde===null ? 0 : $count_colors[0]->verde)}}</span>
    <span class="badge btn_search_color" id="amarillo" style="border:1px solid #F4D03F">{{($count_colors[0]->amarillo === null ? 0 : $count_colors[0]->amarillo)}}</span>
    <span class="badge btn_search_color" id="rojo" style="border:1px solid #CB4335">{{($count_colors[0]->rojo === null ? 0 : $count_colors[0]->rojo)}}</span>
    <span class="badge btn_search_color" id="gris" style="border:1px solid gray">{{($count_colors[0]->gris === null ? 0 : $count_colors[0]->gris)}}</span>
     </div>
    
   @endif
   
      </td>
    </tr>
  
  </table>
    
     

     
  </div>


</div>
@endif
<br>


<div id='divc'>
 
<div class="row">
  <div class="col-sm-12">
   <div id="table_list_model">
    
    @include('myforms.frm_expediente_list_ajax')  

   </div>
  </div>
</div>

<div>
@include('myforms.frm_modal_buscar_exp_avanzada')
              @stop
