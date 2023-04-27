@component('components.modal')
	
	@slot('trigger')
		myModal_reporasis
	@endslot

	@slot('title')
		Detalles asistencias:
	@endslot


	@slot('body')

	<div class="row">
		<div class="col-md-5">
			<label>Cédula:</label><span id="ced_det_asis"></span>
		</div>
		<div class="col-md-7">
			<label>Nombre:</label><span id="nom_det_asis"></span>
		</div>
	</div>
 	<div class="row">
		<div class="col-md-12">
		<div class="box-body table-responsive no-padding">
			<table class="table" >
				<thead>
					<th>
						No.
					</th>
					<th>
						Asistencia
					</th>
					<th>
						Lugar
					</th>
					<th>
						Fecha
					</th>
					<th>
						Descripción
					</th>
				</thead>
				<tbody id="table-details-asistencia">

				</tbody>
			</table>
			</div>
			<span id="estadp_det_asis"></span>
		</div>
	</div>

	@endslot
@endcomponent
<!-- /modal -->










