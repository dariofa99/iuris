@extends('layouts.dashboard')
@section('area_forms')

    @include('msg.success')

    <div class="row">

        {!! Form::model(Request::all(), ['route' => 'requerimientos.index', 'method' => 'GET']) !!}

        <div class="col-md-6">
            <div class="box-body table-responsive no-padding">
                <table class="table-buscar-expe">
                    <tr>
                        <td colspan="3"><b>Busqueda</b></td>
                    </tr>
                    <tr>
                        <td width="35%">
                            <div class="form-grou">
                                <select name="tipo_busqueda" id='tipo_busqueda' class="form-control"
                                    placeholder="Seleccione..." required="required">
                                    <option value="">Seleccione...</option>

                                    <option @if (isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'codido_exp') selected @endif value="codido_exp">Número de
                                        Expediente</option>

                                    <option @if (isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'fecha_creacion') selected @endif value="fecha_creacion">Fecha
                                        de Creación</option>

                                    <option @if (isset($request['tipo_busqueda']) and $request['tipo_busqueda'] == 'fecha_cita') selected @endif value="fecha_cita">Fecha de
                                        Cita</option>


                                </select>

                            </div>
                        </td>
                        <td width="35%">



                            @if (isset($request['tipo_busqueda']))
                                <div id="input_text" class="inputs"
                                    @if ($request['tipo_busqueda'] == 'consultante_num' ||
                                        $request['tipo_busqueda'] == 'codido_exp' ||
                                        $request['tipo_busqueda'] == 'estudiante_num') style="display: block;" <?php $disabled = ''; ?>  @else style="display: none;" <?php $disabled = 'disabled'; ?> @endif>

                                    {!! Form::text('data', null, [
                                        'class' => 'form-control input-search',
                                        'required' => 'required',
                                        'id' => 'input_data',
                                        $disabled,
                                    ]) !!}

                                </div>
                            @else
                                <div id="input_text" class="inputs">
                                    {!! Form::text('data', null, [
                                        'class' => 'form-control input-search',
                                        'required' => 'required',
                                        'id' => 'input_data',
                                        'placeholder' => 'No de Documento',
                                    ]) !!}

                                </div>
                            @endif





                            <div id="input_date" class="inputs"
                                @if (isset($request['tipo_busqueda']) and
                                    $request['tipo_busqueda'] == 'fecha_creacion' || $request['tipo_busqueda'] == 'fecha_cita') <?php $disabled = '';
                                    $fecha = ''; ?> style="display: block;"  @else style="display: none;" <?php $disabled = 'disabled'; ?> @endif>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    {!! Form::date('data', null, [
                                        'id' => 'date_data',
                                        'class' => 'form-control datepicker input-search',
                                        'required' => 'required',
                                        $disabled,
                                    ]) !!}
                                </div>
                            </div>





                        </td>
                        <td>
                            <button type="submit" class="btn btn-success"><i class="fa fa-search"> </i> Buscar </button>

                        </td>
                        <td>
                            <a href="/requerimientos" class="btn btn-default">Ver Todo</a>
                        </td>
                    </tr>

                </table>
            </div>


        </div>


        {!! Form::close() !!}


    </div>

    <br>
    {!! Form::open(['route' => 'curso.empty', 'method' => 'GET']) !!}

    <div class="row">
        <div class="col-md-2 col-md-offset-10">
            @if (isset($data_search) and count($users) > 0)
                <button type="submit" class="btn btn-warning"><i class="fa  fa-hourglass-o"></i> Vaciar Curso</button>
            @endif
        </div>



        <div class="row">
            <div class="col-sm-12">
                <div class="box-body table-responsive no-padding">
                    <table id="tbl_users" class="table table-bordered table-striped dataTable" role="grid">


                        <thead>
                            <tr>
                                <th>Fecha de Creación</th>
                                <th width="25%">Motivo</th>
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
                                <tr>
                                    <td>
                                        {{ $req->getFechaCorta($req->created_at) }}
                                    </td>
                                    <td>
                                        {{ $req->reqmotivo }}
                                    </td>
                                    <td>
                                        {{ $req->expid }}
                                    </td>
                                    <td>
                                        {{ $req->reqfecha }}
                                    </td>
                                    <td>
                                        {{ $req->reqhora }}
                                    </td>
                                    <td>
                                        {{ $req->ref_reqasistencia }}
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

                                        <a href='#' data-toggle='modal' OnClick='searchReq({{ $req->id }})'
                                            data-target='#myModal_req_details' class='btn btn-success btn-block'
                                            role='button'>Detalles</a>

                                        @if ($req->reqentregado and
                                            currentUser()->hasRole('coordprac') || currentUser()->hasRole('diradmin') || currentUser()->hasRole('amatai'))
                                            <button type="button" data-toggle='modal'
                                                @if ($req->reqfecha <= date('Y-m-d')) OnClick='searchReq({{ $req->id }})' @else disabled @endif
                                                data-target='#myModal_req_asist' class='btn btn-primary btn-block'
                                                role='button'>Asistencia
                                            </button>
                                        @else
                                            <button type="button" data-toggle='modal' disabled
                                                data-target='#myModal_req_asist' class='btn btn-primary btn-block'
                                                role='button'>Asistencia
                                            </button>
                                        @endif
                                        @if (currentUser()->hasRole('secretaria') ||
                                            currentUser()->hasRole('coordprac') ||
                                            currentUser()->hasRole('amatai') ||
                                            currentUser()->hasRole('diradmin'))
                                            @if (!$req->reqentregado)
                                                <a href='#' OnClick='changeStateReq({{ $req->id }},"general")'
                                                    class='btn btn-primary btn-block' role='button'>Marcar como
                                                    Entregado</a>
                                            @else
                                                <a href='#' OnClick='changeStateReq({{ $req->id }},"general")'
                                                    class='btn btn-danger btn-block' role='button'>Marcar como No
                                                    Entregado</a>
                                            @endif
                                        @endif
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

        {!! Form::close() !!}

    @stop
