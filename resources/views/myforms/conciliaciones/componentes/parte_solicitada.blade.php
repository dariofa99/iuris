@php
    $user = $conciliacion->getUser(197);
@endphp
<div class="row">
    <div class="col-md-12">
        <h4 align="center"> <strong> PARTE SOLICITADA </strong>
                   </h4>

    </div>
</div>

<div class="row" id="content_solicitada"
    style="display: {{ ($user->idnumber != null) ? 'block' : 'none' }};width:100%">
    
    <div id="user_solicitante_form">
        @include('myforms.conciliaciones.componentes.user_partesolicitada_form')
    </div>
</div> 

<div class="row" id="content_detalles_solicitada"
    style="display: {{ ($user->idnumber == null) ? 'block' : 'none' }}">
    <div class="col-md-12">
        <div class="form-group">
            @include('myforms.conciliaciones.componentes.asunto', [
                'section' => 'parte_solicitada',
                'col' => 12,
            ])

        </div>
    </div>
</div>
