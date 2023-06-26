@component('components.modal_dynamic_cols')

	@slot('cols')
	col-md-8 col-md-offset-2
	@endslot

	@slot('trigger')
		myModal_exp_user_details
	@endslot

	@slot('title')
		Detalles Usuario
	@endslot


	@slot('body')



@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')

<form id="{{isset($user) ? 'myFormUserEditExpediente': 'myFormUserCreateExpediente'}}" method="POST">
    @include('myforms.users.formulario_registro',[
		'user'=>$expediente->solicitante
	])
    @include('myforms.components_user.identitaria',[
		'user'=>$expediente->solicitante
	])
    @include('myforms.components_user.socioeconomica',[
		'user'=>$expediente->solicitante
	])
</form>


	@endslot
@endcomponent
<!-- /modal -->










