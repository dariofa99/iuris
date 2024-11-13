<div class="col-md-12">
    <div class="box-body table-responsive no-padding">
        <table class="normal-table table-list-est-tur" id="table_list_estudiantes_aud">
            <thead>
                <th width="10%">
                    No. Documento
                </th>
                <th width="20%">
                    Estudiante
                </th>
                <th width="15%">
                    Curso
                </th>
                <th>
                    Horario
                </th>
                <th>
                    Conciliaciones
                </th>
                <th>
                    Rol
                </th>
                @if (currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral'))
                    <th>
                        Acciones
                    </th>
                @endif
            </thead>
            <tbody>
                @foreach ($turnos as $turno)
                    <tr>
                        <td> 
                            {{ $turno->estudiante->idnumber }}
                        </td>

                        <td align="left">
                            <input type="hidden" value="{{ $turno->estudiante->id }}"
                                id="estudiante_id{{ $turno->id }}">
                            {{ $turno->estudiante->name }} {{ $turno->estudiante->lastname }}
                        </td>
                        <td>
                            <label> {{ $turno->estudiante->curso->ref_nombre }} </label>
                        </td>
                        <td>
                            <label
                                class="label dis-block {{ $turno->getColorTurno($turno->color->ref_value) }}">{{ $turno->horario->ref_nombre }}</label>
                        </td>
                        <td>
                            <label id="label_num_conciliacion_est{{ $turno->estudiante->idnumber }}"
                                style="font-weight: 100;">
                                {{ count($turno->estudiante->conciliaciones) }}

                            </label>
                        </td>
                        <td width="15%">
                            @php
                                $asig_reparto = $turno->estudiante
                                    ->tipo_conciliacion()
                                    ->where(['conciliacion_id' => $conciliacion->id,
                                        'conciliacion_has_user.user_id' => $turno->estudiante->id,
                                    ])
                                    ->where(function ($query) {
                                        $query->orWhere([
                                                'conciliacion_has_user.tipo_usuario_id' => 203,
                                            ])
                                            ->orWhere([
                                                'conciliacion_has_user.tipo_usuario_id' => 204,
                                            ]);
                                    })
                                    ->first();
                                
                                if ($asig_reparto) {
                                    $label = $asig_reparto->ref_nombre;
                                    $rolid = $asig_reparto->id;
                                } else {
                                    $label = 'Sin asginar';
                                    $rolid = 0;
                                }
                                
                            @endphp

                            <label id="label_rol_est_conciliacion{{ $turno->estudiante->idnumber }}"
                                style="font-weight: 100; font-size: 13px;">
                                {{ $label }}
                            </label>

                            <select class="form-control form-control-sm input-select" name="select"
                                id="select_rol_est_conciliacion{{ $turno->estudiante->idnumber }}"
                                data-id="{{ $conciliacion->id }}" style="display: none;">
                                <option value="000"></option>
                            </select>


                        </td>
                        @if (currentUser()->hasRole('amatai') ||
                                currentUser()->hasRole('coord_centro_conciliacion') ||
                                currentUser()->hasRole('diradmin') ||
                                currentUser()->hasRole('dirgral') ||
                                (currentUser()->hasRole('estudiante') and
                                    currentUserInConciliacion($conciliacion->id, ['conciliador', 'auxiliar'])))
                            <td>
                                <a style="display: none;" data-rol="{{$rolid}}" class="btn btn-success btn-sm btn-block btn_update_rol_est"
                                    id="btn_UpdateRol_est{{ $turno->estudiante->idnumber }}"
                                    data-id="{{ $turno->estudiante->idnumber }}"><i class="fa fa-check-square"> </i>
                                    Actualizar</a>
                                <a style="display: none;"
                                    class="btn btn-sm btn-block btn-warning btn_hide_edit_rol_conciliacion_est"
                                    data-id="{{ $turno->estudiante->idnumber }}"
                                    id="btn_hide_edit_rol_conciliacion_est{{ $turno->estudiante->idnumber }}"><i
                                        class="fa fa-close"> </i> Cancelar</a>
                                <a data-rol="{{$rolid}}"  class="btn btn-primary  btn-sm btn-block btn_asignar_estudiante_audiencia"
                                    id="btn_habilityEditRol_Est{{ $turno->estudiante->idnumber }}"
                                    data-id="{{ $turno->estudiante->idnumber }}" data-state=""><i class="fa fa-edit">
                                    </i>Editar</a>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <hr>
</div>
