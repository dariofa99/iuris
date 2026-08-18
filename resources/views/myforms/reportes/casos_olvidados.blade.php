@extends('layouts.dashboard')


@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
@endpush

@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('titulo_general')
    @if (currentUser()->hasRole('solicitante'))
        Casos
    @else
    @endif

@endsection

@section('titulo_area')
    @if (currentUser()->hasRole('solicitante'))
        Mis Casos
    @else
        Expedientes
        <small class="text-muted">
            Total registros: {{ count($casos) }}
        </small>
    @endif
@endsection




@section('area_forms')

    <div class="container-fluid">


        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">

                    <!-- Encabezado -->
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <h3 class="mb-1">
                                    <i class="fa fa-folder-open text-primary"></i>
                                    Expedientes
                                </h3>

                                <small class="text-muted">
                                    Consulte y filtre los expedientes registrados
                                </small>
                            </div>

                            <span class="badge badge-primary p-2">
                                Total registros: {{ count($casos) }}
                            </span>

                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="card-body">

                        <form method="GET" action="{{ url()->current() }}">

                            <div class="form-row align-items-end">

                                <!-- Periodo -->
                                <div class="form-group col-md-4">
                                    <label for="periodo">
                                        <i class="fa fa-calendar text-primary"></i>
                                        <strong>Periodo</strong>
                                    </label>
                                    <select name="periodo" id="periodo" class="form-control">
                                        <option value="">
                                            Todos los periodos
                                        </option>
                                        @foreach ($periodos as $periodo)
                                            <option value="{{ $periodo->id }}"
                                                {{ request('periodo') == $periodo->id ? 'selected' : '' }}>
                                                {{ $periodo->prddes_periodo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Número de CC -->
                                <div class="form-group col-md-5">
                                    <label for="documento">
                                        <i class="fa fa-id-card text-primary"></i>
                                        <strong>Nombre del estudiante</strong>
                                    </label>
                                 
                                    <select title="Seleccione un estudiante" data-live-search="true" data-live-search-placeholder="Escriba un valor..." class="form-control form-control-sm selectpicker select_data_users" name="documento" id="select_data_users">

                                        @foreach ($users as $user)
                                            <option value="{{ $user->idnumber }}" {{ request('documento') == $user->idnumber ? 'selected' : '' }}>
                                                {{ $user->full_name }}
                                            </option>
                                        @endforeach
                                    </select>


                                    {{--  <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-search text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="documento" id="documento" class="form-control"
                                            placeholder="Ingrese número de CC" value="{{ request('documento') }}">
                                    </div> --}}
                                </div>
                                <!-- Botones -->
                                <div class="form-group col-md-3">
                                    <div class="btn-block">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i>
                                            Filtrar
                                        </button>
                                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary"
                                            title="Limpiar filtros">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @foreach ($casos as $caso)
            <div class="card shadow mb-4 border-left-primary">

                <div class="card-header bg-white">

                    <div class="row align-items-center">

                        <div class="col-md-8">

                            <h5 class="mb-1">

                                <span class="badge badge-primary">
                                    {{ $caso->expid }}
                                </span>

                                {{ $caso->proceso }}

                            </h5>

                            <small class="text-muted">
                                Asignado el
                                {{ getLongDateWithHour(\Carbon\Carbon::parse($caso->fecha_asig)) }}
                            </small>

                        </div>

                        <div class="col-md-4 text-right">

                            @switch($caso->estado)
                                @case('Abierto')
                                    <span class="badge badge-success p-2">
                                        {{ $caso->estado }}
                                    </span>
                                @break

                                @case('Cerrado')
                                    <span class="badge badge-danger p-2">
                                        {{ $caso->estado }}
                                    </span>
                                @break

                                @default
                                    <span class="badge badge-secondary p-2">
                                        {{ $caso->estado }}
                                    </span>
                            @endswitch

                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <table class="table table-borderless table-sm">

                                <tr>
                                    <th width="170">Solicitante</th>
                                    <td>{{ $caso->usuario_sol }}</td>
                                </tr>

                                <tr>
                                    <th>Estudiante</th>
                                    <td>{{ $caso->estudiante }}</td>
                                </tr>

                                <tr>
                                    <th>Docente</th>
                                    <td>{{ $caso->docente_as }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <a target="_blank" href="{{ route('expedientes.show', $caso->expid) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="fa fa-eye"></i>
                                            Ver expediente
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </div>

                        <div class="col-md-6">

                            <table class="table table-borderless table-sm">

                                <tr>
                                    <th width="170">Última actuación</th>
                                    <td>

                                        @if ($caso->fecha_ultima_actuacion)
                                            {{ \Carbon\Carbon::parse($caso->fecha_ultima_actuacion)->diffForHumans() }}
                                            <small>
                                                ({{ \Carbon\Carbon::parse($caso->fecha_ultima_actuacion)->format('d-m-Y H:i') }})
                                            </small>
                                        @else
                                            <span class="text-danger">
                                                Sin actuaciones
                                            </span>
                                        @endif

                                    </td>

                                </tr>

                                <tr>

                                    <th>Última redacción</th>

                                    <td>

                                        @if ($caso->fecha_redaccion)
                                            {{ \Carbon\Carbon::parse($caso->fecha_redaccion)->diffForHumans() }}
                                        @else
                                            <span class="text-warning">
                                                Sin redacción
                                            </span>
                                        @endif

                                    </td>

                                </tr>

                            </table>

                        </div>

                    </div>

                    <hr>

                    <h6 class="font-weight-bold text-primary">
                        Hechos
                    </h6>

                    <p class="text-justify">

                        {{ $caso->exphechos ?: 'No existen hechos registrados para este expediente.' }}

                    </p>

                    @if ($caso->exprtaest)
                        <hr>

                        <h6 class="font-weight-bold text-success">
                            Respuesta del estudiante
                        </h6>

                        <p class="text-justify">

                            {{ $caso->exprtaest }}

                        </p>
                    @endif

                </div>

                <div class="card-footer bg-light">

                    <div class="row">

                        <div class="col-md-8">

                            <small class="text-muted">

                                Expediente:
                                <strong>{{ $caso->expid }}</strong>

                            </small>

                        </div>
                        {{-- 
                        <div class="col-md-4 text-right">

                            <a target="_blank" href="{{ route('expedientes.show', $caso->expid) }}"
                                class="btn btn-primary btn-sm">

                                <i class="fa fa-eye"></i>
                                Ver expediente

                            </a>

                        </div> --}}

                    </div>

                </div>

            </div>
        @endforeach

        {{ $casos->appends(request()->query())->links() }}
    </div>



@stop
@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <!-- Latest compiled and minified JavaScript -->
     <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>
@endpush
