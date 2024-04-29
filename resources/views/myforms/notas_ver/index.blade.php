@extends('layouts.dashboard')

@section('titulo_general')
    Notas

@endsection

@section('titulo_area')
    <h3>
        @if (isset($user) and $user != null)
            {{ $user->name }} {{ $user->lastname }}
        @endif
    </h3>
@endsection
@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection


@section('area_forms')

    @include('msg.alerts')

    <form action="/notas/ver/estudiante" method="GET" id="myFormBuscarNotas">
        @if (auth()->user()->can('ver_notas_estudiante'))
            <div class="row">

                <div class="col-md-4">

                    <input placeholder="Ingrese un número de documento" required type="text"
                        value="{{ Request::get('idnumber') }}" class="form-control form-control-sm" name="idnumber">
                </div>

            </div>
        @else
            @if (Request::has('idnumber'))
                <input required type="hidden" value="{{ Request::get('idnumber') }}" class="form-control form-control-sm"
                    name="idnumber">
            @endif
        @endif
        <div class="row">
            <div class="col-md-2">
                Origen:
                <select class="form-control" name="origen">
                    <option @if (Request::has('origen') and Request::get('origen') == 'expedientes') selected @endif value="expedientes">Expedientes</option>
                    <option @if (Request::has('origen') and Request::get('origen') == 'conciliaciones') selected @endif value="conciliaciones">Conciliaciones</option>

                </select>
            </div>
            <div class="col-md-2">
                Periodo <small>Activo</small>:
                <select class="form-control" name="perid">
                    @foreach ($periodos as $key => $periodo)
                        <option @if (Request::has('perid') and Request::get('perid') == $periodo->id) selected @endif value="{{ $periodo->id }}">
                            {{ $periodo->prddes_periodo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                Corte:

                <select class="form-control" name="segid">
                    @foreach ($segmentos as $key => $segmento)
                        <option @if (
                            (Request::has('segid') and Request::get('segid') == $segmento->id) ||
                                ($segmentoAct->id == $segmento->id and !Request::has('segid'))) selected @endif value="{{ $segmento->id }}">
                            {{ $segmento->segnombre }}</option>
                    @endforeach
                    <option value="">Ver todos</option>
                </select>

            </div>

            <div class="col-md-2">
                <br>
                <button class="btn btn-success btn-block btn-sm" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-md-12 table-responsive no-padding">

            <table class="table">
                <thead>
                    <th style="width: 5px">
                        No.
                    </th>
                    <th width="1%">
                        Expediente
                    </th>

                    <th width="1%">
                        Periodo
                    </th>
                    <th width="2%">
                        Corte
                    </th>

                </thead>

                <tbody>
                    @php
                        $count = 1;
                        $promedio_c = 0;
                        $promedio_a = 0;
                        $promedio_e = 0;
                        $contador_c = 0;
                        $contador_a = 0;
                        $contador_e = 0;

                    @endphp
                    @forelse($notas as $key => $data)
                        <tr style="border-bottom: 2px solid black">
                            <td style="width: 0%">
                                {{ $count }}
                                @php
                                    $count = $count + 1;
                                @endphp
                            </td>
                           
                                <td>


                                    <a target="_blank" href="/expedientes/{{ $data[0]['expediente'] }}/edit">
                                        {{ $data[0]['expediente'] }}
                                    </a>
                                    @if (currentUser()->hasRole('amatai')
                                    || currentUser()->can("eliminar_notas"))
                                    <button id="btn_eliminar_notas_ver-{{$key}}" class="btn btn-danger btn_eliminar_notas_ver" data-id="{{ $key }}"
                                        id="btn-elimin-{{ $key }}">
                                        Eliminar
                                    </button>

                                    <button id="btn_delete_notas_ver-{{$key}}" style="display: none" class="btn btn-success mt-1 btn_delete_notas_ver"
                                        data-id="{{ $key }}" id="btn-elimin-{{ $key }}">
                                        Confirmar
                                    </button>

                                    <button id="btn_cancel_notas_ver-{{$key}}" style="display: none" class="btn btn-default btn_cancel_notas_ver mt-1"
                                        data-id="{{ $key }}" id="btn-elimin-{{ $key }}">
                                        Cancelar
                                    </button>
                                    @endif
                                </td>
                          
                            <td>
                                {{ $data[0]['periodo'] }}
                            </td>
                            <td>
                                {{ $data[0]['segmento'] }}
                            </td>

                            <td width="50%">
                                <table class="table">
                                    <thead>
                                        <th></th>

                                        <th width="13%">
                                            Concepto Nota
                                        </th>

                                        <th>
                                            Nota
                                        </th>
                                        <th>
                                            Origen
                                        </th>
                                        <th>
                                            Tipo
                                        </th>
                                        <th>
                                            Docente
                                        </th>


                                    </thead>
                                    <tbody>
                                        @php
                                            ksort($data);

                                        @endphp
                                        @foreach ($data as $key_2 => $nota)
                                            <tr
                                                @if ($nota['concepto_nota_id'] == '4') style="border-bottom: 2px solid black" @endif>
                                                <td>
                                                    <input type="hidden" disabled class="chk_notas-{{ $key }}"
                                                        value="{{ $nota['id'] }}">
                                                </td>
                                                <td>

                                                    @php
                                                        $texto = $nota['nota'];
                                                        $fecha_formateada = '';
                                                        $textoReemplazado = '';
                                                        $patron = '/\b(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\b/';
                                                        if (preg_match_all($patron, $texto, $coincidencias)) {
                                                            $fechas = $coincidencias[0];
                                                            $fechas_a = [];
                                                            foreach ($fechas as $fecha) {
                                                                $fechas_a[] = getSmallDate($fecha);
                                                            }
                                                            if (count($fechas_a) > 0) {
                                                                $reemplazo = function ($coincidencia) use (&$fechas_a) {
                                                                    // Obtener el índice actual del array de nuevos textos
                                                                    static $indice = 0;

                                                                    // Obtener el nuevo texto correspondiente al índice actual
                                                                    $nuevoTexto = $fechas_a[$indice];

                                                                    // Incrementar el índice para la próxima coincidencia
                                                                    $indice++;

                                                                    return $nuevoTexto;
                                                                };

                                                                $textoReemplazado = preg_replace_callback(
                                                                    $patron,
                                                                    $reemplazo,
                                                                    $texto,
                                                                );
                                                            }
                                                        }
                                                    @endphp
                                                    {!! $nota['concepto_nota'] !!}
                                                </td>

                                                @php
                                                    if (
                                                        $nota['concepto_nota_id'] == '1' and
                                                        is_numeric($nota['nota'])
                                                    ) {
                                                        $promedio_c = $promedio_c + $nota['nota'];
                                                        $contador_c = $contador_c + 1;
                                                    }

                                                    if (
                                                        $nota['concepto_nota_id'] == '2' and
                                                        is_numeric($nota['nota'])
                                                    ) {
                                                        $promedio_a = $promedio_a + $nota['nota'];
                                                        $contador_a = $contador_a + 1;
                                                    }
                                                    if (
                                                        $nota['concepto_nota_id'] == '3' and
                                                        is_numeric($nota['nota'])
                                                    ) {
                                                        $promedio_e = $promedio_e + $nota['nota'];
                                                        $contador_e = $contador_e + 1;
                                                    }

                                                @endphp

                                                <td width="30%">

                                                    {!! $textoReemplazado == '' ? $nota['nota'] : $textoReemplazado !!}
                                                </td>
                                                <td>
                                                    {{ $nota['origen_nota'] }}
                                                </td>
                                                <td>
                                                    {{ $nota['tipo'] }}
                                                </td>
                                                <td colspan="4">
                                                    {{ $nota['docevname'] }}
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                            </td>
                        </tr>
                    @empty

                    @endforelse
                    {{--   @if ($contador_e > 0 and Request::has('segid') and Request::get('segid') != '')
            <tr>
                <td>
                    Promedio Conocimiento
                </td>
                <td>
                    Promedio Aplicación
                </td>
                <td>
                    Promedio Ética
                </td>
                <td>
                    Promedio General
                </td>
            </tr>
            <tr>
                <td>
            
                    {{number_format($promedio_c/$contador_c,1,'.',' ' )}} 
                </td>
                <td>
                    
                    {{number_format($promedio_a/$contador_a,1,'.',' ' )}} 
                </td>
                <td>
                    {{number_format($promedio_e/$contador_e,1,'.',' ' )}} 
                </td>
                <td>
                   {{ number_format((($promedio_c/$contador_c) + ($promedio_a/$contador_a) +($promedio_e/$contador_e)) /3,1,'.',' ')}} 
                </td>
            </tr>
            @endif --}}
                </tbody>
            </table>


        </div>
    </div>
    @include('myforms.notas_ver.modal_detalles')
@stop
@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <script type="module" src={{ asset('js/admin_notas_ver.js') }}></script>
@endpush
