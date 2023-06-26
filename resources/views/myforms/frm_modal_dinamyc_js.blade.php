@component('components.modal_dynamic')

	@slot('trigger')
		mymodaljs
	@endslot
	@slot('size')
	modal-dialog modal-lg
	@endslot

	@slot('title')
		<div id="mymodal-dinamyc-tittle" >Historial datos del caso:</div>
	@endslot

 
	@slot('body')


@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')
<div id='modal-conten-js' style="">

</div>

	@endslot
@endcomponent
<!-- /modal -->










