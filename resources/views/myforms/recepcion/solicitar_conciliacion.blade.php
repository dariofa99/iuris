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

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Si presenta algún problema para diligenciar el formulario, por favor comuníquese al correo
                        darioj99@udenar.edu.co o acérquese a las oficinas de la Universidad de Nariño, sede centro.
                    </strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            </div>
        </div>
        <div class="row justify-content-center">

            <div class="col-md-10">

                <div class="card">
                    <div class="card-header">
                        <br>

                        @include('myforms.recepcion.menu_buttons', [
                            'paso' => $paso,
                            'pasos' => $pasos,
                        ])
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
                                        href="/solicitudes/recepcion/conciliacion/{{ $conciliacion->token }}?id={{ Request::get('id') }}&paso={{ $paso_an - 1 }}">
                                        <i style="cursor: pointer" class="fa fa-chevron-circle-left" aria-hidden="true"></i>
                                    </a>
                                @endif



                                @if (isset($conciliacion))
                                    @if (intval($paso) == 6)
                                        <a class="btn btn-success" id="btn_siguiente_replegal"
                                            href="/solicitudes/recepcion/conciliacion/{{ $conciliacion->token }}?id={{ Request::get('id') }}&paso=7">
                                            Siguiente
                                        </a>
                                    @else
                                        <a href="/solicitudes/recepcion/conciliacion/{{ $conciliacion->token }}?id={{ Request::get('id') }}&paso={{ intval($paso) + 1 }}"
                                            data-step="{{ intval($paso) + 1 }}" class="btn btn-success"
                                            data-type="{{ $pasos[$paso - 1]['tipo_usuario'] }}"
                                            id="{{ $pasos[$paso - 1]['id'] }}">
                                            Siguiente
                                        </a>
                                    @endif
                                @else
                                    {{--    <a class="btn btn-success" id="btn_no_apoderado" style="display:none"
                                        href="/solicitudes/recepcion/conciliacion/{{ $conciliacion->token }}?id={{ Request::get('id') }}&paso=4">
                                        Siguiente
                                    </a>  --}}

                                    <button type="button" data-step="2" class="btn btn-success" data-type="205"
                                        id="btn_registrar_conc">
                                        Siguiente
                                    </button>
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
    @include('myforms.conciliaciones.componentes.modal_create_hechos_pretenciones')
    @include('myforms.conciliaciones.componentes.modal_create_document')
     @include('myforms.conciliaciones.componentes.modal_create_anexo')
    @include('myforms.recepcion.frm_modal_infoinderminada')

@endsection
@push('scripts')
    {{-- <script type="module" src={{asset("js/conciliaciones.js")}}></script>
    <script type="module" src={{asset("js/users.js")}}></script>
    --}}
    <script src="{{ asset('/plugins/dropzone59/dropzone59.js') }}"></script>
    <script src={{ asset('js/dropzone_anexos.js') }}></script>

    <script type="module" src={{ asset('js/recepcion_conciliacion.js?v=' . config('app_config.asset_version')) }}></script>
@endpush
