@component('components.modal')

@slot('trigger')
myModal_conc_user_detalles
@endslot

@slot('title')
Detalles
@endslot


@slot('body')


@section('msg-contenido')
Registrado 
@endsection
@include('msg.ajax.success')
<div id="content_detalles_user">    
    {{-- @include('myforms.conciliaciones.componentes.user_detalles_form') --}}
</div>

@endslot
@endcomponent
<!-- /modal -->