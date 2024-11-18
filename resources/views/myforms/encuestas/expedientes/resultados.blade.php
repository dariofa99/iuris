@extends('layouts.dashboard')
@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/jquery-ui/jquery-ui.min.css') }}">

    @endpush

@section('titulo_area')
Administración de encuestas
<h3>
   Consultorios Jurídicos
</h3>
@endsection 
@section('navbar')
    @include('content.navbar')
@endsection
@section('area_forms')

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link urlactive active" id="edit_form-tab" data-toggle="tab" href="#edit_form_tab" role="tab"
                aria-controls="edit_form_tab" aria-selected="false">
                Administrar formularios
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link urlactive" id="general-tab" data-toggle="tab" href="#general_tab" role="tab"
                aria-controls="general_tab" aria-selected="true">
                Resultados
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link urlactive" id="individual-tab" data-toggle="tab" href="#individual_tab" role="tab"
                aria-controls="individual_tab" aria-selected="false">
                Resultados Individual
            </a>
        </li>

       
    </ul>

    <div class="tab-content" id="myTabContent" style="margin-top: 10px !important">

        <div class="tab-pane fade " id="general_tab" role="tabpanel" aria-labelledby="general-tab">
            <div class="row">
                <div class="col-md-5">
                    <label for="select_table">Tabla Principal</label>
                    <select class="form-control form-control-sm generate_graf" id="select_table" name="select_table">
                        <option value="" selected="selected">Seleccione...</option>
                        <option value="expedientes">Expedientes</option>
                        <option value="conciliaciones">Conciliaciones</option>
                    </select>


                </div>
                <div class="col-md-1"><br>
                    <label for="">Hab. Rango</label>
                    <input type="checkbox" id="check_hab_rango" class="generate_graf">
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="fecha_ini">Fecha Inicial</label>
                            <input class="form-control form-control-sm" id="fecha_ini" disabled="" name="fecha_ini"
                                type="date" value="2024-06-05">
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_fin">Fecha Final</label>

                            <input class="form-control form-control-sm generate_graf" id="fecha_fin" disabled=""
                                name="fecha_fin" type="date" value="2024-06-12">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="content-grafs">
 
            </div>
        </div>

        <div class="tab-pane fade" id="individual_tab" role="tabpanel" aria-labelledby="individual-tab">

            <div class="row justify-content-center">
                <div class="col-md-6 list_encuind" id="list_encuind">
                    @include('myforms.encuestas.expedientes.resultados_individual_ajax')
                </div>
            </div>

        </div>

        <div class="tab-pane fade active show" id="edit_form_tab" role="tabpanel" aria-labelledby="edit_form-tab">

            @include('myforms.encuestas.expedientes.editar_form')
              

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
    {!! Html::script('plugins/jquery-ui/jquery-ui.min.js') !!}
    <script type="module" src={{ asset('js/admin_categorias.js') }}></script>
  
    <script type="module" src={{ asset('js/graficos_encuestas_exp.js') }}></script>

    </script>
@endpush
