@extends('layouts.dashboard')
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

    @if($conciliacion->fecha_redicado != '0000-00-00') {{$conciliacion->fecha_radicado}}
   
    
    @else --- @endif

</label>



@endsection

@section('area_forms')

@include('msg.success')

 
 
{{-- {{dd(currentUser()->conciliaciones()
->where(['conciliacion_id'=>$conciliacion->id,'tipo_usuario_id'=>203])->get())}} --}} 
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            @if(currentUser()->can('ver_form_conciliacion') 
            || (currentUserInConciliacion($conciliacion->id,['conciliador','asistente','autor'])))
            <li class="active">
                <a class="urlactive" data-toggle="tab" href="#home">Información de Solicitud</a>
            
            </li>
            @endif
            
            @if( currentUser()->can('ver_documentos_conciliacion') ||
             (currentUserInConciliacion($conciliacion->id,['conciliador','asistente'])
            and $conciliacion->getUser(203)->pivot and $conciliacion->getUser(203)->pivot->user_id == auth()->user()->id and 
            $conciliacion->getUser(203)->pivot->estado_id == 230
            ))

            <li>
                <a class="urlactive" data-toggle="tab" href="#documentos">Documentos </a>
            </li>
            @endif
            
            @if((currentUser()->can('ver_comentarios_conciliacion'))
            || ((currentUserInConciliacion($conciliacion->id,['autor'])))
            || ($conciliacion->getUser(203)->pivot and $conciliacion->getUser(203)->pivot->user_id == auth()->user()->id 
            and $conciliacion->getUser(203)->pivot->estado_id == 230)
            )
         
         <li>
            <a class="urlactive" data-toggle="tab" href="#menu1">Notificaciones</a>
        </li>

         @endif
        
         @if(((currentUser()->can('ver_estados_conciliacion')))
         || ((currentUserInConciliacion($conciliacion->id,['conciliador','auxiliar']) and (
            ($conciliacion->getUser(203)->pivot and $conciliacion->getUser(203)->pivot->user_id == auth()->user()->id
             and $conciliacion->getUser(203)->pivot->estado_id == 230)))) 
         || ($conciliacion->getUser(199)->pivot and $conciliacion->getUser(199)->hasRole('estudiante') and currentUserInConciliacion($conciliacion->id,['autor']))
         )
            <li><a class="urlactive" data-toggle="tab" href="#menu2">Estado de la solicitud</a></li>
        @endif
        
{{-- 
        <li>
            <a class="urlactive" data-toggle="tab" href="#menu7">Actas</a>
        </li> --}}

        @if((currentUser()->can('ver_asignaciones_conciliacion'))
        || ((currentUserInConciliacion($conciliacion->id,['conciliador','auxiliar']) 
        and (( $conciliacion->getUser(203)->pivot and $conciliacion->getUser(203)->pivot->user_id == auth()->user()->id
             and $conciliacion->getUser(203)->pivot->estado_id == 230)))))
       
            <li><a class="urlactive" data-toggle="tab" href="#menu3">Asignaciones</a></li>
            @endif

            @if($audiencia!='' || (currentUserInConciliacion($conciliacion->id,['conciliador','auxiliar']) and ( 
            ($conciliacion->getUser(203)->pivot and $conciliacion->getUser(203)->pivot->user_id == auth()->user()->id
             and $conciliacion->getUser(203)->pivot->estado_id == 230)))
            || currentUser()->can('ver_audiencia_conciliacion'))

            <li><a class="urlactive" data-toggle="tab" href="#menu4">Audiencia</a></li>
            @endif

            @if((currentUser()->can('act_conciliacion'))
            || ($conciliacion->getUser(199)->pivot and $conciliacion->getUser(199)->hasRole('estudiante') and
             currentUserInConciliacion($conciliacion->id,['conciliador','auxiliar'])
             and currentUser()->conciliaciones()->where([
                'conciliacion_id'=>$conciliacion->id
             ])->first()->pivot->estado_id != 231
             ))
           {{--  <li>
                <a class="urlactive" data-toggle="tab" href="#menu5">Notas</a>
            </li> --}}
            @endif
            @if(((currentUser()->can('act_conciliacion')))
            || ($conciliacion->getUser(199)->pivot and
             $conciliacion->getUser(199)->hasRole('estudiante') and
             (currentUserInConciliacion($conciliacion->id,['conciliador','auxiliar']))))
             @if(count($conciliacion->expedientes)<=0) 
              {{-- <li><a class="urlactive" data-toggle="tab" href="#menu6">Expediente</a></li> --}}
              @endif
           @endif

       

          </ul>

          <div class="tab-content">
            <div id="documentos" class="tab-pane fade">
                @include('myforms.conciliaciones.documentos')
              </div>

            <div id="home" class="tab-pane fade in active">
              @include('myforms.conciliaciones.conciliacion_form') 
             {{--  @include('myforms.conciliaciones.conciliacion_informacion') --}}

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
          <div id="menu6" class="tab-pane fade">
            @include('myforms.conciliaciones.conciliacion_expediente')
          </div>

          <div id="menu7" class="tab-pane fade">
            @include('myforms.conciliaciones.formatos_actas')
          </div>

          </div>
    </div>
</div>
    @include('myforms.conciliaciones.componentes.modal_create_hechos_pretenciones')
    @include('myforms.conciliaciones.componentes.modal_reportes_pdf_estados')
    @include('myforms.conciliaciones.componentes.modal_create_document')
    {{-- @include('myforms.conciliaciones.componentes.modal_create_estado') --}}
    @include('myforms.conciliaciones.componentes.modal_create_comentario')
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
<!-- aqui van los scripts de cada vista -->
<script type="module"   src={{asset("js/admin_conciliacion.js")}}></script>

{!! Html::script('js/audiencia_conciliacion.js?v=1')!!}
<script src="https://meet.jit.si/external_api.js"></script>
{!! Html::script('js/config_jitsi.js?v=3')!!}
@include('myforms.conciliaciones.script')
<script>
     var request = 
        {
            "conciliacion_id":$("#conciliacion_id").val()
        }
     descargarAllPdfConcEstado(request)
     getColorTurno($("#audiencia_fecha").val())
</script>
@endpush