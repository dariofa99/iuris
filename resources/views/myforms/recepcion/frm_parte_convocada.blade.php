@php
    $user = $conciliacion->getUser(197);
@endphp
<form id="myFormParteConvocada" method="POST">
    <div class="box_section">      
        
        <div class="row" id="content_solicitada" style="display: {{ ($user->idnumber == null and $conciliacion->getStaticDataValByShortName('informacion_parte_convocada_(ej._nombres,_direccion,_telefono)', 'parte_solicitada')) ? 'none' : 'auto' }}">
            @include('myforms.conciliaciones.componentes.formulario_parte_solicitada', [
                'disabled' => $user->idnumber == null ?: 'disabled',
            ])
        </div>
        <div class="row" id="content_detalles_solicitada" style="display: {{ ($user->idnumber == null and $conciliacion->getStaticDataValByShortName('informacion_parte_convocada_(ej._nombres,_direccion,_telefono)', 'parte_solicitada')) ? 'auto' : 'none' }}">
            <div class="col-md-12">
                <div class="form-group">
                    @include('myforms.conciliaciones.componentes.asunto', [
                        'section' => 'parte_solicitada',
                        'col' => 12,
                        'disabled' => '',
                    ])
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="checkbox">
                    <h3>
                        Marque la casilla en caso de no tener toda la información de la parte convocada
                        <input
                            {{ ($user->idnumber == null and $conciliacion->getStaticDataValByShortName('informacion_parte_convocada_(ej._nombres,_direccion,_telefono)', 'parte_solicitada')) ? 'checked' : '' }}
                            class="chk_not_parte" id="chk_not_parte" type="checkbox">
                    </h3>
                </div>
            </div>
        </div>
</form>
