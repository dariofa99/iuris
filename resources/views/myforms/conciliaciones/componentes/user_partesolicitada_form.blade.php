@foreach ($conciliacion->usuarios()->where('tipo_usuario_id', 197)->get() as $key => $user)
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                Información de la persona convocada
                <span class="badge badge-light">{{ $key + 1 }}</span>
            </div>
        </div>
    </div>
    <form id="myUserParteSolicitadaForm" data-view="user_partesolicitada_form" data-content="user_solicitante_form">
        <div class="row">
            @include('myforms.conciliaciones.componentes.formulario_parte_solicitada')
        </div>
    </form>
@endforeach
