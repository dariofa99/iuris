@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/plugins/dropzone59/dropzone.css') }}">
@endpush

@section('content')
    @php
        $paso = $paso ?? (Request::has('paso') ? Request::get('paso') : 1);
        $num_pasos = $num_pasos ?? 4;
        $pasos = $pasos ?? [];
    @endphp

    <div class="container-fluid solicitar-conciliacion-page">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="alert alert-dismissible fade show solicitar-conciliacion-help" role="alert">
                    <i class="fas fa-life-ring" aria-hidden="true"></i>
                    <div class="solicitar-conciliacion-help-copy">
                        <strong>¿Necesitas ayuda con el formulario?</strong>
                        Si presenta algún problema para diligenciarlo, por favor comuníquese al correo
                        darioj99@udenar.edu.co o acérquese a las oficinas de la Universidad de Nariño, sede centro.
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            </div>
        </div>
        <div class="row justify-content-center">

            <div class="col-md-10">

                <div class="card solicitar-conciliacion-card">
                    <div class="card-header solicitar-conciliacion-header iuris-gradient-banner">
                        <div class="iuris-gradient-banner-content">
                            <div class="solicitar-conciliacion-heading">
                                <span class="solicitar-conciliacion-kicker">Atención virtual IURIS</span>
                                <h1>Solicitud de conciliación</h1>
                                <p>Completa cada paso para registrar y continuar con tu solicitud.</p>
                            </div>
                            <div class="solicitar-conciliacion-progress">
                                @include('myforms.recepcion.menu_buttons', [
                                    'paso' => $paso,
                                    'pasos' => $pasos,
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="content_data_conciliaciones">
                        <div class="content_message">
                            @include('myforms.recepcion.menu_mensaje', [
                                'paso' => $paso,
                                'pasos' => $pasos,
                            ])
                        </div>
                        @include('msg.alerts')

                        @if ($paso == 1)
                            @include('myforms.recepcion.frm_parte_solicitante')
                        @else
                            @if (isset($conciliacion))
                                <input type="hidden" value="{{ $conciliacion->id }}" name="conciliacion_id"
                                    id="conciliacion_id">
                            @endif
                            @include($pasos[intval($paso) - 1]['view'], [
                                'conciliacion' => $conciliacion,
                                'tipo_usuario_id' => $pasos[$paso - 1]['tipo_usuario'],
                            ])
                        @endif
                    </div>
                    <div class="card-footer solicitar-conciliacion-footer">
                        @if ($paso > 2)
                            @php
                                $paso_an = $paso;
                                if ($paso == 7) {
                                    if (!$pasos[5]['visible']) {
                                        $paso_an = 6;
                                    }
                                }
                            @endphp
                            <a class="btn btn-outline-secondary" aria-label="Volver al paso anterior"
                                href="/solicitudes/recepcion/conciliacion/{{ $conciliacion->token }}?id={{ Request::get('id') }}&paso={{ $paso_an - 1 }}">
                                <i class="fas fa-arrow-left mr-1" aria-hidden="true"></i>
                                Anterior
                            </a>
                        @else
                            <span></span>
                        @endif

                        <div class="solicitar-conciliacion-footer-actions">
                            @if (isset($conciliacion))
                                @if (intval($paso) == 6)
                                    <a class="btn btn-success solicitar-conciliacion-next" id="btn_siguiente_replegal"
                                        href="/solicitudes/recepcion/conciliacion/{{ $conciliacion->token }}?id={{ Request::get('id') }}&paso=7">
                                        Siguiente
                                        <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i>
                                    </a>
                                @else
                                    <a href="/solicitudes/recepcion/conciliacion/{{ $conciliacion->token }}?id={{ Request::get('id') }}&paso={{ intval($paso) + 1 }}"
                                        data-step="{{ intval($paso) + 1 }}" class="btn btn-success solicitar-conciliacion-next"
                                        data-type="{{ $pasos[$paso - 1]['tipo_usuario'] }}"
                                        id="{{ $pasos[$paso - 1]['id'] }}">
                                        Siguiente
                                        <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i>
                                    </a>
                                @endif
                            @else
                                <button type="button" data-step="2" class="btn btn-success solicitar-conciliacion-next"
                                    data-type="205" id="btn_registrar_conc">
                                    Siguiente
                                    <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i>
                                </button>
                            @endif

                            <a href="/login" class="btn btn-default solicitar-conciliacion-cancel">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
    @include('myforms.conciliaciones.componentes.modal_create_hechos_pretenciones')
    @include('myforms.conciliaciones.componentes.modal_create_document')
    @include('myforms.conciliaciones.componentes.modal_create_anexo')
    @include('myforms.recepcion.frm_modal_infoinderminada')
    @include('myforms.recepcion.frm_modal_recuperar_cuenta_detalles')

@endsection
@push('scripts')
    {{-- <script type="module" src={{asset("js/conciliaciones.js")}}></script>
    <script type="module" src={{asset("js/users.js")}}></script>
    --}}
    <script src="{{ asset('/plugins/dropzone59/dropzone59.js') }}"></script>
    <script src={{ asset('js/dropzone_anexos.js') }}></script>

    <script type="module" src={{ asset('js/recepcion_conciliacion.js?v=' . config('app_config.asset_version')) }}></script>
@endpush
