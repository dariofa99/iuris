@extends('layouts.dashboard')
@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    {!! Html::style('/css/jitsi.css?v=2') !!}

    <style>
        .input_time{
            display: block !important;
            border: none !important;
            font-size: 25px !important;
            width: 100% !important;
            background-color: rgb(255, 255, 255) !important;
        }
        .container-meet {
            /*position: relative;
                    border:1px red  solid;*/
            width: 100%;
            height: 600px;
            margin-bottom: 30px;
            text-align: center;
        }

        .toolbox {
            /* position: absolute;*/
            bottom: 0px;
            /*border:1px black solid;*/
            width: 100%;
            height: 50px;
            background-color: rgb(71, 71, 71);
        }

        #jitsi-meet-conf-container {
            width: 100%;
            height: 570px;
        }
    </style>
@endpush
@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('titulo_area')
    <div class="row">
        <div class="col-md-12">
            Número: <strong>{{ $conciliacion->num_conciliacion }}</strong><br>
            <span style="color:#ffffff;background-color: {{ $conciliacion->estado->color }}" class="badge">
                Estado: {{ $conciliacion->estado->ref_nombre }} {{ $conciliacion->estado->id }}</span>
        </div>
    </div>


@endsection
@section('area_buttons')
    <label class="pull-right">

        Fecha radicado:

        @if ($conciliacion->fecha_redicado != '0000-00-00')
            {{ $conciliacion->fecha_radicado }}
        @else
            ---
        @endif

    </label>



@endsection

