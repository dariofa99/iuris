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

        Incidencias
  
@endsection


@section('area_buttons')

    <div class="row">
        <div class="col-md-4">
            <select name="filter" id="filter" class="form-control form-control-sm" >
                <option value="" {{ Request::has('filter') and Request::get('filter') == '' ? 'selected' : '' }}>
                    Filtrar por...
                </option>
                <option value="status" {{ (Request::get('filter')) == 'status' ? 'selected' : '' }} >
                    Estado
                </option>
                <option value="cedula" {{ (Request::get('filter')) == 'cedula' ? 'selected' : '' }} >
                    Cédula
                </option>
                <option value="expediente" {{ (Request::get('filter')) == 'expediente' ? 'selected' : '' }}>
                    Expediente
                </option>
            </select>

        </div>

        <div class="col-md-4">
            
             
            <select name="filter_value" id="filter_option" class="form-control filter_input form-control-sm" style="display: {{(Request::get('filter')) == 'status' ? 'block' : 'none'}}">
                @foreach($estados_incidencia as $estado)
                    <option value="{{ $estado->id }}" {{ Request::get('filter_value') == $estado->id ? 'selected' : '' }}>
                        {{ $estado->ref_nombre }}
                    </option>
                @endforeach
            </select>

            <input type="text" style="{{ (Request::get('filter')) == 'status' ? 'display: none' : 'block' }}" value="{{ Request::get('filter')!= 'status' ? Request::get('filter_value') : '' }}" name="filter_value" id="filter_value" class="form-control filter_input form-control-sm" placeholder="Ingrese el valor a filtrar">


        </div>

         <div class="col-md-1">
            
             
            <button class="btn btn-sm btn-success" id="btn_buscar_incidencia">
                <i class="fas fa-search"></i> Buscar
            </button>

        </div>

    </div>
@endsection

@section('area_forms')
    <div class="row">
        <div class="col-md-4">
            <button id="btn_notificar_incidente" type="button" class="btn btn-primary"
                style="                   
                    color: white;
                    font-weight: 600;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    font-size: 15px;
                    transition: all 0->2s ease-in-out;
                    box-shadow: 0 4px 8px rgb(214, 214, 214);
                "
                onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                ⚡ Notificar incidencia / cambio
            </button>


        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12 table-responsive no-padding" id="tbl_incidencias">
            @include('myforms.incidencias.admin_ajax')
        </div>
    </div>

    @include('myforms.components_exp.frm_modal_notificar_incidencia', [
        'categorias_incidencia' => $categorias_incidencia_system,
    ])
    @include('myforms.components_exp.frm_modal_actualizar_incidencia')
@endsection

@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <!-- Latest compiled and minified JavaScript -->
    <script type="module" src={{ asset('js/admin_incidencias.js?v=' . config('app_config.asset_version')) }}></script>
@endpush
