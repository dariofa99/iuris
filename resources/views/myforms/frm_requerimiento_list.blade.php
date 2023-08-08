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
@section('area_forms')
    @include('msg.success')
    {!! Form::model(Request::all(), ['route' => 'requerimientos.index', 'method' => 'GET']) !!}
    <div class="row">
        <div class="col-md-4">
            <label for="">Busqueda</label>
            <div class="form-group">
                <select name="tipo_busqueda" id='tipo_busqueda' class="form-control form-control-sm" placeholder="Seleccione..."
                    required="required">
                    <option value="">Seleccione...</option>

                    <option @if (isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'codido_exp') selected @endif value="codido_exp">Número de
                        Expediente</option>

                    <option @if (isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'fecha_creacion') selected @endif value="fecha_creacion">Fecha
                        de Creación</option>

                    <option @if (isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'fecha_cita') selected @endif value="fecha_cita">Fecha de
                        Cita</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <label for="">Tipo busqueda</label>
            <div class="form-group">
            @if (Request::has('tipo_busqueda'))
               
                    @if (Request::get('tipo_busqueda') == 'codido_exp')
                        {!! Form::text('data', Request::get('data'), [
                            'class' => 'form-control form-control-sm input-search',
                            'required' => 'required',
                            'id' => 'input_data',
                        ]) !!}
                    @else
                        {!! Form::date('data', Request::get('data'), [
                            'class' => 'form-control form-control-sm input-search',
                            'required' => 'required',
                            'id' => 'input_data',
                        ]) !!}
                    @endif
                @else
                    {!! Form::text('data', Request::get('data'), [
                        'class' => 'form-control form-control-sm input-search',
                        'required' => 'required',
                        'id' => 'input_data',
                    ]) !!}
               
            @endif
        </div>
        </div>
        <div class="col-md-4">
           <label style="color:azure">.</label>
           <div class="form-group">
            <button type="submit" class="btn btn-sm btn-success">
                <i class="fa fa-search"> </i> Buscar
            </button>
            <a href="/requerimientos" class="btn btn-default btn-sm">Ver Todo</a>
           </div>          
        </div>
    </div>
    {!! Form::close() !!}
    <hr>   
        <div class="row">
            <div class="col">
                <div class="box-body table-responsive no-padding">
                    <table id="table_list_autorizaciones" class="table table-bordered table-striped dataTable" role="grid">
                        <thead>
                            <tr>
                                <th>Fecha de Creación</th>
                                <th>Motivo</th>
                                <th>Expediente</th>
                                <th>Fecha Cita</th>
                                <th>Hora Cita</th>
                                <th>Asistencia</th>
                                <th>Estado</th>
                                <th>Evaluado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requerimientos as $req)
                                @php
                                    if (date('Y-m-d') >= $req->reqfecha && $req->reqentregado && !$req->evaluado) {
                                        $estadoBtn = '';
                                        $label = currentUser()->hasRole('estudiante') ? 'Comentar' : 'Evaluar';
                                        $label = currentUser()->hasRole('coordprac') ? 'Asistencia' : $label;
                                    } else {
                                        $estadoBtn = 'disabled';
                                        $label = 'Evaluado';
                                        if (date('Y-m-d') < $req->reqfecha && !$req->evaluado) {
                                            $label = 'Esperando fecha cita';
                                        }
                                        if (!$req->reqentregado && !$req->evaluado) {
                                            $label = 'Sin entregar';
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        {{ getSmallDate($req->created_at) }}
                                    </td>
                                    <td>
                                        {{ $req->reqmotivo }}
                                    </td>
                                    <td>
                                        {{ $req->expediente->expid }}
                                    </td>
                                    <td>
                                        {{ getSmallDate($req->reqfecha) }}
                                    </td>
                                    <td>
                                        {{ $req->reqhora }}
                                    </td>
                                    <td>
                                        {{ $req->reqasistencia->ref_reqasistencia }}
                                    </td>
                                    <td>
                                        @if ($req->reqentregado)
                                            <label class="label label-success">Entregado</label>
                                        @else
                                            <label class="label label-danger">Sin entregar</label>
                                        @endif

                                    </td>
                                    <td>
                                        {{ $req->evaluado == 0 ? 'Sin evaluar' : 'Evaluado' }}
                                    </td>
                                    <td>

                                        @if (
                                            !$req->evaluado and
                                                currentUser()->hasRole('amatai') || currentUser()->hasRole('secretaria') || currentUser()->hasRole('coordprac'))
                                            <a href='#' data-id="{{ $req->id }}"
                                                data-estado="{{ $req->reqentregado }}"
                                                class='btn  {{ $req->reqentregado ? 'btn-danger' : 'btn-primary' }}  btn-sm btn-block btn_cambiar_estado_requerimiento'
                                                role='button'>
                                                {{ $req->reqentregado ? 'Marcar como no entregado' : 'Marcar como entregado' }}
                                            </a>
                                        @endif


                                        <a href='#' data-id="{{ $req->id }}" data-modal='#myModal_req_details'
                                            class='btn_editar_req btn btn-success btn-sm btn-block' role='button'>
                                            Detalles
                                        </a>
                                        @if (currentUser()->hasRole('coordprac') ||
                                                currentUser()->hasRole('amatai') ||
                                                (currentUser()->hasRole('estudiante') and $expediente->expidnumberest == currentUser()->idnumber) ||
                                                $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber)
                                            <button href='#' {{ $estadoBtn }} data-id="{{ $req->id }}"
                                                data-modal='#myModal_req_asist'
                                                class='btn btn-info btn-sm btn-block btn_editar_req' role='button'>
                                                {{ $label }}
                                            </button>
                                        @endif
                                        <a href="{{ url('/reqpdfgen', $req->id) }}" target='_blank'
                                            class='btn btn-warning btn-sm btn-block' role='button'>
                                            Imprimir
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {!! $requerimientos->render() !!}
            </div>
        </div>
        @include('myforms.frm_requerimiento_details')
        @include('myforms.frm_requerimiento_asist')
    @stop
    @push('scripts')
        <!-- aqui van los scripts de cada vista -->
        <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>
        <script type="module"   src={{asset("js/admin_requerimientos.js")}}></script>
    @endpush
