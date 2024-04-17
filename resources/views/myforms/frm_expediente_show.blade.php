@extends('layouts.dashboard')


@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
    <style>

    </style>
@endpush

@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('titulo_area')
    @if (currentUser()->hasRole('solicitante'))
        Casos
    @else
        @include('myforms.components_exp.frm_datos_docente')
    @endif

@endsection


@section('area_buttons')
    <div class="float-right" style="float: right !important;">
        <a href="#" class="btn-atrasexed  btn bg-gray" style="color:#777">
            <i class="fa fa-backward"></i> Atrás</a>
    </div>
@endsection


@section('area_forms')
    @php
        if (!currentUser()->hasRole('estudiante')) {
            $disabled = 'disabled';
        } else {
            if ($expediente->expestado_id == '1' or $expediente->expestado_id == '3') {
                $disabled = '';
            } else {
                $disabled = 'disabled';
            }
        }
    @endphp
    @include('msg.alerts')

    <ul class="nav nav-tabs" id="myTab" role="tablist">

        <li class="nav-item">
            <a class="nav-link active urlactive" id="chat-tab" data-toggle="tab" href="#chat_tab" role="tab"
                aria-controls="chat_tab" aria-selected="false">
                Oficina virtual
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link urlactive" id="case_data-client" data-toggle="tab" href="#case_data" role="tab"
                aria-controls="case_data" aria-selected="false">
                Datos del caso
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link urlactive" id="act-data" data-toggle="tab" href="#act_data" role="tab"
                aria-controls="act_data" aria-selected="true">
                Actuaciones
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link urlactive" id="requerimientos_tab" data-toggle="tab" href="#requerimientos" role="tab"
                aria-controls="requerimientos" aria-selected="false">
                Cita o req. a solicitante
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link urlactive" id="cierre_caso-tab" data-toggle="tab" href="#cierre_caso" role="tab"
                aria-controls="cierre_caso" aria-selected="false">
                Cierre de caso
            </a>
        </li>
        @if (count($expediente->asignaciones) > 1 ||
                (currentUser()->hasRole('amatai') or
                    currentUser()->hasRole('diradmin') or
                    currentUser()->hasRole('coordprac') or
                    currentUser()->hasRole('dirgral')) and
                $expediente->expestado_id != 2)
            <li class="nav-item">
                <a class="nav-link urlactive" id="reasignar-tab" data-toggle="tab" href="#reasignar_caso" role="tab"
                    aria-controls="reasignar" aria-selected="false">
                    Reasignar Caso
                </a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link urlactive" id="citaciones-tab" data-toggle="tab" href="#citaciones" role="tab"
                aria-controls="citaciones" aria-selected="false">
                Citaciones
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link urlactive" id="autorizaciones-tab" data-toggle="tab" href="#autorizaciones" role="tab"
                aria-controls="autorizaciones" aria-selected="false">
                Autorizaciones
            </a>
        </li>

    </ul>

    <div class="tab-content" id="myTabContent" style="margin-top: 10px !important">

        <div class="tab-pane fade show active" id="chat_tab" role="tabpanel" aria-labelledby="chat-tab">
            <div class="row">
                <div class="col-md-12 content_oficina_virtual" id="content_oficina_virtual">
                    {{-- @include('myforms.components_exp.frm_oficina_virtual') --}}
                </div> <!-- /.md12-->
            </div>
        </div>

        <div class="tab-pane fade " id="case_data" role="tabpanel" aria-labelledby="case-data-tab">
            @include('myforms.components_exp.frm_datos_caso')
            @include('myforms.components_exp.frm_asesorias_caso')
            @include('myforms.components_exp.frm_notas_caso')
        </div>

        <div class="tab-pane fade " id="act_data" role="tabpanel" aria-labelledby="act-data-tab">
            <div id="frm_actuacion_create">
                @include('myforms.frm_actuacion_create')
                @include('myforms.frm_actuacion_list')
            </div>
        </div>

        <div class="tab-pane fade " id="requerimientos" role="tabpanel" aria-labelledby="requerimientos-tab">
            @if ($expediente->exptipoproce_id == '2')
                @include('myforms.frm_requerimiento_create')
            @else
                Opción inactiva para Consulta simple
            @endif
        </div>

        <div class="tab-pane fade " id="cierre_caso" role="tabpanel" aria-labelledby="cierre_caso-tab">
            <div class="row">
                <div class="col-md-12">
                    @include('myforms.frm_expediente_cierre_caso')
                </div> <!-- /.md12-->
            </div>
        </div>

        <div class="tab-pane fade " id="reasignar_caso" role="tabpanel" aria-labelledby="reasignar-tab">
            <div class="row">
                <div class="col-md-12">
                    @include('myforms.components_exp.frm_reasignar_caso')
                </div> <!-- /.md12-->
            </div>
        </div>
        <div class="tab-pane fade " id="citaciones" role="tabpanel" aria-labelledby="citaciones-tab">
            <div class="row">
                <div class="col-md-12">
                    @include('myforms.components_exp.frm_citaciones_estudiante')
                </div> <!-- /.md12-->
            </div>
        </div>
        <div class="tab-pane fade " id="autorizaciones" role="tabpanel" aria-labelledby="autorizaciones-tab">
            <div class="row">
                <div class="col-md-12">
                    @include('myforms.components_exp.frm_autorizaciones')
                </div> <!-- /.md12-->
            </div>
        </div>

    </div>
    @include('myforms.frm_add_asesoria_docente')
    @include('myforms.frm_update_asesoria_docente')
    @include('myforms.frm_add_nota_final_expedientes')
    @include('myforms.frm_addnew_nota_final_expedientes')
    @include('myforms.frm_calificacion_edit')
    @include('myforms.frm_modal_cambiar_docente_exp')
    @include('myforms.components_exp.frm_modal_create_autorizacion')
    @include('myforms.frm_modal_dinamyc_js')
    @include('myforms.frm_modal_adm_documentos')
    @include('myforms.components_exp.frm_modal_create_notificacion')
    @include('myforms.components_exp.frm_modal_exp_edit_cierre_caso')
    @include('myforms.frm_modal_details_not_caso')
    @include('myforms.components_exp.frm_modal_fechalimitres')
    @include('myforms.components_exp.frm_modal_show_details_estadocaso')
    @include('myforms.components_exp.frm_modal_citaciones_estudiante')
    @include('myforms.frm_requerimiento_edit')
    @include('myforms.frm_requerimiento_asist')
    @include('myforms.frm_requerimiento_details')
    @include('myforms.components_exp.frm_modal_create_requerimiento')
    @include('myforms.components_exp.frm_modal_create_actuacion')
    @include('myforms.components_exp.frm_modal_pausar_expediente')
    @include('myforms.components_exp.frm_modal_cerrar_nota_minima')
    @if (count($expediente->solicitudes) > 0)
        @include('myforms.components_exp.frm_modal_videollamada', [
            'user_idnumber' => $expediente->expidnumber,
        ])
    @endif  
        @include('myforms.frm_expediente_user_edit')
 
        @include('myforms.frm_expediente_user_details')
 

@stop
@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>
    <!-- InputMask -->
    {!! Html::script('plugins/input-mask/jquery.inputmask.js') !!}
    {!! Html::script('plugins/input-mask/jquery.inputmask.date.extensions.js') !!}
    {!! Html::script('plugins/input-mask/jquery.inputmask.extensions.js') !!}
    <script type="module"   src={{asset("js/admin_expedientes.js")}}></script>
@endpush
