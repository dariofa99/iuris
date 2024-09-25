@extends('layouts.app')

@push('styles')
    {!! Html::style('/css/jitsi.css?v=2') !!}

    <link rel="stylesheet" href="{{ asset('/plugins/dropzone59/dropzone.css') }}">
@endpush

@section('content')
    @php

        $paso = Request::has('paso') ? Request::get('paso') : 1;
        $num_pasos = 4;
        $pasos = [
            0 => [
                'id' => 'btn_registrar_solexp',
                'tipo_usuario' => 205,
                'visible' => true,
                'title' => 'Solicitud',
                'message' =>
                    "Diligencia el siguiente formulario con la información de la persona que solicita la asesoría, recuerda que si ya tienes una cuenta debes <a href='/login'>iniciar sesión</a> para realizar una nueva solicitud. Todos los campos son obligatorios.",
                'view' => 'myforms.recepcion.frm_solicitud_form',
                'toltip'=>"Formulario inicial de solicitud"
            ],
            1 => [
                'id' => 'btn_sala_espera',
                'tipo_usuario' => 195,
                'visible' => true,
                'title' => 'Espera',
                'message' => 'Espera el turno, cuando la persona asesora este disponible revisará la solicitud.',
                'view' => 'myforms.recepcion.expedientes.sala_espera.sala_espera',
                 'toltip'=>"Proceso donde se evalua la solicitud por la persona asesora"
            ],
            2 => [
                'id' => 'btn_culminar_regus',
                'tipo_usuario' => 196,
                'visible' => true,
                'title' => 'Registro',
                'message' => 'Para activar la sala virtual se necesita terminar el registro.',
                'view' => 'myforms.recepcion.expedientes.frm_parte_solicitante',
                'toltip'=>"Proceso donde la persona solicitante cúlmina el registro de información"
            ],
            3 => [
                'id' => 'btn_',
                'tipo_usuario' => 196,
                'visible' => true,
                'title' => 'Virtual',
                'message' => 'Recuerda tener activado el micrófono.',
                'view' => 'myforms.recepcion.expedientes.chat.chat',
                'toltip'=>"Proceso donde se asesora a la persona solicitante "
            ],
        ];

        $num_pasos = count($pasos);
    @endphp

    <div class="container-fluid">
        <div class="row justify-content-center">
            @if (!isset($solicitud))
                <div class="col-md-3">

                    <div class="card">
                        <div class="card-header">
                            Recuperar solicitud
                        </div>
                        <div class="card-body">
                            <form id="myFormBuscarSolicitud">
                                <div class="form-group has-feedback"><label for="name">No. Solicitud<span
                                            class="ast_required">*</span></label>
                                    <input id="number" value="" name="codigo_solicitud" required type="text"
                                        class="form-control form-control-sm onlynumber required" data-toggle="tooltip"
                                        title="" placeholder="Número de solicitud" maxlength="20"
                                        data-original-title="Número de solicitud">

                                </div>
                                <button type="submit" class="btn btn-success">
                                    BUSCAR
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-9">

                <div class="card">
                    <div class="card-header">
                        <br>

                        @include('myforms.recepcion.menu_buttons', [
                            'paso' => $paso,
                            'pasos' => $pasos,
                        ])
                    </div>
                    <div class="card-body">
                        <div class="content_message">
                            @include('myforms.recepcion.menu_mensaje', [
                                'paso' => $paso,
                                'pasos' => $pasos,
                            ])
                        </div>

                        @if ($paso == 1)
                            @include($pasos[0]['view'])
                        @else
                            @if (isset($solicitud))
                                <input type="hidden" value="{{ $solicitud->id }}" name="solicitud_id" id="solicitud_id">
                            @endif
                            @include($pasos[$paso - 1]['view'], ['token' => $solicitud->number])

                            {{-- @include($pasos[intval($paso)-1]['view'],[
                'conciliacion'=>$conciliacion,
                'tipo_usuario_id'=>$pasos[$paso-1]['tipo_usuario']
             ]) --}}
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="row">

                            <div class="col-md-12">
                                @if ($paso > 2)
                                    @php
                                        $paso_an = $paso;
                                        if ($paso == 7) {
                                            if (!$pasos[5]['visible']) {
                                                $paso_an = 6;
                                            }
                                        }
                                    @endphp
                                    <a
                                        href="/solicitudes/recepcion/conciliacion/?id={{ Request::get('id') }}&paso={{ $paso_an - 1 }}">
                                        <i style="cursor: pointer" class="fa fa-chevron-circle-left" aria-hidden="true"></i>
                                    </a>
                                @endif

                                <button type="button" data-step="{{ intval($paso) + 1 }}" class="btn btn-success"
                                    data-type="{{ $pasos[$paso - 1]['tipo_usuario'] }}" id="{{ $pasos[$paso - 1]['id'] }}">
                                    Siguiente
                                </button>
                                @if (isset($conciliacion))
                                    <a class="btn btn-success" id="btn_no_apoderado" style="display:none"
                                        href="/solicitudes/recepcion/conciliacion/{{ $conciliacion->token }}?id={{ Request::get('id') }}&paso=4">
                                        Siguiente
                                    </a>
                                @endif
                                <a href="/login" class="btn btn-default">
                                    Cancelar
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
    @include('myforms.recepcion.expedientes.chat.frm_modal_create_document')

@endsection
@php
    $conciliacion = App\Conciliacion::first();
@endphp
@push('scripts')
    @include('myforms.conciliaciones.script')
    <script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
    <script src="https://meet.jit.si/external_api.js"></script>
    {!! Html::script('js/config_jitsi.js?v=3') !!}
    {{-- <script type="module" src={{asset("js/conciliaciones.js")}}></script>
    <script type="module" src={{asset("js/users.js")}}></script>
    --}}
    <script src="{{ asset('/plugins/dropzone59/dropzone59.js') }}"></script>
    <script src={{ asset('js/dropzone_anexos_solicitudes.js') }}></script>

    <script type="module" src={{ asset('js/recepcion_expedientes.js') }}></script>
    <script>
        var channel = Echo.channel('cambiosEstadoSolicitud');

        channel.listen('.solicitud-estados', function(data) {
            var myTk = $("#solicitudTk").val()
            if (myTk == data.data.token) {
                location.reload()
            }
        });
        tippy('[data-tippy-content]');

    </script>
@endpush
