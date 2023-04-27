@extends('layouts.dashboard')
@push('styles')
<!-- aqui van los estilos de cada vista -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

<style>
       
        </style>
@endpush
@section('titulo_area')
Actualizando formato            
@endsection
@section('area_forms')

@include('msg.success')
<div class="row">
    <div class="col-md-12" id="reporte">          
        @include('myforms.conciliaciones.reportes',[
                'view'=>'update_temp',
                'mySummernote'=>'summernote_update',
                'myForm'=>'myFormEditPdfReporte',
                'conciliacion'=>$conciliacion,
        ])
            
    </div>
</div>
@include('myforms.conciliaciones.componentes.modal_create_categoria')
@stop

@push('scripts')
<!-- aqui van los scripts de cada vista -->
{{-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script> --}}



    
<script>
          
</script>
@endpush