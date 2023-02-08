@component('components.modal')

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
<div id="content_form_user">    
    @include('myforms.conciliaciones.componentes.user_form')
</div>

@endslot
@endcomponent
<!-- /modal -->