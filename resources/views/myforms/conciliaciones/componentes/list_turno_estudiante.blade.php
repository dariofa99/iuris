
        <div class="col-md-12">
            <div class="box-body table-responsive no-padding">
                <table class="normal-table table-list-est-tur">
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
                        @if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole("dirgral"))
                        <th>
                            Acciones
                        </th>
                        @endif
                    </thead>
                    <tbody>
                        @foreach($turnos as $turno)
                          <tr>
                            <td>
                                {{$turno->idnumber }}
                            </td>

                            <td align="left">
                                <input type="hidden" value="{{$turno->estudiante_id}}" id="estudiante_id{{$turno->id}}">
                                {{$turno->name }} {{$turno->lastname }}
                            </td>
                            <td>
                            <label> {{$turno->curso_nombre}} </label>
                            </td>
                            <td>
                            <label class="label dis-block {{$turno->getColorTurno($turno->color_ref_value) }}">{{$turno->horario_nombre }}</label>
                            </td>
                            <td>
                                <label id="label_num_conciliacion_est{{$turno->idnumber}}" style="font-weight: 100;"> 0 </label>
                            </td>
                            <td>
                                <label id="label_rol_est_conciliacion{{$turno->idnumber}}" style="font-weight: 100; font-size: 13px;"> sin asignar </label>
                                <select class="form-control input-select" name="select" id="select_rol_est_conciliacion{{$turno->idnumber}}" data-id="{{$conciliacion->id}}" style="display: none;">
                                    <option value="000">Eliminar asignación</option>

                                </select>
                            </td>
                            @if(currentUser()->hasRole('amatai') || currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('diradmin') || currentUser()->hasRole("dirgral") || (currentUser()->hasRole('estudiante') and currentUserInConciliacion($conciliacion->id,['conciliador','auxiliar'])))
                            <td>
                                <a style="display: none;" class="btn btn-success" id="btn_UpdateRol_est{{$turno->idnumber}}" onclick="Updaterolest_conciliacion('{{$turno->idnumber}}')"><i class="fa fa-check-square"> </i> Actualizar</a>
                                <a style="display: none;" class="btn btn-warning" id="btn_hide_edit_rol_conciliacion_est{{$turno->idnumber}}" onclick="hideupdaterolest('{{$turno->idnumber}}')"><i class="fa fa-close"> </i> Cancelar</a>
                                <a class="btn btn-primary" id="btn_habilityEditRol_Est{{$turno->idnumber}}" data-state="" onclick="editRolEstudentAudiencia('{{$turno->idnumber}}')"><i class="fa fa-edit"> </i>Editar</a>
                            </td> 
                            @endif
                        </tr>
                       
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr>
        </div>
