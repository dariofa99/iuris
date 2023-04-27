@extends('layouts.dashboard')

@section('titulo_general')
Expedientes
@include('myforms.components_exp.frm_datos_docente')
@endsection

@section('titulo_area')
Editar
@endsection

@section('area_buttons')
<div class="pull-right" style="float: right !important;">
	<a href="#" class="btn-atrasexed pull-right btn bg-gray" style="color:#777"><i class="fa fa-backward"></i> Atras</a>
</div>
@endsection
 

@section('area_forms') 
 
@include('msg.success') 



{{-----------------------ESTADOS DE EXPEDIENTES---------------------------------------------      
   -------------------------------------------------------------------------------------------      

1	Abierto
2	Solicitud cierre
3	Cerrado
4	Rechazado

  -------------------------------------------------------------------------------------------}}

{{-----------------------ESTADOS DE ACTUACIONES---------------------------------------------      
   -----------------------------------------------------------------------------------------      

        '1' => 'Enviado a revisión',
		'2' => 'Pendiente por requerimiento',
		'3' => 'Enviado con correcciones',
		'4' => 'Aprobado',

  --------------------------------------------------------------------------------------------}}

@php
 if(!currentUser()->hasRole("estudiante")){
	$disabled = 'disabled';

 }else{
 	 	if($expediente->expestado_id =='1' OR $expediente->expestado_id =='3'){
			$disabled = '';
 		}else{
			$disabled = 'disabled';
 		}

 }
 @endphp
 
 <div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_1" class="tab-btn-show-notas" data-toggle="tab">Datos del caso</a></li>
		<li><a href="#tab_2" class="tab-btn-show-notas" data-toggle="tab">Actuaciones</a></li>
		{{-- <li><a href="#tab_3" class="tab-btn-show-notas" data-toggle="tab">Cita o req. a solicitante</a></li> --}}
		<li><a href="#tab_4" class="tab-btn-show-notas" data-toggle="tab">Cierre de caso</a></li>
		<li><a href="#tab_5" class="tab-btn-show-notas" id="tab-btn-show-notas" data-toggle="tab">Calificaciones</a></li>
		 @if( (count($expediente->asignaciones)>1 || (currentUser()->hasRole('amatai') or currentUser()->hasRole('diradmin') or currentUser()->hasRole('coordprac') or currentUser()->hasRole('dirgral'))) and $expediente->expestado_id!=2)
		<li>
			<a href="#tab_6" class="tab-btn-show-notas" data-toggle="tab">Reasignar Caso</a>
		</li>
		 
		@endif
 <li><a href="#tab_7" data-toggle="tab">Citaciones</a>
		  </li>

		  <li><a href="#tab_10" data-toggle="tab">Autorizaciones</a>
		  </li>
	  </ul>
		<!--Tab contnent-->
		<div class="tab-content">
		<!--Tab pane tab_1-->
		  <div class="tab-pane active" id="tab_1">
			@include("myforms.components_exp.frm_datos_caso_dofi") 
			@include("myforms.components_exp.frm_asesorias_caso")
			@include("myforms.components_exp.frm_notas_caso")
		  </div>
		<!--Tab pane tab_1-->
		<!--Tab pane tab_2-->
		<div class="tab-pane" id="tab_2">
				<div class="row">
						<div class="col-md-12">	
						@if($expediente->exptipoproce_id=='3')
						<div id="frm_actuacion_create">
							@include('myforms.frm_actuacion_create') 
							@include('myforms.frm_actuacion_list')
						</div>						 
						@else
						<div id="frm_actuacion_create">
							Opción inactiva para Consulta simple
						</div>						
						@endif
	
							
	
						 </div> <!-- /.md12-->              
				   </div> <!-- /.row -->	
		</div>
			<!--Tab pane tab_2-->
			<!--Tab pane tab_3-->
		<div class="tab-pane" id="tab_3">
					
		</div>
			<!--Tab pane tab_3-->

			<!--Tab pane tab_4-->
		<div class="tab-pane" id="tab_4">
				<div class="row">
						<div class="col-md-12">	
								@include('myforms.frm_expediente_cierre_caso')
						</div> <!-- /.md12-->              
				</div>
				 <!-- /.row -->	
		</div>
			<!--Tab pane tab_4-->
			<!--Tab pane tab_5-->
		<div class="tab-pane" id="tab_5">
				<div class="row">
						<div class="col-md-12">	
						@if($expediente->exptipoproce_id == 1)				
							@include('myforms.frm_calificacion_show')  
						@else
							@include('myforms.frm_calificacion_create')						
						@endif
						</div> <!-- /.md12-->              
				</div>
				 <!-- /.row -->	
		</div>
			<!--Tab pane tab_5-->

				<!--Tab pane tab_6-->
		<div class="tab-pane" id="tab_6">
				<div class="row">
						<div class="col-md-12">	
								@include('myforms.components_exp.frm_reasignar_caso')	
						</div> <!-- /.md12-->               
				</div>
				 <!-- /.row -->	
		</div>
			<!--Tab pane tab_6-->

								<!--Tab pane tab_7-->
		<div class="tab-pane" id="tab_7">
				<div class="row">
						<div class="col-md-12">	
								@include('myforms.components_exp.frm_citaciones_estudiante')
						</div> <!-- /.md12-->              
				</div>
				 <!-- /.row -->	
		</div>
			<!--Tab pane tab_7-->
<!--Tab pane tab_10-->
				<div class="tab-pane" id="tab_10">
				<div class="row">
						<div class="col-md-12">	
								@include('myforms.components_exp.frm_autorizaciones')
						</div> <!-- /.md12-->              
				</div>
			<!--fin Tab pane -->
			
				
				 <!-- /.row -->	
		</div>


		</div>
		<!--Tab contnent-->
 </div>
 @include('myforms.frm_add_nota_final_expedientes')
 @include('myforms.frm_calificacion_edit')
 @include('myforms.frm_modal_cambiar_docente_exp')
 @include('myforms.components_exp.frm_modal_create_autorizacion')
 @include('myforms.frm_modal_dinamyc_js')










@stop
