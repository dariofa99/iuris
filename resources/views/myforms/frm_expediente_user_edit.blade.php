@component('components.modal')
	
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




	@endslot
@endcomponent
<!-- /modal -->










