@component('components.b4.modal_large')

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
		<form id="{{isset($user) ? 'myFormUserEditExpediente': 'myFormUserCreateExpediente'}}" method="POST">
			<div class="row">
				@include('myforms.users.formulario_registro',[
					'user'=>$expediente->solicitante
				])
				@include('myforms.components_user.identitaria',[
					'user'=>$expediente->solicitante
				])
				@include('myforms.components_user.socioeconomica',[
					'user'=>$expediente->solicitante
				])
			</div>   
			<div class="row">
				<div class="col-md-4">
					<button id="btnActualizarUserEstudiante" type="button" class="btn btn-primary btn-sm btn-block">Actualizar datos</button>
				</div>
			</div>
		</form>
    @endslot
@endcomponent
<!-- /modal -->
