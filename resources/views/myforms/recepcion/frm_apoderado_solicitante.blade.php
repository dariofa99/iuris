@php
    $user = $conciliacion->getUser(196);
@endphp
<form id="myFormApoderado">
    <div class="row" id="content_apoderado_solicitud">
        @include('myforms.conciliaciones.componentes.formulario_apoderado', [
            'disabled' => !Request::has('id') || $user->idnumber != null ? 'disabled' : '',
        ])
    </div>
    <div class="row mb-1">
        <div class="col-md-12">
            <h4>
                Marque la siguiente casilla en caso de no contar con un apoderado
                <input class="chk_not_parte" id="chk_not_parte_apoderado" type="checkbox">
            </h4>
        </div>
    </div>

</form>
