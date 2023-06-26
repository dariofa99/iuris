@extends('layouts.dashboard')


@section('titulo_area')
Reportes

@endsection




@section('area_forms') 
<div class="row">
  <div class="col-md-12">
   
                  <div class="row">
  <div class="col-md-5">
    {!! Form::label('select_table','Tabla Principal') !!}
    {!! Form::select('select_table',[''=>'Seleccione...',
    'expedientes'=>'Expedientes',
    'actuaciones'=>'Actuaciones',
    'requerimientos'=>'Requerimientos',
    'conciliaciones'=>'Conciliaciones',
    ],null,['class'=>'form-control generate_graf','id'=>'select_table']) !!}
 

  </div>
  <div class="col-md-1"><br>
    <label for="">Hab. Rango</label>
    <input type="checkbox" id="check_hab_rango" class="generate_graf">
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
        {!! Form::date('fecha_ini',$nuevafecha,['class'=>'form-control generate_graf','id'=>'fecha_ini','disabled']) !!}
      </div>
      <div class="col-md-6">
        {!! Form::label('fecha_fin','Fecha Final') !!}
        
        {!! Form::date('fecha_fin',date('Y-m-d'),['class'=>'form-control generate_graf','id'=>'fecha_fin','disabled']) !!}
      </div>
    </div>  
  </div>

  
</div>
<div class="row">  
    <div class="col-md-5">  
      <ul class="menu-rep">
        <li>
         <i class="fa fa-pie-chart"></i> Torta <input class="generate_graf" id="pie" value="pie" checked type="radio" name="type_grap"> 
        </li>
        <li>
         <i class="fa fa-line-chart"></i> Linea <input class="generate_graf" id="line" value="line" type="radio" name="type_grap"> 
        </li>
        <li>
         <i class="fa fa-bar-chart"></i> Columnas <input class="generate_graf" id="bar" value="bar" type="radio" name="type_grap"> 
        </li>
      </ul>
     </div>
     <div class="col-md-1">  
    <label for="">Cruzar</label><br>
    <input type="checkbox" id="check_hab_cruce" class="generate_graf" disabled>
  </div> 
  <div class="col-md-5">  
    {!! Form::label('select_option_table','Opciones de Filtro') !!}
        
    {!! Form::select('select_option_table',[''=>''],null,['class'=>'form-control generate_graf','id'=>'select_option_table']) !!}
     
  </div>
   
</div> 
<div class="row">  
    <div class="col-md-6">  
      
    </div> 
    <div class="col-md-5">
        {!! Form::select('select_option_table_cruce',[''=>''],null,['class'=>'form-control generate_graf','id'=>'select_option_table_cruce','style'=>'display:none']) !!}
    </div> 
</div>
<br>
<div class="row">
  <div class="col-md-12">
    <div id="preloader_chart"> 
     <img src="{{asset('img/logo2.png')}}" id="load" width="67" height="71" style="margin-top:25%;margin-left:48%;padding:2px;" />
    </div>
    <div id="chartdiv" style="height: 900px;">
      
        
      </div>
    

  </div>
  <hr>
 <div class="col-md-12">
    <div id="chartdiv3" style="height: 400px;"></div>

  </div>
</div>
             
  </div>
</div>


              @stop
