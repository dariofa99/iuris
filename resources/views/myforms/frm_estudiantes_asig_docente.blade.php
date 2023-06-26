@component('components.modal')
	
	@slot('trigger')
		myModal_est_asig_docente
	@endslot

	@slot('title')
		Listado de Estudiantes Asignados
	@endslot

 
	@slot('body')
		<div class="box-body table-responsive no-padding">
		<table class="table" id="table_list_est_asig_doc">
			<thead>
				<th>
					Nombre del Estudiante
				</th>
				<th>
					Curso
				</th>
				<th>
					Estado
				</th>
			</thead> 
			<tbody>
				
			</tbody>
		</table>
		</div>

	@endslot
@endcomponent
<!-- /modal -->










