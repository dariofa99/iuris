@extends('layouts.dashboard')
@push('styles')
<!-- aqui van los estilos de cada vista -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

<style>
            .container-meet {
                /*position: relative;
                border:1px red  solid;*/
                width: 100%;
                height:600px;
                
            }
            .toolbox {
               /* position: absolute;*/
                bottom: 0px;
                border:1px black solid;
                width: 100%;
                height:30px;
            }
            #jitsi-meet-conf-container{
                width: 100%;
                height:570px;
            }
        </style>
@endpush
@section('titulo_area')

            
@endsection
@section('area_forms')

@include('msg.success')
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#menu5">Nuevo formato</a></li>
            <li><a data-toggle="tab" href="#menu6">Actualizar formatos existentes</a></li>
            <li><a data-toggle="tab" href="#menu7">Administrar destinos</a></li>
        </ul>
          
          <div class="tab-content" id="reporte">
            <div id="menu5" class="tab-pane fade in active">
                @include('myforms.conciliaciones.reportes',[
                    'view'=>'store',
                    'mySummernote'=>'summernote_store',
                    'myForm'=>'myFormCreatePdfReporte',
                ])
            </div>
            <div id="menu6" class="tab-pane fade">
                @include('myforms.conciliaciones.reportes',[
                    'view'=>'update',
                    'mySummernote'=>'summernote_update',
                    'myForm'=>'myFormEditPdfReporte',
                ])
            </div>

            <div id="menu7" class="tab-pane fade">
                @include('myforms.conciliaciones.admin_destinos')
            </div>

          </div>
    </div>
</div>
@include('myforms.conciliaciones.componentes.modal_create_categoria')
@stop

@push('scripts')
<!-- aqui van los scripts de cada vista -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>    
<script type="module"   src={{asset("js/admin_conciliacion.js")}}></script>

@endpush 