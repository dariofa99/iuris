@extends('layouts.dashboard')


@section('titulo_general')
Turnos
@endsection

@section('titulo_area')
Docentes
@endsection


@section('area_forms')

@include('msg.success')

<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
    	<li class="active"><a href="#tab_1" class="tab-btn-show-notas" data-toggle="tab">Asignación horario</a></li>
@if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole("dirgral"))
             
@endif
        <li><a href="#tab_2" class="tab-btn-show-notas" data-toggle="tab" id="reporasistencia_doc_btn">Reporte asistencia</a></li>

	</ul>
        <div class="tab-content">
		<!--Tab panel 1-->
        <div class="tab-pane active" id="tab_1">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="sel1">Docentes:</label>
						<select class="form-control" id="select_doc_horario">
							<option value="0">Seleccione...</option>
							@foreach($docentes as $docente)
							<option value="{{$docente->idnumber}}">{{$docente->full_name}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
<hr>

<div class="row">
	<div class="col-md-12 text-center">
		<label id="name_doc_horairo">Seleccione un docente</label>
	</div>
</div>

<div class="row">
	<div class="col-md-10 col-md-offset-1">
<div class="box-body table-responsive no-padding">
		<table class="normal-table table-list-est-tur table" id="table_turnos_docentes" >
			<thead>

				<th width="100px" >
					Hora Inicio
				</th>
				<th width="100px" >
					Hora Fin
				</th>
				<th width="80px">
					Lun
				</th>
				<th width="80px">
					Mar
				</th>
				<th width="80px">
					Mie
				</th>
				<th width="80px">
					Jue
				</th>
				<th width="80px">
					Vie
				</th>
				<th width="40px">
					Eliminar
				</th>
			</thead>
			<tbody id="new_dia_docente">
					

			
			</tbody>
		</table>
		<div class="row">
			<div class="col-md-6">
				<button id="guardar_horario_doc" class="btn btn-primary">Guardar</button>
			</div>
			<div class="col-md-6 text-right" >
				<button id="horariomas" value="1" class="btn btn-success">Agregar</button>
			</div>
		</div>
			
		<br><br>
		<br><br>
		<br>
		</div>
		<hr>
	</div>
</div>

              </div>
              <!--Fin Tab panel 1-->

@if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole("dirgral"))

@endif
<!--Tab panel 2-->
              <div class="tab-pane" id="tab_2">
      <div class="row">
        <div class="col-md-12">
				<div class="box-body table-responsive no-padding">
           <table id="tbl_repor_asis" class="table table-bordered table-striped dataTable" role="grid">

                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Cédula</th>
                      <th>Nombre</th>
                      <th>Horas asistidas</th>
                      <th>Horas permisos</th>
                      <th>Horas reposiciones</th>
                      <th>Horas pendientes</th>


                    </tr>
                  </thead>
                <tbody id="contenrepasistenciadoc">
                </tbody>
                </table>
								</div>
        </div>
      </div>
              </div><!--Fin Tab panel 2-->
          </div>
      </div>






@stop