@section('area_forms')

    @include('msg.success')

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">

                @if(currentUser()->can('ver_form_conciliacion') 
                || (currentUserInConciliacion($conciliacion->id,['conciliador','asistente','autor'])))
                <li class="nav-item">
                    <a class="nav-link active urlactive" id="info-tab" data-toggle="tab" href="#info_tab" role="tab"
                        aria-controls="chat_tab" aria-selected="false">
                        Información de Solicitud
                    </a>
                </li>
                @endif


                @if( currentUser()->can('ver_documentos_conciliacion') ||
                (currentUserInConciliacion($conciliacion->id,['conciliador','asistente'])
               and $conciliacion->getUser(203)->pivot and $conciliacion->getUser(203)->pivot->user_id == auth()->user()->id and 
               $conciliacion->getUser(203)->pivot->estado_id == 230
               ))
                <li class="nav-item">
                    <a class="nav-link urlactive" id="documentos-tab" data-toggle="tab" href="#documentos" role="tab"
                        aria-controls="documentos_tab" aria-selected="false">
                        Documentos
                    </a>
                </li>
                @endif
                @if((currentUser()->can('ver_comentarios_conciliacion'))
                || ((currentUserInConciliacion($conciliacion->id,['autor'])))
                || ($conciliacion->getUser(203)->pivot and $conciliacion->getUser(203)->pivot->user_id == auth()->user()->id 
                and $conciliacion->getUser(203)->pivot->estado_id == 230)
                )
                <li class="nav-item">
                    <a class="nav-link urlactive" id="notificaciones-tab" data-toggle="tab" href="#notificaciones"
                        role="tab" aria-controls="notificaciones" aria-selected="false">
                        Notificaciones
                    </a>
                </li>
                @endif
                @if(((currentUser()->can('ver_estados_conciliacion')))
                || ((currentUserInConciliacion($conciliacion->id,['conciliador','auxiliar']) and (
                   ($conciliacion->getUser(203)->pivot and $conciliacion->getUser(203)->pivot->user_id == auth()->user()->id
                    and $conciliacion->getUser(203)->pivot->estado_id == 230)))) 
                || ($conciliacion->getUser(199)->pivot and $conciliacion->getUser(199)->hasRole('estudiante') and currentUserInConciliacion($conciliacion->id,['autor']))
                )
                <li class="nav-item">
                    <a class="nav-link urlactive" id="estados-tab" data-toggle="tab" href="#estado"
                        role="tab" aria-controls="estado" aria-selected="false">
                        Estado de la solicitud
                    </a>
                </li>
                @endif

                @if((currentUser()->can('ver_asignaciones_conciliacion'))
        || ((currentUserInConciliacion($conciliacion->id,['conciliador','auxiliar']) 
        and (( $conciliacion->getUser(203)->pivot and $conciliacion->getUser(203)->pivot->user_id == auth()->user()->id
             and $conciliacion->getUser(203)->pivot->estado_id == 230)))))
       
       <li class="nav-item">
        <a class="nav-link urlactive" id="usuario-tab" data-toggle="tab" href="#usuarios"
            role="tab" aria-controls="estado" aria-selected="false">
            Usuarios
        </a>
    </li>


          
            @endif

            @if($audiencia!='' || (currentUserInConciliacion($conciliacion->id,['conciliador','auxiliar']) and ( 
            ($conciliacion->getUser(203)->pivot and $conciliacion->getUser(203)->pivot->user_id == auth()->user()->id
             and $conciliacion->getUser(203)->pivot->estado_id == 230)))
            || currentUser()->can('ver_audiencia_conciliacion'))

                <li class="nav-item">
                    <a class="nav-link urlactive" id="audiencia-tab" data-toggle="tab" href="#audiencia"
                        role="tab" aria-controls="audiencia" aria-selected="false">
                        Audiencia
                    </a>
                </li>
            @endif

            

            </ul>

            <div class="tab-content" id="myTabContent" style="margin-top: 10px !important">

                <div class="tab-pane fade show active" id="info_tab" role="tabpanel" aria-labelledby="info-tab">
                    @include('myforms.conciliaciones.conciliacion_form')
                </div>                
                <div class="tab-pane fade " id="estado" role="tabpanel" aria-labelledby="estado-tab">
                    @include('myforms.conciliaciones.conciliacion_estados')
                </div>
                <div class="tab-pane fade " id="documentos" role="tabpanel" aria-labelledby="documentos-tab">
                    @include('myforms.conciliaciones.documentos')
                </div>
                <div class="tab-pane fade " id="notificaciones" role="tabpanel" aria-labelledby="notificaciones-tab">
                    @include('myforms.conciliaciones.conciliacion_comentarios')
                </div>              
                <div class="tab-pane fade " id="usuarios" role="tabpanel" aria-labelledby="usuarios-tab">
                    @include('myforms.conciliaciones.conciliacion_asignaciones')
                </div>
                <div class="tab-pane fade " id="audiencia" role="tabpanel" aria-labelledby="audiencia-tab">
                    @include('myforms.conciliaciones.conciliacion_audiencia')
                </div>
            </div>
                    
        </div>
    </div>



    @include('myforms.conciliaciones.componentes.modal_create_hechos_pretenciones')
    @include('myforms.conciliaciones.componentes.modal_reportes_pdf_estados')
    @include('myforms.conciliaciones.componentes.modal_create_document')
    {{-- @include('myforms.conciliaciones.componentes.modal_create_estado') --}}
    @include('myforms.conciliaciones.componentes.modal_detalles_notificaciones')
    @include('myforms.conciliaciones.componentes.modal_create_user')
    @include('myforms.conciliaciones.componentes.modal_create_estado_pretension')
    @include('myforms.conciliaciones.componentes.modal_detalles_user')
    @include('myforms.conciliaciones.componentes.modal_audiencia_salas_alternas')
    @include('myforms.conciliaciones.componentes.modal_add_notas')
    @include('myforms.conciliaciones.componentes.modal_edit_notas')
    @include('myforms.conciliaciones.componentes.modal_reportes_archivos_compartidos')
    @include('myforms.conciliaciones.componentes.modal_respuestas_asignaciones')

@stop
 
@push('scripts')
  <script src="{{ asset('plugins/summernote-0.8/summernote.min.js') }}"></script>
  
    <!-- aqui van los scripts de cada vista -->
    <script type="module" src={{ asset('js/admin_conciliacion.js') }}></script>

    {{-- {!! Html::script('js/audiencia_conciliacion.js?v=1')!!} --}}
    <script src="https://meet.jit.si/external_api.js"></script>
    {{-- {!! Html::script('js/config_jitsi.js?v=3')!!} --}}
    {{-- @include('myforms.conciliaciones.script') --}}
    <script>
        var request = {
            "conciliacion_id": $("#conciliacion_id").val()
        }
        //  descargarAllPdfConcEstado(request)
        //  getColorTurno($("#audiencia_fecha").val())
    </script>
@endpush
