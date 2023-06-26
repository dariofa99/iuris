@component('components.modal_dynamic_cols')

	@slot('cols')
	col-md-8 col-md-offset-2
	@endslot

	@slot('trigger')
		myModal_exp_user_edit
	@endslot

	@slot('title')
		Editar
	@endslot


	@slot('body')


@section('msg-contenido')
Registrado 
@endsection
@include('msg.ajax.success') 


<div id="content_user_exp_asig">
	
</div>

	@endslot
@endcomponent
<!-- /modal -->










