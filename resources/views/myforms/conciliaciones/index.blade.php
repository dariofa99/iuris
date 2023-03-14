@extends('layouts.dashboard')
@section('titulo_area')

@if(currentuser()->can('crear_conciliaciones'))
<a href="/conciliaciones/create" class="btn btn-success">Nueva conciliación</a>
@endif          
@endsection
@section('area_forms')

@include('msg.success')

<div class="row">
    {!!Form::open(['route'=>'conciliaciones.index', 'method'=>'GET','id'=>'myformConcFilter'])!!}

 
    <div class="col-md-8"> 
     
      <table class="table-buscar-expe">
     
        <tr>
          <td colspan=""><b>Busqueda</b></td>
           @if(currentUser()->hasRole('docente'))
           <td>           
         {{--   <input type="checkbox" @if((Request::has('search_onlyMy_exp') and Request::has('search_onlyMy_exp')) || empty(request()->all())) checked @endif name="search_onlyMy_exp" id="search_onlyMy_exp">Mis casos
          --}}  </td>
           @endif
        </tr>
        <tr>
         <td width="35%">
            <div class="form-grou">     
              <select name="tipo_busqueda" id='tipo_busqueda_conciliacion' class="form-control" placeholder="Seleccione..." required="required">
                <option value="">Seleccione...</option>
               
                <option @if((Request::has('tipo_busqueda')) and Request::get('tipo_busqueda') == 'num_conciliacion' ) selected @endif   value="num_conciliacion">Número de conciliación</option>
            {{--    
                <option  @if((Request::has('tipo_busqueda')) and Request::get('tipo_busqueda') == 'idnumber' ) selected @endif  value="idnumber" >Doc. Identificación(partes)</option>
                <option value="estudiante" @if((Request::has('tipo_busqueda')) and Request::has('tipo_busqueda') == 'estudiante' ) selected @endif >Nombre Estudiante</option> --}}
           
                {{-- <option @if((Request::has('tipo_busqueda')) and Request::has('tipo_busqueda') == 'consultante' ) selected @endif value="consultante">Nombre o apellidos</option>  --}}
  
              {{--  <option @if((Request::has('tipo_busqueda')) and Request::has('tipo_busqueda') == 'consultante_num' ) selected @endif value="consultante_num">Buscar por Documento</option> --}}
  
                <option @if((Request::has('tipo_busqueda')) and Request::get('tipo_busqueda') == 'estado_id' ) selected @endif   value="estado_id">Estado</option>
              
                <option @if((Request::has('tipo_busqueda')) and Request::get('tipo_busqueda') == 'fecha_radicado' ) selected @endif  value="fecha_radicado">Fecha de radicado</option>
  
                <option @if((Request::has('tipo_busqueda')) and Request::get('tipo_busqueda') == 'fecha_rango' ) selected @endif  value="fecha_rango">Rango Fechas</option>
                
                <option @if((Request::has('tipo_busqueda')) and Request::get('tipo_busqueda') == 'all') selected @endif value="all">Todo</option>
                
  
              </select>
            
              </div>
          </td>
          <td width="35%">
  
  @php
  //dd($types_status);
    $disabled='';
   
  @endphp

  
   
            <div id="input_text" class="inputs"  @if((Request::has('tipo_busqueda')) and (Request::get('tipo_busqueda') == 'num_conciliacion' 
            || Request::get('tipo_busqueda') == 'idnumber')) style="display: block" @else style="display: none" @endif> 
                       
              <input type="text" @if((Request::has('tipo_busqueda')) and (Request::get('tipo_busqueda') == 'num_conciliacion' 
              || Request::get('tipo_busqueda') == 'idnumber')) value="{{Request::get('data')}}" @else disabled @endif name="data" class="form-control input-search" required id="input_data_text" />         

             
           
     </div> 
  
      <div class="input-group inputs" id="input_select"  @if((Request::has('tipo_busqueda')) and (Request::get('tipo_busqueda') == 'estado_id')) style="display: block" @else style="display: none" @endif>
      <select name="data" class="form-control input-search" required id="select_data">
        @foreach ($types_status as $id => $item)
      
            <option {{$id == Request::get("data") ? "selected":""}} value="{{$id}}">{{$item}}</option>
        @endforeach
      </select>
      
        {{-- {!!Form::select('data',$types_status,182,['class' => 'form-control input-search', 'required' => 'required','id'=>'select_data'] ); !!} --}}              
      </div>

     
                  <div id="input_date" class="inputs" @if((Request::has('tipo_busqueda')) and 
                  (Request::get('tipo_busqueda') == 'fecha_radicado')) style="display: block;"  @else style="display: none;" @endif> 
                       <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    {!!Form::text('data',null,['id'=>'date_data','class' => 'form-control datepicker input-search', 'required' => 'required',$disabled] ); !!}
                  </div>
                  </div>
  
                  <div id="input_date_rango" class="inputs" @if((Request::has('tipo_busqueda')) and (Request::get('tipo_busqueda') == 'fecha_rango'))  style="display: block;"  @else style="display: none;" @endif> 
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
          </td>
        </tr>
        
      </table>
  
    </div>
    {!!Form::close()!!}
</div>

<div class="row">
<div class="col-md-12 table-responsive no-padding">

<table class="table">
<thead>
    <th>
        Número
    </th>
    <th>
        Solicitante
    </th>
   
    <th>
        Tipo
    </th>
    <th>
        Estado
    </th>
    <th>
        Conciliador/Asistente
    </th>
    <th>
        Acciones
    </th>
</thead>
<tbody>
    @foreach($conciliaciones as $key => $conciliacion)
    <tr>
        <td>
          <div class="container_img">
            {{-- <img src="{{asset('dist/img/folder_icon.png')}}" alt=""> --}}
            <span>
              {{$conciliacion->num_conciliacion}}
            </span>
          </div>
       
        </td>
        <td>
            @if(count($conciliacion->usuarios()->where('tipo_usuario_id',205)->get())>0)
            {{$conciliacion->usuarios()->where('tipo_usuario_id',205)->first()->name}}
            {{$conciliacion->usuarios()->where('tipo_usuario_id',205)->first()->lastname}}
            @else
            Sin usuarios
            @endif
        </td>
      
        <td>
            {{$conciliacion->categoria->ref_nombre}}
        </td>
        <td>
            <span class="badge bg-{{$conciliacion->estado->color}}">{{$conciliacion->estado->ref_nombre}}</span> 
        </td>
        
        <td>
           
          @if(count($conciliacion->usuarios()->whereIn('tipo_usuario_id',[203,204])
         // ->where("conciliacion_has_user.estado_id",)
          ->get())>0)
          {{$conciliacion->usuarios()
          ->whereIn('tipo_usuario_id',[203,204])
          ->orderBy('conciliacion_has_user.created_at','desc')
          ->first()->name}}
          {{$conciliacion->usuarios()
          ->whereIn('tipo_usuario_id',[203,204])
          ->orderBy('conciliacion_has_user.created_at','desc')
          ->first()->lastname}}
          @else
          Sin usuarios
          @endif
        
        </td>
        <td>
            <a href="/conciliaciones/{{$conciliacion->id}}/edit" class="btn btn-sm btn-primary">Abrir</a>
        </td>
    </tr>
    @endforeach
   
</tbody>
</table>

{{ $conciliaciones->appends(request()->query())->links() }}
</div>
</div>

@stop
