@extends('layouts.dashboard')


@section('titulo_area')
Reportes

@endsection




@section('area_forms') 
@include('msg.alerts') 
{!! Form::open(['url'=>'/excel/download','method'=>'POST','id'=>'myFormDownExcel']) !!}
{!! Form::hidden('filter',null,['id'=>'filter']) !!}

<div class="row">
  <div class="col-md-12">
   
                  <div class="row">
  <div class="col-md-5">
    {!! Form::label('select_table_excel','Tabla Principal') !!}
    {!! Form::select('select_table_excel',[''=>'Seleccione...',
    'expedientes'=>'Expedientes',
    'actuaciones'=>'Actuaciones',
    'requerimientos'=>'Requerimientos',
    ],null,['class'=>'form-control generate_excel','id'=>'select_table_excel']) !!}
 
  </div>
  <div class="col-md-1">
    <label for="">Hab. Rango</label>
    <input type="checkbox" id="check_hab_rango"  class="">
  </div>
  <div class="col-md-5" >
    <div class="row">
      <div class="col-md-6">
        @php
        $fecha = date('Y-m-j');
        $nuevafecha = strtotime ( '-7 day' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
        @endphp
        {!! Form::label('fecha_ini','Fecha Inicial') !!}
        {!! Form::date('fecha_ini',$nuevafecha,['class'=>'form-control ','id'=>'fecha_ini','disabled']) !!}
      </div>
      <div class="col-md-6">
        {!! Form::label('fecha_fin','Fecha Final') !!}
        
        {!! Form::date('fecha_fin',date('Y-m-d'),['class'=>'form-control ','id'=>'fecha_fin','disabled']) !!}
      </div>
    </div>  
  </div>

  
</div>
<div class="row">  
   {{--  <div class="col-md-5">  
     <label for="">Cruzar</label><br>
      <input type="checkbox" id="check_hab_cruce" class="generate_excel">
     </div>
     <div class="col-md-1">  
    
  </div> --}} 
  <div class="col-md-5">  
    {!! Form::label('select_option_table_excel','Filtro') !!}
        
    {!! Form::select('select_option_table_excel',[''=>''],null,['class'=>'form-control ','id'=>'select_option_table_excel']) !!}
     
  </div>
    <div class="col-md-5">
      {!! Form::label('select_options_filtro_excel','Opciones de Filtro') !!}
    
        {!! Form::select('select_options_filtro_excel',[''=>''],null,['class'=>'form-control ','id'=>'select_options_filtro_excel','style'=>'display:block']) !!}
    </div>
    <div class="col-md-2">
      <br>
      <button type="submit" class="btn btn-success" id="btn_download_excel" disabled><i class="fa fa-file-excel-o"></i> Descargar</button> 
    </div>
   
</div> 


<div class="row">
  <div class="col-md-12">
    <div id="preloader_chart"> 
     <img src="{{asset('img/logo2.png')}}" id="load" width="67" height="71" style="margin-top:25%;margin-left:48%;padding:2px;" />
    </div>
    <div id="cont-opt-excel">
      
      
      <table id="llenar_tabla_cols" class="table table-hover">
        <tbody>
          
        </tbody>
      </table>
      <table id="llenar_tabla_cols_users" class="table table-hover">
        <tbody>
          
        </tbody>
      </table> 
  
        
      </div>
    

  </div>
  <hr>
 <div class="col-md-12">
    <div id="chartdiv3" style="height: 400px;"></div>

  </div>
</div>
             
  </div>
</div>
{!! Form::close()!!}

              @stop
