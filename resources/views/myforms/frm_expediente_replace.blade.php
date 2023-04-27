@extends('layouts.dashboard')


@section('titulo_general')
Sustituciones de Expedientes
@endsection

@section('titulo_area')
Listados
@endsection


@section('area_forms')

@include('msg.success') 

<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" class="tab-btn-show-notas" data-toggle="tab">Sustituciones</a></li>
             
              <li><a href="#tab_2" class="tab-btn-show-notas" data-toggle="tab">Historial</a></li>
			{{-- 	
			<li><a href="#tab_3" class="tab-btn-show-notas" data-toggle="tab">Estudiantes por Asignar</a></li>
	 --}}

               
              
            </ul>
            <div class="tab-content">
			
			<!--Tab panel 1-->
              <div class="tab-pane active" id="tab_1">
	<div class="row">
	
	<div class="col-md-12 border-g">
		{!!Form::open(['route'=>['expedientes.sustcasos'], 'method'=>'post', 'id'=>'myformSusEstu'])!!}
		<div class="box-body ">
		
		<table class="normal-table tabla-sust" id="tabla-sust-estu"> 
			<thead>
				<th width="45%">
					Estudiante Actual
				</th>
				<th>
					<i class="fa fa-random"></i>
				</th>
				<th width="45%">
					Nuevo Estudiante 
				</th>
				
			</thead>
			<tbody>
				<tr id="fila_0" class="filas">
					<td>
						{!!Form::select('numberestact_id[]',$user, null,  ['class' => 'form-control selectpicker disabled-fun4', 'data-live-search'=>'true','required' => 'required','id'=>'numberestact_id','placeholder'=>"Seleccione..." ])!!}
					</td>
					<td>
						
					</td>
					<td>
						{!!Form::select('numberestnew_id[]',$user, null,  ['class' => 'form-control selectpicker disabled-fun4', 'data-live-search'=>'true','required' => 'required','id'=>'numberestnew_id','placeholder'=>"Seleccione..." ])!!}
					</td>
					<td>

						<a onclick="searchEstud()" class="btn btn-success"> <i class="fa fa-plus-circle"></i> Agregar</a>
						
					</td>
				</tr>
			</tbody>
		</table>
		</div>
		<hr>
		 <div>
							{{-- <a class="btn btn-warning" id="btnOpReasig" onclick="habilityButtReasCaso()"><i class="fa fa-edit"> </i>
							Reasignar</a> --}}
							<input type="submit"  class="btn btn-primary" id="btnReasignar" value="Enviar">
								
						{{-- <a class="btn btn-danger" style="display: none;" id="btnCancReasig" onclick="hideButtReasCaso()">
								<i class="fa  fa-remove"> </i>
							Cancelar</a> --}}
					</div>
		{!!Form::close()!!}
	</div>
</div>
            </div>
              <!--Fin Tab panel 1-->
              	<!--Tab panel 2-->
              <div class="tab-pane" id="tab_2">
			 <div class="row">
			 	<div class="col-md-12">
			 		Seleccione un rango

			 	</div>
			{!! Form::open(['url'=>'expediente/replacecaso','method'=>'GET','id'=>'myFormBuscarAntEst']) !!}
					<div class="col-md-2"> 
				    <div class="form-group">
				    	{!!Form::label('Desde:') !!}
				     <div class="input-group">
				      <div class="input-group-addon">
				        <i class="fa fa-calendar"></i>
				      </div>
				      {!!Form::date('fech_desde', null, ['class' => 'form-control required', 'required' => 'required','id'=>'fech_desde'] ); !!}
				    </div>
				    <!-- /.input group -->
				    </div> 
			    </div> 

			    <div class="col-md-3">
				    <div class="form-group">
				    	{!!Form::label('Hasta:') !!}
				     <div class="input-group">
				      <div class="input-group-addon">
				        <i class="fa fa-calendar"></i>
				      </div>
				      {!!Form::date('fech_hasta', null, ['class' => 'form-control required', 'required' => 'required','id'=>'fech_hasta'] ); !!}
				    </div>
				    <!-- /.input group -->
				    </div>
			    </div>
			    <div class="col-md-1">
			    	<label for=""></label>
			    	<input type="button" id="btn_buscantest" value="Buscar" class="btn btn-success">
			    </div>


			{!! Form::close() !!}
			</div>
			<div class="row">
				<div class="col-md-12">
				<div class="box-body table-responsive no-padding">
					<table class="table" id="table_lisestant_exp">
						<thead>
							<th>Cedula</th>
							<th>Nombre del Estudiante</th>
							<th>Fecha Sustitución</th>
						</thead>
						<tbody>
							
						</tbody>
					</table>
					</div>
				</div>
			</div>
    @include('myforms.frm_modal_expediente_replace_details')
              </div>
              <!--Fin Tab panel 2-->

              <!--Tab panel 3--> 
              <div class="tab-pane" id="tab_3">
              	

<div class="row">
	<div class="col-md-8 col-md-offset-2 border-g">
		
		<hr>
		
	</div>
</div>

              </div>
              <!--Fin Tab panel 3-->


          </div>
      </div>







@stop
