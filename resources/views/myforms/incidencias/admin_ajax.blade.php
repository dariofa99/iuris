 <table class="table table-hover mb-0" id="tbl_incidencias_admin">
     {{-- <thead class="thead-light">
                        <tr class="text-muted" style="font-size: 13px; text-transform: uppercase; letter-spacing: 0->5px;">
                            <th scope="col" class="pl-4">#</th>
                            <th scope="col">Motivo</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Estado</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead> --}}
     <tbody>
         @foreach ($incidencias as $key => $incidencia)
             <tr style="font-size:15px !important; border-bottom: 1px solid #dee2e6;" class="row-incidencia"
                 data-id="{{ $incidencia->id }}" id="row-incidencia-{{ $incidencia->id }}">
                 <td class="cell-contenido">
                     <span class="badge badge-pill badge-success numero">{{ $key + 1 }}</span>

                     <span class="categoria-texto">
                         {{ $incidencia->categoria->ref_nombre }}
                     </span>
                     <span class="{{ $incidencia->estado_id == 272 ? 'badge badge-pill badge-success numero' : '' }}">

                         ({{ $incidencia->estado->ref_nombre }})
                     </span>

                        
                     <strong>Cédula:</strong> {{ $incidencia->user->idnumber }}


                     @if ($incidencia->asignaciones->count() > 0 and Route::is('incidencias.index'))
                         <br>
                         <strong>Expediente(s):</strong>
                         @foreach ($incidencia->asignaciones as $asignacion)
                             <span class="badge badge-secondary">
                                 {{ $asignacion->expediente->expid }}
                             </span>
                             @if ($asignacion->expediente->exptipoproce_id != 3)
                                 <a href="{{ url('/expedientes/' . $asignacion->expediente->expid . '/edit') }}"
                                     target="_blank" rel="noopener noreferrer">
                                     <i class="fa fa-clone"></i> Ir al caso

                                 </a>
                             @else
                                 <a href="{{ url('/defensas/oficio/' . $asignacion->expediente->expid . '/edit') }}"
                                     target="_blank" rel="noopener noreferrer"><i class="fa fa-clone"></i> Ir al
                                     caso</a>
                             @endif
                         @endforeach
                     @endif

                 </td>
                 <td class="text-center">
                     <button data-id="{{ $incidencia->id }}"
                         class="btn btn-sm btn-outline-primary btn_inmostradetalles" data-toggle="tooltip"
                         title="Ver detalles">
                         <i class="fas fa-eye"></i>
                     </button>
                 </td>
             </tr>

             <tr id="deta-{{ $incidencia->id }}" class="fila-detalle"
                 style="display: @if ($key == 0 and $incidencia->estado_id == 272) @else none @endif;">
                 <td colspan="4">
                     <ul class="incidencias-list">
                         @foreach ($incidencia->estados()->orderBy('created_at', 'desc')->get() as $key2 => $estado)
                             <li class="item-estado">
                                 <div class="row align-items-center">

                                     <!-- Motivo -->
                                     <div class="col-md-3 motivo">
                                         <i class="fa fa-reply icon-rotate"></i>
                                         {{ $estado->motivo }}
                                     </div>

                                     <div class="col-md-2 motivo">
                                         @if ($estado->files->count() > 0)
                                             <i class="fa fa-paperclip">
                                                 <a target="_blank"
                                                     href="{{ url('file/download', $estado->files->first()->id) }}">Archivo</a>
                                             </i>
                                         @endif

                                     </div>

                                     <!-- Usuario -->
                                     <div class="col-md-2 usuario">
                                         {{--  <i class="fa fa-user-circle"></i> --}}
                                         {{ $estado->user->name }} <br>
                                         {{ $estado->user->lastname }}
                                     </div>

                                     <!-- Fecha -->
                                     <div class="col-md-2 fecha">
                                         <span>
                                             {{ getSmallDateWithHour($estado->created_at) }}
                                         </span>
                                     </div>

                                     <!-- Estado -->
                                     <div class="col-md-2 estado-badge">
                                         <span class="badge badge-info"
                                             style="background-color: {{ $estado->estado->color }}">
                                             {{ $estado->estado->ref_nombre }}
                                         </span>
                                     </div>

                                     @if ($key2 == 0)
                                         <div class="col-md-1 text-right acciones">
                                             @if ($estado->estado_id == 272)
                                                 @if (currentUser()->hasRole('amatai'))
                                                     <button data-estado="273" data-id="{{ $incidencia->id }}"
                                                         title="Aprobar incidencia"
                                                         class="btn btn-success btn-sm btn-action btn-block btn_act_incidencia">
                                                         <i class="fa fa-check"></i>
                                                     </button>

                                                     <button data-estado="274" data-id="{{ $incidencia->id }}"
                                                         title="Rechazar incidencia"
                                                         class="btn btn-danger btn-sm btn-action btn-block btn_act_incidencia">
                                                         <i class="fa fa-times"></i>
                                                     </button>
                                                 @endif
                                                 @if (currentUser()->id == $estado->user_id)
                                                     <input type="hidden" value="{{ $estado->motivo }}"
                                                         id="old_motivo-{{ $estado->id }}">
                                                     <button data-estado="update" data-id="{{ $estado->id }}"
                                                         title="Actualizar incidencia"
                                                         class="mt-1 btn btn-info btn-sm btn-action btn-block btn_act_incidencia">
                                                         <i class="fa fa-edit"></i>
                                                     </button>
                                                 @endif
                                             @endif
                                             @if ($estado->estado_id == 274)
                                                 @if (currentUser()->id == $incidencia->user_id)
                                                     <button data-estado="272" data-id="{{ $incidencia->id }}"
                                                         title="Solicitar revisión"
                                                         class="btn btn-warning btn-xs btn-action btn-block btn_act_incidencia">
                                                         <i class="fa fa-reply-all"></i>
                                                     </button>
                                                 @endif
                                             @endif

                                         </div>
                                         <!-- Botones -->
                                     @endif

                                 </div>
                             </li>
                         @endforeach
                     </ul>
                 </td>
             </tr>
         @endforeach
     </tbody>
 </table>
