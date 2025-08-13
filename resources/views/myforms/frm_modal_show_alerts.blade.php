@component('components.b4.modal_extra_large')

	@slot('trigger')
		mymodalShowAlerts
	@endslot
	@slot('size')
	modal-dialog modal-lg 
	@endslot

	@slot('title')
		<h3>Información importante!</h3>
	@endslot
  @push('styles')
  <!-- aqui van los estilos de cada vista -->
  <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
  <style>

  </style>
@endpush
 
	@slot('body')


@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')
<div id='modal-show-alerts-content'>
    
   
</div>

	@endslot

  @slot('footer')

<div id="contentNotButtonDis">

</div>
  
    @endslot

@endcomponent
<!-- /modal -->










