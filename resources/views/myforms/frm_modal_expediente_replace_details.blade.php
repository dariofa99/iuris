@component('components.modal_dynamic_cols')

	@slot('trigger')
		myModal_asig_details
	@endslot

	@slot('title')
		<h5><label>Detalles Asignaciones</label></h5>
	@endslot

	@slot('cols')
	col-md-8 col-md-offset-2
	@endslot

	@slot('body')


@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')
 	<div class="row">
		<div class="col-md-12">
		<div class="box-body table-responsive no-padding">
			<table class="table" id="table-details-asig">
				<thead>
					<th>
						No Expediente
					</th>
					<th>
						Estudiante Actual
					</th>
					<th>
						No Veces Reasignado
					</th>
					<th>
						Fecha Ult. Asignación
					</th>
					<th>
						Acción
					</th>
				</thead>
				<tbody>

				</tbody>
			</table>
			</div>
		</div>
	</div>


	@endslot
@endcomponent
<!-- /modal -->










