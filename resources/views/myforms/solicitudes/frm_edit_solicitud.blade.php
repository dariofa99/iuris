@extends('layouts.dashboard')
@section('titulo_area')
    <i>Número de solicitud:</i> {{ $solicitud->number }} <br> <i>Estado de la solicitud:</i>
    <span id="lbl_status_sol"> {{ $solicitud->estado->ref_nombre }} </span>
@endsection

@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('area_buttons')
    @if ($solicitud->type_status_id == 155 || $solicitud->type_status_id == 156)
        <p><i>Número de turno:</i> {{ $solicitud->turno }}</p>
        <div class="" id="con_timer" @if ($solicitud->type_status_id == 155 || $solicitud->type_status_id == 156) style="display:none" @endif>
            <input type="hidden" id="tiempo_espera" value="{{ $solicitud->tiempo_espera }}">
            <h6>Tiempo disponible para entrar al chat: <div id="clock"></div>
            </h6>
        </div>
    @endif
    @if (count($solicitud->expedientes) > 0)
        <p>No. Expediente: {{ $solicitud->expedientes[0]->expid }}</p>
    @endif
    <p>Categoria: <label id="lbl_cta_ref_n"> {{ $solicitud->categoria->ref_nombre }} </label> </p>
@endsection

@section('area_forms')
    @include('msg.success')
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link urlactive active" id="datos-generales" data-toggle="tab" href="#datos-generales-tab"
                        role="tab" aria-controls="datos-generales-tab" aria-selected="true">
                        Datos generales
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link urlactive" id="chat" data-toggle="tab" href="#chat-tab" role="tab"
                        aria-controls="chat" aria-selected="false">
                        Chat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link urlactive" id="asig-expediente" data-toggle="tab" href="#asig-expediente-tab"
                        role="tab" aria-controls="asig-expediente-tab" aria-selected="false">
                        Asignar expediente
                    </a>
                </li>
            </ul>
        </div>
    </div>
 
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content" id="myTabContent" style="margin-top: 10px !important">
                <input type="hidden" id="solicitud_id" value="{{ $solicitud->id }}">
                <input type="hidden" value="{{ $solicitud->type_status_id }}" id="soli_type_status_id">
                <input type="hidden" id="solicitudTk" value="{{ $solicitud->token }}">
                <div class="tab-pane fade active show" id="datos-generales-tab" role="tabpanel"
                    aria-labelledby="datos-generales-tab">
                    @include('myforms.solicitudes.frm_solicitud')
                </div>

                <div class="tab-pane fade" id="chat-tab" role="tabpanel" aria-labelledby="chat-tab">
                    <div class="row">
                        <div class="col-md-9">
                            {!! \Facades\App\Facades\ApiChat::room($solicitud->number)->render() !!}
                        </div>
                        <div class="col-md-3">
                            <table id="tblListFilesShared" class="table">
                                <thead>
                                    <th>
                                        Documentos compartidos
                                    </th>
                                </thead>
                                <tbody>
                                    @include('myforms.recepcion.expedientes.chat.files_ajax')
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- @include('myforms.solicitudes.frm_chat') --}}
                    {{--     @include("myforms.recepcion.expedientes.chat.chat", ['token' => 1234])
                --}}

                </div>

                <div class="tab-pane fade" id="asig-expediente-tab" role="tabpanel" aria-labelledby="asig-expediente-tab">
                    @include('myforms.solicitudes.frm_asignar_expediente')
                </div>
            </div>
        </div>
    </div>









    {{--  @include('myforms.components_exp.frm_modal_videollamada', ['user_idnumber' => $solicitud->idnumber])
    @include('myforms.solicitudes.frm_modal_user_register')
    @include('myforms.solicitudes.frm_modal_acept_solicitud')
    @include('myforms.solicitudes.frm_modal_denied_solicitud')
    @include('myforms.solicitudes.frm_modal_adm_documentos')
    @include('myforms.frm_expediente_user_edit') --}}

@stop
@push('scripts')
    <script src="{{ asset('js/timer.js') }}"></script>
    <script src="{{ asset('js/recepcion.js') }}"></script>
@endpush
