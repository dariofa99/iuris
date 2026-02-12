@extends('layouts.dashboard')


@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
  
@endpush

@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('titulo_area')

        
  
@endsection


@section('area_buttons')

    <div class="filter-section">
       
        
        <div class="filter-row">
            {{-- Filtro tipo --}}
            <div class="filter-group">
                <label for="filter">
                    
                    Filtrar por:
                </label>
                <select name="filter" id="filter" class="filter-select">
                    <option value="">-- Seleccionar filtro --</option>
                    <option value="status" {{ (Request::get('filter')) == 'status' ? 'selected' : '' }}>
                        📊 Estado
                    </option>
                    <option value="cedula" {{ (Request::get('filter')) == 'cedula' ? 'selected' : '' }}>
                        🆔 Cédula
                    </option>
                    <option value="expediente" {{ (Request::get('filter')) == 'expediente' ? 'selected' : '' }}>
                        📋 Expediente
                    </option>
                </select>
            </div>

            {{-- Valor del filtro - Select para Estado --}}
            <div class="filter-group" id="filter-value-group">
                <label for="filter_option">
                    <i class="fas fa-check-circle"></i>
                    Seleccione un valor:
                </label>
                <div class="filter-input-wrapper">
                    <select name="filter_value" id="filter_option" class="filter-select filter_input" style="display: {{(Request::get('filter')) == 'status' ? 'block' : 'none'}}">
                        @foreach($estados_incidencia as $estado)
                            <option value="{{ $estado->id }}" {{ Request::get('filter_value') == $estado->id ? 'selected' : '' }}>
                                {{ $estado->ref_nombre }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text" 
                        style="{{ (Request::get('filter')) == 'status' ? 'display: none' : 'block' }}" 
                        value="{{ Request::get('filter')!= 'status' ? Request::get('filter_value') : '' }}" 
                        name="filter_value" 
                        id="filter_value" 
                        class="filter-input filter_input" 
                        placeholder="Ingrese el valor a filtrar">
                </div>
            </div>

            {{-- Botón de búsqueda --}}
            <div class="filter-group" style="flex: 0 1 auto;">
                <button class="btn-search-filter" id="btn_buscar_incidencia" type="button">
                    <i class="fas fa-search"></i>
                    <span>Buscar</span>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('area_forms')
    <div class="btn-notify-section">
        <button id="btn_notificar_incidente" type="button" class="btn-notify-modern">
            <i class="fas fa-bolt"></i>
            <span>Notificar Incidencia / Cambio</span>
        </button>
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
