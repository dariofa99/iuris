@foreach ($conciliacion->usuarios()->where('tipo_usuario_id', 197)->get() as $key => $user)
    <div class="card p-2 list_user_rep_legal_form-{{ $key }}">
        <div class="card-header">
            Información de la persona convocada
            <span class="badge badge-info">
                {{ $key + 1 }}

            </span>
        </div>
        <div class="card-body">

            <form id="myUserParteSolicitadaForm" data-view="user_partesolicitada_form"
                data-content="user_solicitante_form">
                <div class="row">
                    @include('myforms.conciliaciones.componentes.formulario_parte_solicitada')
                </div>
            </form>
        </div>
    </div>
@endforeach
