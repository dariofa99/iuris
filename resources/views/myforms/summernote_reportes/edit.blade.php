@extends('layouts.dashboard')
@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link href="{{ asset('/plugins/summernote-0.8/summernote-bs4.min.css') }}" rel="stylesheet">

    <style>
        .container-meet {
            /*position: relative;
                                border:1px red  solid;*/
            width: 100%;
            height: 600px;

        }
 
        .toolbox {
            /* position: absolute;*/
            bottom: 0px;
            border: 1px black solid;
            width: 100%;
            height: 30px;
        }

        #jitsi-meet-conf-container {
            width: 100%;
            height: 570px;
        }
    </style>
@endpush
@section('titulo_area')


@endsection
@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection
@section('area_forms')

    @include('msg.success')
{{-- {{dd(strtolower(str_replace(" ","_",getReferencesDataBySection(
    'elementos_juridicos',
    'conciliaciones')[4]->name)))}} --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active urlactive" id="custom-tabs-four-home-tab" data-toggle="pill"
                                href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home"
                                aria-selected="true">Nuevo formato</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link urlactive" id="custom-tabs-four-profile-tab" data-toggle="pill"
                                href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile"
                                aria-selected="false">Actualizar formatos existentes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link urlactive" id="custom-tabs-four-messages-tab" data-toggle="pill"
                                href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages"
                                aria-selected="false">Administrar destinos</a>
                        </li>

                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel"
                            aria-labelledby="custom-tabs-four-home-tab">
                        @include('myforms.summernote_reportes.componentes.reportes', [
                                'view' => 'store',
                                'mySummernote' => 'summernote_store',
                                'myForm' => 'myFormCreatePdfReporte',
                            ]) 
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                            aria-labelledby="custom-tabs-four-profile-tab">
                            @include('myforms.summernote_reportes.componentes.reportes', [
                                'view' => 'update',
                                'mySummernote' => 'summernote_update',
                                'myForm' => 'myFormEditPdfReporte',
                            ])
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel"
                            aria-labelledby="custom-tabs-four-messages-tab">
                            @include('myforms.summernote_reportes.componentes.admin_destinos')
                        </div>

                    </div>
                </div>
                <!-- /.card -->
            </div> 
        </div>
    </div>

    @include('myforms.conciliaciones.componentes.modal_create_categoria')
@stop

@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <script src="{{ asset('plugins/summernote-0.8/summernote.min.js') }}"></script>
    <script type="module" src={{ asset('js/admin_reportespdf.js') }}></script>
    <script>
        $(document).ready(function() {
            set_tab();
            var summernote = $(".summernote");           
            items_delete = [];
          /*   summernote.summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    //['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ],
                height: 527,        
            }); */
        });
    </script>
@endpush
