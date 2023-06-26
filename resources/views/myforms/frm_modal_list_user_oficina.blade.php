@component('components.modal')
	
	@slot('trigger')
		myModal_list_user_oficina
	@endslot

	@slot('title')
		Usuarios en la oficina:
	@endslot

 
	@slot('body')



@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')


<div class="row">
<div class="col-md-12  table-responsive no-padding">
<input type="hidden" id="oficina_id">
	<table id="table_list_oficina_usuarios" class="table">
	<thead>
	<th>Nombres</th>

	<th>Acciones</th>
	</thead>

	<tbody>
	
	</tbody>
	</table>
</div>
</div>

	@endslot
@endcomponent
<!-- /modal -->










