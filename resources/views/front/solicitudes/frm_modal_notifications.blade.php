@component('components.modal')
	
	@slot('trigger')
		myModal_notificacion
	@endslot

	@slot('title')
		Documentos
	@endslot

 
	@slot('body')



@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')




	<div class="row">
		<div class="col-md-12">
			<div class="embed-responsive embed-responsive-4by3">
			@if(count($solicitud->expedientes)>0)
				@include('front.solicitudes.frm_list_notificaciones',['expediente'=>$solicitud->expedientes[0]])
			@endif
				
			</div> 
		</div>
	</div>


	
	@endslot
@endcomponent
<!-- /modal -->










