@extends('layouts.dashboard')


@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
    <style>
        /* =====================================
           ESTILOS MODERNOS PARA FILTROS
           ===================================== */

        .filter-section {
            background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
           
            border-left: 5px solid #27ae60;
        }

      

        .filter-title {
            font-weight: 700;
            color: #1a4d2e;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-row {
            display: flex;
            gap: 1.5rem;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            display: block;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.6rem;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .filter-group label i {
            margin-right: 6px;
            color: #27ae60;
        }

        .filter-select,
        .filter-input {
            width: 100%;
            height: 45px;
            border: 2px solid #e8f5e9;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #2c3e50;
            background: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.08);
        }

        .filter-select::placeholder,
        .filter-input::placeholder {
            color: #a0a9b8;
        }

        .filter-select:focus,
        .filter-input:focus {
            border-color: #27ae60;
            box-shadow: 0 0 0 4px rgba(39, 174, 96, 0.1), 0 4px 16px rgba(39, 174, 96, 0.15);
            outline: none;
            background: #f0f9ff;
        }

        .filter-select option {
            padding: 10px;
            background: white;
            color: #2c3e50;
        }

        .filter-input-wrapper {
            position: relative;
        }

        .filter-input-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #27ae60;
            pointer-events: none;
            font-size: 1rem;
        }

        .btn-search-filter {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 28px;
            height: 45px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .btn-search-filter::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-search-filter:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-search-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.4);
            background: linear-gradient(135deg, #229954 0%, #1e8449 100%);
        }

        .btn-search-filter:active {
            transform: translateY(0);
        }

        .btn-search-filter i {
            position: relative;
            z-index: 1;
            font-size: 1.1rem;
        }

        .btn-search-filter span {
            position: relative;
            z-index: 1;
        }

        .btn-notify-section {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .btn-notify-modern {
            background: linear-gradient(135deg, #ff9500 0%, #fb8c00 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 15px;
            padding: 12px 28px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(255, 149, 0, 0.3);
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-notify-modern::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-notify-modern:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-notify-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 149, 0, 0.4);
            background: linear-gradient(135deg, #fb8c00 0%, #f57c00 100%);
        }

        .btn-notify-modern:active {
            transform: translateY(0);
        }

        .btn-notify-modern i {
            position: relative;
            z-index: 1;
            font-size: 1.2rem;
        }

        .btn-notify-modern span {
            position: relative;
            z-index: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-row {
                gap: 1rem;
            }

            .filter-group {
                min-width: 100%;
            }

            .filter-select,
            .filter-input,
            .btn-search-filter {
                font-size: 13px;
                height: 42px;
                padding: 8px 12px;
            }

            .filter-section {
                padding: 1.5rem;
            }

            .btn-search-filter {
                padding: 8px 16px;
            }
        }

        /* Animación */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .filter-section {
            animation: slideDown 0.5s ease-out;
        }
    </style>
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
