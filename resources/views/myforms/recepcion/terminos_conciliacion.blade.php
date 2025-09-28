@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-gradient-default text-white text-center py-4">
            <h3 class="mb-0" style="color: black">✨ Antes de empezar</h3>
        </div>

        <div class="card-body p-4">
            <!-- Mensaje de éxito -->
          

            <!-- Tabs -->
            <ul class="nav nav-pills justify-content-center mb-4" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="servicios-tab" data-toggle="tab" href="#servicios_tab" role="tab"
                        aria-controls="servicios_tab" aria-selected="true">
                        <i class="fas fa-handshake mr-1"></i> Servicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tramites-tab" data-toggle="tab" href="#tramites_tab" role="tab"
                        aria-controls="tramites_tab" aria-selected="false">
                        <i class="fas fa-file-alt mr-1"></i> Trámite Audiencia
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <!-- Tab 1 -->
                <div class="tab-pane fade show active" id="servicios_tab" role="tabpanel" aria-labelledby="servicios-tab">
                    <p class="text-justify">
                        <strong>Población objeto:</strong> La población objeto del Centro
                            de Conciliación “Eduardo Alvarado Hurtado” a
                            quien presta sus servicios, corresponde a la
                            población del Departamento de Nariño y en
                            especial a la ciudadanía de Pasto de estratos
                            económicos 1,2, 3 y personas que se encuentren
                            en situación económica precaria y población
                            vulnerable, como personas en situación de
                            desplazamiento forzado, madres comunitarias
                            activas, discapacitados, padres o madres
                            cabeza de familia, adultos mayores y miembros
                            de minorías étnicas.
                    </p>

                    <p><strong>Servicios ofrecidos:</strong></p>
                    <ul class="list-group mb-3">
                        <li class="list-group-item">
                            ✅ Conciliación totalmente gratuita para población de escasos recursos.
                        </li>
                        <li class="list-group-item">
                            ⚖️ Solución alternativa de conflictos en las áreas: civil, comercial, familia, y en
                                general en cualquier asunto susceptible de transacción o desistimiento.
                        </li>
                    </ul>

                    <div class="text-center">
                        <a id="tramites-continuar" href="#tramites_tab" class="btn btn-outline-primary">
                            Continuar <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Tab 2 -->
                <div class="tab-pane fade" id="tramites_tab" role="tabpanel" aria-labelledby="tramites-tab">
                    <p class="text-justify">
                        Se verificarán las circunstancias del asunto y si es admisible, por lo cual se estudiará si el
                            asunto es conciliable o no, conforme las competencias radicadas en el centro de conciliación.
                            <br>
                            Una vez verificada la solicitud y admitida por el Centro de Conciliación, se realizan las citaciones
                            a las partes, con la indicación de la fecha, hora y medio por el cual se desarrollará la audiencia
                            de conciliación.
                            <br>
                            Para tal efecto, se designará un estudiante adscrito al Centro de Conciliación.
							<br>
                    </p>
                    <div class="alert alert-info" style="font-size: 20px">
                        <i class="fas fa-info-circle"></i>
                        Recuerde: Para la solicitud es necesario el número de documento del convocado.  
                        Si es persona jurídica, adjunte certificado de existencia (PDF, no mayor a 3 meses).
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="valida_regla">
                        <label class="form-check-label" for="valida_regla">
                            Comprendo los términos y condiciones
                        </label>
                    </div>

                    <div class="text-center">
                        <button id="btn_continuar_conciliacion" disabled class="btn btn-primary btn-lg px-4">
                            Continuar (en periodo de prueba)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@push('scripts')

<!-- FontAwesome para los íconos -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
@if (config('app.name') != 'ConciliApp')
<script type="module" src={{ asset('js/admin_login.js?v='. config('app_config.asset_version')) }}></script>
@endif   

@endpush