@extends('layouts.front.dashboard')
@push('styles')
<!-- aqui van los estilos de cada vista -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
{!! Html::style('/css/jitsi.css?v=2')!!}

<style>
            .container-meet {
                /*position: relative;
                border:1px red  solid;*/
                width: 100%;
                height:600px;
                margin-bottom: 30px;
                text-align: center;
            }
            .toolbox {
               /* position: absolute;*/
                bottom: 0px;
                /*border:1px black solid;*/
                width: 100%;
                height:50px;
                background-color: rgb(71, 71, 71);
            }
            #jitsi-meet-conf-container{
                width: 100%;
                height:570px;
            }
        </style>
@endpush
@section('titulo_area')
<div class="row">
    <div class="col-md-12">
        Número: <strong>{{$conciliacion->num_conciliacion}}</strong><br>
        <span class="badge bg-{{$conciliacion->estado->color}}"> 
            Estado: {{$conciliacion->estado->ref_nombre}}</span> 
    </div>    
</div>
            
            
@endsection
@section('area_buttons')
<label class="pull-right" >
    
    Fecha radicado:

    @if($conciliacion->fecha_redicado != '0000-00-00') {{$conciliacion->fecha_radicado}} @else --- @endif

</label>



@endsection

@section('area_forms')

@include('msg.success')
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active"><a class="urlactive" data-toggle="tab" href="#home">Información de Solicitud</a></li>
            @if(((currentUser()->hasRole('diradmin') || currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('secretaria') || currentUser()->hasRole('docente') || currentUser()->hasRole('amatai')))
            || ((currentUserInConciliacion($conciliacion->id,['autor']))))
         
         <li><a class="urlactive" data-toggle="tab" href="#menu1">Comentarios</a></li>

         @endif
         @if(((currentUser()->hasRole('diradmin') || currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai')))
         || ((currentUserInConciliacion($conciliacion->id,['autor','solicitante','rep_legal_solicitante','apoderado_solicitante','conciliador','asistente']))))
            {{-- <li><a class="urlactive" data-toggle="tab" href="#menu2">Estado de la solicitud</a></li> --}}
        @endif
            {{-- <li><a class="urlactive" data-toggle="tab" href="#menu3">Asignaciones</a></li> --}}
           @if($audiencia != '') <li><a class="urlactive" data-toggle="tab" href="#menu4">Audiencia</a></li> @endif
            {{-- <li><a class="urlactive" data-toggle="tab" href="#menu5">Notas</a></li> --}}
          </ul>

          <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                @if(((currentUser()->can('act_conciliacion') ))
         || ((currentUserInConciliacion($conciliacion->id,['autor','auxiliar']))))   
         @if ($conciliacion->estado_id == 174 || $conciliacion->estado_id == 194)
              
                <div class="row">
                    <div class="col-md-2">
                        <button id="btn_radicar_conci" class="btn btn-primary btn-sm btn-block">Radicar</button>
                    </div>
                </div>
                @endif    
                @endif
              @include('myforms.conciliaciones.conciliacion_form')
            </div>
            <div id="menu1" class="tab-pane fade">
                @include('myforms.conciliaciones.conciliacion_comentarios')
            </div>
            <div id="menu2" class="tab-pane fade">
                @include('myforms.conciliaciones.conciliacion_estados')
            </div>
            <div id="menu3" class="tab-pane fade">
              @include('myforms.conciliaciones.conciliacion_asignaciones')
          </div>
          <div id="menu4" class="tab-pane fade">
              @include('myforms.conciliaciones.conciliacion_audiencia')
          </div>
          <div id="menu5" class="tab-pane fade">
            @include('myforms.conciliaciones.conciliacion_notas')
          </div>
   
          </div>
    </div>
</div>
    @include('myforms.conciliaciones.componentes.modal_create_hechos_pretenciones')
    @include('myforms.conciliaciones.componentes.modal_reportes_pdf_estados')
    @include('myforms.conciliaciones.componentes.modal_create_document')
    @include('myforms.conciliaciones.componentes.modal_create_estado')
    @include('myforms.conciliaciones.componentes.modal_create_comentario')
    @include('myforms.conciliaciones.componentes.modal_create_user')
    @include('myforms.conciliaciones.componentes.modal_create_estado_pretension')
    @include('myforms.conciliaciones.componentes.modal_detalles_user')
    @include('myforms.conciliaciones.componentes.modal_audiencia_salas_alternas')
    @include('myforms.conciliaciones.componentes.modal_add_notas')
    @include('myforms.conciliaciones.componentes.modal_edit_notas')
    @include('myforms.conciliaciones.componentes.modal_reportes_archivos_compartidos')
@stop

@push('scripts')
<!-- aqui van los scripts de cada vista -->
{!! Html::script('js/audiencia_conciliacion.js?v=1')!!}
<script src="https://meet.jit.si/external_api.js"></script>
{!! Html::script('js/config_jitsi.js?v=3')!!}
@include('myforms.conciliaciones.script')
@endpush