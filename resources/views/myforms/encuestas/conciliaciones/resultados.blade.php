@extends('layouts.dashboard')
@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
@endpush

@section('titulo_area')
    Resultados
@endsection
@section('navbar')
    @include('content.navbar')
@endsection
@section('area_forms')

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link urlactive active" id="general-tab" data-toggle="tab" href="#general_tab" role="tab"
                aria-controls="general_tab" aria-selected="true">
                Administrar formularios
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link urlactive" id="individual-tab" data-toggle="tab" href="#individual_tab" role="tab"
                aria-controls="individual_tab" aria-selected="false">
                Resultados gráficos
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link urlactive" id="edit_form-tab" data-toggle="tab" href="#edit_form_tab" role="tab"
                aria-controls="edit_form_tab" aria-selected="false">
                Resultados individuales
            </a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent" style="margin-top: 10px !important">

        <div class="tab-pane fade active show" id="general_tab" role="tabpanel" aria-labelledby="general-tab">
            <div class="row">
              
            </div>
            <div class="row" id="content-grafs">

            </div>
        </div>

        <div class="tab-pane fade" id="individual_tab" role="tabpanel" aria-labelledby="individual-tab">

            <div class="row justify-content-center">
                <div class="col-md-6 list_encuind" id="list_encuind">
                    @include('myforms.encuestas.conciliaciones.resultados_individual_ajax')
                </div>
            </div>

        </div>

        <div class="tab-pane fade" id="edit_form_tab" role="tabpanel" aria-labelledby="edit_form-tab">

            @include('myforms.encuestas.conciliaciones.editar_form')
              

        </div>
    </div>

    @include('myforms.categorias.partials.modals.create')

    @include('myforms.encuestas.expedientes.frm_modal_create_encuesta')

    @include('myforms.encuestas.preguntas.frm_modal_add_pregunta_encuesta')






@stop
@push('scripts')
    {{--  <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>
    <script type="module" src={{ asset('js/admin_conciliacion.js') }}></script>
    <script>
        $(document).ready(function() {
            $(".selectpicker").selectpicker()
        }); --}}
    {!! Html::script('plugins/amcharts/amcharts.js') !!}
    {!! Html::script('plugins/amcharts/serial.js') !!}
    {!! Html::script('plugins/amcharts/pie.js') !!}
    <script type="module" src={{ asset('js/admin_encuestas.js') }}></script>
    <script type="module" src={{ asset('js/admin_categorias.js') }}></script>
  
    <script type="module" src={{ asset('js/graficos_encuestas_conc.js') }}></script>

    </script>
@endpush
