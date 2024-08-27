@php
    $user = $conciliacion->getUser(197);
    $users = $conciliacion->usuarios()
    ->where("tipo_usuario_id",197)->get();
    $numConv = $conciliacion->getStaticDataValByShortName('no._convocados', 'asunto');
    $numConv = $numConv->value;
    //dd($users);
@endphp

<div id="contentFormsParteCovocada">

@if ($numConv > 0)
    @for ($i = 0; $i < $numConv; $i++)
    @php
        $user = isset($users[$i]) ? $users[$i] : $conciliacion->getUser(00);;
    @endphp
        <form id="myFormParteConvocada_{{$i}}" class="myFormParteConvocada" method="POST">
            <div class="box_section">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            Ingrese la información de la persona convocada 
                            <span class="badge badge-light">{{$i + 1}}</span>
                        </div>
                    </div>
                </div>
                <div class="row" id="content_solicitada"
                    style="display: {{ ($user->idnumber == null and $conciliacion->getStaticDataValByShortName('informacion_parte_convocada_(ej._nombres,_direccion,_telefono)', 'parte_solicitada')) ? 'none' : 'auto' }}">
                    @include('myforms.conciliaciones.componentes.formulario_parte_solicitada', [
                        'disabled' => $user->idnumber == null ?: 'disabled',
                    ])
                </div>

            </div>
        </form>
        <hr>
    @endfor
@endif

</div>