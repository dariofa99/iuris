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
                            @if (currentUser()->active_asignacion || currentUser()->hasRole('docente') || currentUser()->hasRole('docente_prueba'))
                            <input type="hidden" name="search_onlyMy_exp" value="off">    
                            <input type="checkbox" aria-describedby="desChkVerMisCasos"
                                @if(!Request::has('search_onlyMy_exp') 
                                || (Request::has('search_onlyMy_exp') and Request::input('search_onlyMy_exp') != 'off'))
                                checked
                                @endif
                                 name="search_onlyMy_exp"
                                    id="search_onlyMy_exp">
                                <label for="search_onlyMy_exp">Solo listar mis casos asignados</label>
                                {{--  <span id="desChkVerMisCasos" style="font-size: 0px !important;height: 0px;width:0px;position:absolute">
                                        Marcar para ver solo casos asignados
                                    </span> --}}
                            @endif
                        </div>
                        <div class="col-md-12">
                            <input type="checkbox" name="search_onlyProJur" value="search_onlyProJur" id="search_onlyProJur"
                            @if(Request::has('search_onlyProJur'))
                                checked
                                @endif>
                            <label for="search_onlyProJur">
                                Solo listar asuntos marcados como jurídicos
                            </label>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="tipo_busqueda">Busqueda de expedientes</span>
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
                                    <option value="estudiante_num"
                                        {{ Request::has('tipo_busqueda') and (Request::get('tipo_busqueda') == 'estudiante_num' ? 'selected' : '') }}>
                                        Documento de identificación (estudiante)
                                    </option>
                                    <option value="estudiante"
                                        {{ Request::has('tipo_busqueda') and (Request::get('tipo_busqueda') == 'estudiante' ? 'selected' : '') }}>
                                        Nombre o apellidos (estudiante)
                                    </option>

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
                              {{--   <option
                                    {{ Request::has('tipo_busqueda') and (Request::get('tipo_busqueda') == 'fecha_rango' ? 'selected' : '') }}
                                    value="fecha_rango">
                                    Rango Fechas
                                </option> --}}
                                <option
                                    {{ Request::has('tipo_busqueda') and (Request::get('tipo_busqueda') == 'all' ? 'selected' : '') }}
                                    value="all">
                                    Todo (limpiar)
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

                            <input type="date" required id="data_date" name="data" class="form-control form-control-sm"
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
                                <button type="button" id="btn_exp_bus_avz" class="btn btn-sm btn-default"><i
                                        class="fa fa-cogs">
                                    </i> Avanzada </button>
                            @endif
                        </div>
                    </div>

                </form>
            </div>

            <div class="col-md-2" id="content_count_asesorias_inlist">
                  @include('myforms.components_exp.count_asesorias_inlist')
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
    <script>
        async function init() {
            var request = {} //convertFormToJSON('myformExpFilter');
            if ($("#search_onlyMy_exp").is(":checked")) {
                request['search_onlyMy_exp'] = 'search_onlyMy_exp';
            }
            var opselected = $("#myformExpFilter select[name='tipo_busqueda']").val();
            var dataselected = $("#myformExpFilter select[name='data']").val();;
            if (opselected != '' && opselected != null) request['tipo_busqueda'] = opselected;
            if (dataselected != '' && dataselected != null) request['data'] = dataselected;
            $("#wait").show();
            var page = "expedientes";
            let res = await index_page(page, request);
            $("#wait").hide();
        }
        // init();
    </script>
@endpush
