@component('components.modal')
	
	@slot('trigger')
		myModal_videollamadas
	@endslot

	@slot('title')
		Videollamada
	@endslot

 
	@slot('body')



@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')


	<div class="row">
		<div class="col-md-4" style="  text-align: left;">
			<button type="button" id="ask-stream-cases"  data-id="{{ $user_idnumber }}" class="btn btn-info">Invitar usuario</button>
		</div>
		<div class="col-md-4 text-md-center" style="  text-align: center;">
		<a id="newtab-stream-cases" href=""  class="btn btn-info" target="_blank">Abrir en nueva pestaña</a>
		
		</div>

		<div class="col-md-4 text-md-right"  style="  text-align: right;">
			<button type="button" id="copy-stream-cases"  data-frame="" class="btn btn-info">Copiar link</button>
		</div>


	</div>
	<br>
	<div class="row">
		<div class="col-md-12" id="content-text-stream-cases" >
			<input type="text" class="form-control form-control-sm text-center" readonly id="text-stream-cases" value="">
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="embed-responsive embed-responsive-4by3" style=" height: 500px; ">
				<iframe id="iframe-stream-cases" class="embed-responsive-item" src="" frameborder="0" style="border:0" allow="camera; microphone" ></iframe> 
			</div> 
		</div>
	</div>


	
	@endslot
@endcomponent
<!-- /modal -->










