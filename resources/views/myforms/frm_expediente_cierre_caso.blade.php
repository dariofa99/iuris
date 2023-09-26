    @if (
        $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber or
            currentUser()->hasRole('estudiante') or
            currentUser()->hasRole('amatai') or
            currentUser()->hasRole('diradmin') or
            currentUser()->hasRole('dirgral'))

        <div class="col-md-12" align="right">
            @if (
                (currentUser()->hasRole('estudiante') and
                    $expediente->getDaysOrColorForClose('dias') >= 10 || $expediente->exptipoproce_id != 1 and
                    $expediente->estado->id == 1 || $expediente->estado->id == 3) ||
                    ($expediente->getDocenteAsig()->idnumber == currentUser()->idnumber and $expediente->estado->id == 4) ||
                    (currentUser()->hasRole('amatai') or currentUser()->hasRole('diradmin') or currentUser()->hasRole('dirgral')))

                @if ($expediente->getDocenteAsig()->idnumber == 'Sin asignar')
                    <button disabled type="button" class="btn btn-danger btn-sm mb-2"
                        id="btn_trigger_exp_edit_cierre_caso">
                        Debe solicitar la asignación del docente
                    </button>
                @else
                    <button type="button" class="btn btn-primary btn-sm mb-2" data-toggle="modal"
                        data-target="#myModal_exp_edit_cierre_caso" id="btn_trigger_exp_edit_cierre_caso">
                        Actualizar Solicitud de cierre
                    </button>
                @endif

            @endif

            @if (
                $expediente->exptipoproce_id == 1 and
                    ($expediente->getDocenteAsig()->idnumber == currentUser()->idnumber and
                        $expediente->expestado_id == 5 and
                        $expediente->isValidOpen()) ||
                        (currentUser()->hasRole('diradmin') || currentUser()->hasRole('amatai')))
                <button type="button" class="btn btn-warning btn-sm mb-2" id="btn_reabrir_caso">
                    Volver a evaluar y cerrar caso
                </button>
            @endif

            @if (
                $expediente->expestado_id != 2 and
                    $expediente->expestado_id != 5 and
                    currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin'))
                <button type="button" class="btn btn-warning btn-sm mb-2" id="btn_cerrar_dr_caso">
                    Cerrar caso
                </button>
            @endif


        </div>
    @endif

    <div class="col-md-12">

        @if (count($expediente->estados) > 0)
            <div class="box-body table-responsive no-padding">
                <table id="tbl_cierre_caso" class="table table-bordered table-striped dataTable dataTable"
                    role="grid">
                    <tr>
                        <td width="15%">
                            <label>Estado actual del caso</label>
                        </td>
                        <td>
                            <label>{{ $expediente->estado->nombre_estado }}</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Fecha</label>
                        </td>
                        <td>
                           <label>{{ getSmallDateWithHour($expediente->estados()->orderBy('created_at', 'desc')->first()->created_at) }}</label>

                        </td>
                    </tr>
                    <tr>
                        <td width="15%">
                            <label>Docente</label>
                        </td>
                        <td>
                            <label>
                                {{ $expediente->estados()->orderBy('created_at', 'desc')->first()->user->name }}
                            
                                {{ $expediente->estados()->orderBy('created_at', 'desc')->first()->user->lastname }}
                                <small>
                                    ({{ $expediente->estados()->orderBy('created_at', 'desc')->first()->user->role()->first()->display_name }})
                                </small>
                            </label>
                     
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Motivo</label>
                        </td>
                        <td>
                            <label>{{ $expediente->estados()->orderBy('created_at', 'desc')->first()->motivo->nombre_motivo }}</label>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label>Comentario</label>
                        </td>
                        <td>
                            <textarea name="txtcomenta" rows="4" class="form-control form-control-sm " readonly="readonly">{{ $expediente->estados()->orderBy('created_at', 'desc')->first()->comentario }}</textarea>

                        </td>
                    </tr>

                </table>
            </div>
        @else
            <div class="box-body table-responsive no-padding">
                <table id="tbl_cierre_caso" class="table table-bordered table-striped dataTable dataTable"
                    role="grid">

                    <tr>
                        <td width="15%">
                            <label>Estado del Caso</label>
                        </td>
                        <td>
                            <label>{{ $expediente->estado->nombre_estado }}</label>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label>Motivo</label>
                        </td>
                        <td>
                            <label>Apertura</label>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label>Último Comentarios</label>
                        </td>
                        <td>
                            <textarea name="txtcomenta" rows="3" class="form-control form-control-sm " readonly="readonly">Sin comentarios aún.</textarea>
                        </td>
                    </tr>
                </table>
            </div>
        @endif
    </div>

    <div class="row">
        <hr>
        <h5>Últimos estados</h5>
        <hr>
        <div class="col-md-12">
            <div class="box-body table-responsive no-padding">
                <table class="table">
                    <thead>
                        <th>
                            Usuario
                        </th>
                        <th>
                            Rol
                        </th>
                        <th>
                            Estado
                        </th>
                        <th>
                            Motivo
                        </th>
                        <th>
                            Comentario
                        </th>
                        <th>
                            Fecha
                        </th>
                    </thead>
                    <tbody>


                        @foreach ($expediente->estados()->orderBy('created_at', 'desc')->get() as $key => $estado)
                            @if ($key != 0)
                                <tr>
                                    <td>
                                        {{ $estado->user->name }} {{ $estado->user->lastname }}
                                    </td>
                                    <td>
                                        {{ $estado->user->role()->first()->display_name }}
                                    </td>
                                    <td>


                                        @if ($estado->estado->id == '1')
                                            <span
                                                class="pull-center badge bg-green dis-block">{{ $estado->estado->nombre_estado }}</span>
                                        @elseif ($estado->estado->id == '4')
                                            <span
                                                class="pull-center badge bg-yellow dis-block">{{ $estado->estado->nombre_estado }}</span>
                                        @elseif ($estado->estado->id == '2')
                                            <span
                                                class="pull-center badge bg-blue dis-block">{{ $estado->estado->nombre_estado }}</span>
                                        @elseif ($estado->estado->id == '3')
                                            <span
                                                class="pull-center badge bg-red dis-block">{{ $estado->estado->nombre_estado }}</span>
                                        @else
                                            <span class="pull-center badge bg-red dis-block">
                                                {{ $estado->estado->nombre_estado }}
                                            </span>
                                        @endif


                                    </td>
                                    <td>
                                        {{ $estado->motivo->nombre_motivo }}
                                    </td>
                                    <td>
                                        {{-- <button data-id="{{$estado->id}}" class="btn btn-success btn-sm btn-block btn_detalles_estado_caso">
                                    Detalles
                                </button>  --}}
                                        {{ $estado->comentario }}
                                    </td>
                                    <td>
                                        <span title="Fecha en la que se envió la solicitud">
                                            {{ getSmallDateWithHour($estado->created_at) }}</span>
                                        <span title="Números de días después de la asignación" style="display: block">

                                            <small>
                                                <i>
                                                    ({{ $expediente->difDays($expediente->asignaciones[0]->fecha_asig, $estado->created_at) }}
                                                    días despues de asignado)
                                                </i>

                                            </small>

                                    </td>


                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> <!-- /.md12-->
    </div> <!-- /.row -->
