@extends('layouts.dashboard')
@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/fullcalendar/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
    <style>
        table {
            border: 1px solid #ddd;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 16px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        caption {
            caption-side: top; /* Asegura que el caption esté arriba */
            font-weight: bold;
            text-align: left;
            margin-bottom: 10px; /* Espacio opcional */
        }

    </style>
@endpush
@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection
@section('titulo_area')
    Agenda <a href="#" id="consultar_citas_dia">Consultar citas del día</a>
@endsection
@section('area_buttons')

@endsection

@section('area_forms')


    <div class="row">
        <div class="col-md-12">
            <div id="calendar"></div>
        </div>
    </div>

    @include('myforms.components_exp.frm_modal_citaciones_agenda_estudiante')
    @include('myforms.components_exp.frm_modal_lista_citas_dia')
@stop
@push('styles')
    <style>
        /* Estilo personalizado */
        .event-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .event-card:hover {
            transform: scale(1.02);
        }

        .event-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #000000;
        }

        .event-motivo {
            font-size: 1.1rem;
            color: #6c757d;
        }

        .event-date {
            font-size: 1rem;
            font-weight: 500;
            color: #000000;
        }
    </style>
@endpush

@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <!-- Latest compiled and minified JavaScript -->
    {!! Html::script('plugins/fullcalendar/fullcalendar.min.js') !!}
    {!! Html::script('plugins/fullcalendar/dist/locale/es.js') !!}
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>

    <script type="module" src={{ asset('js/admin_cal_cita_est.js') }}></script>
@endpush
