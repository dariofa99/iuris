@component('components.b4.modal_large')




@slot('trigger')
myModal_conc_user_create
@endslot

@slot('title')

@endslot


@slot('body')


@section('msg-contenido')
Registrado 
@endsection
@include('msg.ajax.success')
<input type="hidden" id="tipo_usuario_id" name="tipo_usuario_id">
<input type="hidden" id="section" name="section">
<div id="user_gen_conciliacion_form">    
   @include('myforms.conciliaciones.componentes.user_general_form')
</div>
<div class="col-md-12">
    <input type="button" id="btn_crear_usuario_conciliacion" value="Asignar usuario" class="btn btn-primary btn-block">
	{{-- <input type="button" id="btn_actualizar_usuario_conciliacion" value="Actualizar usuario" class="btn btn-warning btn-block"> --}}
</div>
@endslot
@endcomponent
<!-- /modal -->