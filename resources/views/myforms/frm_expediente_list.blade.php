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
        Expedientes
    @endif

@endsection

@section('titulo_area')
    @if (currentUser()->hasRole('solicitante'))
        Mis Casos
    @else
        Listar
    @endif
@endsection




@section('area_forms')

    @include('msg.alerts')
    <input type="hidden" value="{{ $estados }}" id="ref_estados">
    <input type="hidden" value="{{ $tipo_proceso }}" id="ref_tipoproceso">
    <input type="hidden" value="{{ $reframa_derecho }}" id="ref_ramaderecho">

    <div class="cd">
        <div class="row">
            <div class="col-md-10">
                <form action="{{ route('expedientes.index') }}" method="GET" id="myformExpFilter">
                    <div class="row">
                        <div class="col-md-12">
                            @if (currentUser()->hasRole('docente') || currentUser()->hasRole('docente_prueba'))
                                <input type="checkbox" @if ((isset($request['search_onlyMy_exp']) and $request['search_onlyMy_exp'] == 'on') || empty($request)) checked @endif
                                    name="search_onlyMy_exp" id="search_onlyMy_exp">
                                Solo ver mis casos
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <span>Busqueda</span>
                            <select name="tipo_busqueda" id='tipo_busqueda' class="form-control form-control-sm"
                                placeholder="Seleccione..." required="required">
                                <option value="">Seleccione...</option>
                                @if (currentUser()->hasRole('diradmin') or currentUser()->hasRole('dirgral') or currentUser()->hasRole('amatai'))
                                    <option
                                        {{ Request::has('tipo_busqueda') and (Request::get('tipo_busqueda') == 'idnumber_doc' ? 'selected' : '') }}
                                        value="idnumber_doc">
                                        Casos por docente
                                    </option>
                                @endif
                                <option
                                    {{ Request::has('tipo_busqueda') and (Request::get('tipo_busqueda') == 'codido_exp' ? 'selected' : '') }}
                                    value="codido_exp">
                                    Número de Expediente
                                </option>

                                @if (!currentUser()->hasRole('estudiante'))
                                    <option value="estudiante"
                                        {{ Request::has('tipo_busqueda') and (Request::get('tipo_busqueda') == 'estudiante' ? 'selected' : '') }}>
                                        Nombre o apellidos (estudiante)
                                    </option>
                                @endif
                                @if (!currentUser()->hasRole('estudiante'))
                                    <option value="solicitante_num"
                                        {{ Request::has('tipo_busqueda') and (Request::get('tipo_busqueda') == 'solicitante_num' ? 'selected' : '') }}>
                                        Documento de identificación (solicitante)
                                    </option>
                                @endif
                                <option
                                    {{ Request::has('tipo_busqueda') and (Request::get('tipo_busqueda') == 'solicitante' ? 'selected' : '') }}
                                    value="solicitante">
                                    Nombre o apellidos (solicitante)</option>
                                <option
                                    {{ Request::has('tipo_busqueda') and (Request::get('tipo_busqueda') == 'estado' ? 'selected' : '') }}
                                    value="estado">
                                    Estado
                                </option>
                                <option
                                    {{ Request::has('tipo_busqueda') and (Request::get('tipo_busqueda') == 'tipo_consulta' ? 'selected' : '') }}
                                    value="tipo_consulta">Tipo de Consulta</option>
                                <option
                                    {{ Request::has('tipo_busqueda') and (Request::get('tipo_busqueda') == 'rama_derecho' ? 'selected' : '') }}
                                    value="rama_derecho">Rama del Derecho</option>
                                <option
                                    {{ Request::has('tipo_busqueda') and (Request::get('tipo_busqueda') == 'fecha_creacion' ? 'selected' : '') }}
                                    value="fecha_creacion">
                                    Fecha de Creación
                                </option>
                                <option
                                    {{ Request::has('tipo_busqueda') and (Request::get('tipo_busqueda') == 'fecha_rango' ? 'selected' : '') }}
                                    value="fecha_rango">
                                    Rango Fechas
                                </option>
                                <option
                                    {{ Request::has('tipo_busqueda') and (Request::get('tipo_busqueda') == 'all' ? 'selected' : '') }}
                                    value="all">
                                    Todo
                                </option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <span>Ingrese un valor</span>
                            {!! Form::select('data', [], null, [
                                'class' => 'form-control form-control-sm selectpicker select_data_users',
                                'data-live-search' => 'true',
                                'required' => 'required',
                                'id' => 'select_data_users',
                                'data-width' => '100%',
                                'title' => 'Esperando tipo de busqueda',
                                'data-live-search-placeholder' => 'Escriba un valor...',
                            ]) !!}

                            <input type="date" id="data" name="data" class="form-control form-control-sm"
                                style="display: none">


                            <table width="100%" style="display: none;width:100% !important">
                                <tr>
                                    <td>
                                        <span>Fecha inicio</span>
                                        <input type="date" name="dataIni" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <span>Fecha fin</span>
                                        <input type="date" name="dataFin" class="form-control form-control-sm">
                                    </td>
                                </tr>
                            </table>



                        </div>
                        <div class="col-md-4">
                            <br>
                            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-search"> </i> Buscar
                            </button>
                            @if (currentUser()->hasRole('diradmin') ||
                                    currentUser()->hasRole('dirgral') ||
                                    currentUser()->hasRole('coordprac') ||
                                    currentUser()->hasRole('amatai'))
                                <button type="button" id="btn_exp_bus_avz" class="btn btn-sm btn-default"><i class="fa fa-cogs">
                                    </i> Avanzada </button>
                            @endif
                        </div>
                    </div>

                </form>
            </div>

            <div class="col-md-2">
                <table style="text-align:right;width:100%;font-size:14px;">
                    <tr>
                        <td>

                            <label>No de Expedientes <span class="badge bg-blue" id="badgeCount">
                                    {{ number_format($numEx, 0, '.', '.') }} </span></label>

                        </td>
                    </tr>
                    <tr>
                        <td>

                            @if (count($count_colors) > 0 and $count_colors != '')
                                <div>
                                    <label>Asesorías asignadas</label><br>
                                    <span class="badge btn_search_color" id="green"
                                        style="border:1px solid #2ECC71">{{ $count_colors[0]->verde === null ? 0 : $count_colors[0]->verde }}</span>
                                    <span class="badge btn_search_color" id="amarillo"
                                        style="border:1px solid #F4D03F">{{ $count_colors[0]->amarillo === null ? 0 : $count_colors[0]->amarillo }}</span>
                                    <span class="badge btn_search_color" id="rojo"
                                        style="border:1px solid #CB4335">{{ $count_colors[0]->rojo === null ? 0 : $count_colors[0]->rojo }}</span>
                                    <span class="badge btn_search_color" id="gris"
                                        style="border:1px solid gray">{{ $count_colors[0]->gris === null ? 0 : $count_colors[0]->gris }}</span>
                                </div>
                            @endif
                        </td>
                    </tr>

                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="table_list_model">

                    @include('myforms.frm_expediente_list_ajax')

                </div>
            </div>
        </div>
    </div>



    @include('myforms.frm_modal_buscar_exp_avanzada')
@stop
@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>

    <script type="module" src={{ asset('js/admin_expedientes.js') }}></script>
@endpush
