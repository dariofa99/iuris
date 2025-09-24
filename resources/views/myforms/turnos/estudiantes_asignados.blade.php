<div class="row">
    <div class="col-md-10">
        {!! Form::open(['route' => 'turnos.index', 'method' => 'GET']) !!}
        <table class="normal-table">
            <tr>
                <td>
                    Busqueda
                </td>
                <td>

                </td>
                <td>
                    <select required class="form-control input-select" name="data_search" id="select_data_cursando">
                        <option value="">Seleccione...</option>
                        @foreach ($cursando as $key => $item)
                            <option
                                {{ (Request::has('data_search') and Request::get('data_search') == $key) ? 'selected' : '' }}
                                value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>

                </td>
                <td>
                    <input class="btn btn-success" type="submit" name="buscar" value="Buscar">
                    <a class="btn btn-default" href="/turnos">Ver Todo</a>
                    <a class="btn btn-default bg-green" id="btn_descargar_turnos" href="/turnos/descargar/curso">
                        <i class="fa fa-file-excel"></i>
                        Descargar
                    </a>
                </td>
            </tr>
        </table>
        {!! Form::close() !!}
    </div>
    <div class="col-md-2 col-md-offset-4">
        <table class="table-colors">
            <tr>
                <td>
                    <label class="badge color-amarillo ">{{ $colores[0]->amarillo }}</label>
                </td>
                <td>
                    <label class="badge color-rojo">{{ $colores[0]->rojo }}</label>
                </td>
                <td>
                    <label class="badge color-verde">{{ $colores[0]->verde }}</label>
                </td>
                <td>
                    <label class="badge color-gris">{{ $colores[0]->gris }}</label>
                </td>
                <td>
                    <label class="badge color-azul">{{ $colores[0]->azul }}</label>
                </td>
                <td>
                    <label class="badge color-morado">{{ $colores[0]->morado }}</label>
                </td>
            </tr>
        </table>
    </div>
</div>
<hr>

<div class="row">
    <div class="col-md-12">
        <div class="box-body table-responsive no-padding">
            <table class="normal-table table-list-est-tur">
                <thead>
                    <th>
                        No.
                    </th>
                    <th width="10%">
                        No. Documento
                    </th>

                    <th>
                        Estudiante
                    </th>

                    <th width="11%">
                        Color
                    </th>
                    <th width="8%">
                        Curso
                    </th>
                    <th width="11%">
                        Horario
                    </th>
                    <th width="10%">
                        Oficina
                    </th>
                    <th width="13%">
                        Dia
                    </th>
                    @if (currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('coordprac'))
                        <th width="11%">
                            Acciones
                        </th>
                    @endif
                </thead>
                <tbody>
                    @foreach ($turnos as $key => $turno)
                        <tr>
                            <td>
                                {{ $key + 1 }}
                            </td>
                            <td>
                                {{ $turno->idnumber }}
                            </td>

                            <td align="left">
                                <input type="hidden" value="{{ $turno->estudiante_id }}"
                                    id="estudiante_id{{ $turno->id }}">
                                {{ $turno->name }} {{ $turno->lastname }}

                            </td>

                            <td>
                                {!! Form::select('color_id', $ref_color, $turno->trnid_color, [
                                    'class' => 'form-control  input-select',
                                    'data-live-search' => 'true',
                                    'required' => 'required',
                                    'id' => "color_id$turno->id",
                                    'style' => 'display:none',
                                ]) !!}



                                <label id="label_color{{ $turno->id }}"
                                    class="label dis-block {{ $turno->getColorTurno($turno->color_ref_value) }}">{{ $turno->color_nombre }}</label>
                            </td>
                            <td>
                                {!! Form::select('cursando_id', $cursando, $turno->curso_id, [
                                    'class' => 'form-control input-select',
                                    'data-live-search' => 'true',
                                    'required' => 'required',
                                    'id' => "cursando_id$turno->id",
                                    'style' => 'display:none',
                                ]) !!}

                                <label id="label_cursando{{ $turno->id }}">
                                    {{ $turno->curso_nombre }} </label>
                            </td>

                            <td>
                                {!! Form::select('horario_id', $ref_horarios, $turno->trnid_horario, [
                                    'class' => 'form-control input-select',
                                    'data-live-search' => 'true',
                                    'required' => 'required',
                                    'id' => "horario_id$turno->id",
                                    'style' => 'display:none',
                                ]) !!}
                                <label id="label_horario{{ $turno->id }}">
                                    {{ $turno->horario_nombre }}
                                </label>
                            </td>
                            <td>
                                {!! Form::select('trnid_oficina', $oficinas, $turno->oficina_id, [
                                    'class' => 'form-control input-select',
                                    'data-live-search' => 'true',
                                    'required' => 'required',
                                    'id' => "trnid_oficina$turno->id",
                                    'style' => 'display:none',
                                ]) !!}
                                <label id="label_trnid_oficina{{ $turno->id }}">

                                    {{ $turno->oficina_nombre }}
                                </label>
                            </td>
                            <td>
                                {!! Form::select('trnid_dia', $dias, $turno->trnid_dia, [
                                    'class' => 'form-control input-select',
                                    'data-live-search' => 'true',
                                    'required' => 'required',
                                    'id' => "trnid_dia$turno->id",
                                    'style' => 'display:none',
                                ]) !!}
                                <label id="label_trnid_dia{{ $turno->id }}">
                                    {{ $turno->dia_nombre }}
                                </label>
                            </td>
                            @if (currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('coordprac'))
                                <td>
                                    <a style="display: none;" class="btn btn-success  btn-block btn-sm btn_updatecolor"
                                        data-id="{{ $turno->id }}" id="btnUpdatecolor_{{ $turno->id }}">
                                        <i class="fa fa-check-square"> </i>
                                        Actualizar</a>

                                    <a style="display: none;"
                                        class="btn btn-block btn-sm btn-warning btn_cancelupdcolor"
                                        id="btn_hideupdatecolor{{ $turno->id }}"
                                        data-id="{{ $turno->id }}">Cancelar</a>

                                    <a class="btn btn-primary  btn-block btn-sm btn_habilityupdatecolor"
                                        data-id="{{ $turno->id }}" id="btn_habilityupdatecolor{{ $turno->id }}">
                                        <i class="fa fa-edit"></i>Editar</a>

                                    <a class="btn btn-danger  btn-block btn-sm btn_delete_turno"
                                        data-id="{{ $turno->id }}" id="btn_delete_turno-{{ $turno->id }}">
                                        <i class="fa fa-edit"></i>Eliminar</a>

                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr>
    </div>
</div>
@if (currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral'))
    <div class="row">
        <div class="col-md-6">
            <a id="btn_del_all_turnos" class="btn btn-danger"> <i class="fa fa-trash"> </i> Eliminar
                todos los turnos</a>
        </div>
    </div>
@endif
