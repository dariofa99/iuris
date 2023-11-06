 <div class="table-responsive no-padding">
     <table style="font-size: 15px !important" id="tbl_users" class="table table-bordered table-striped dataTable"
         role="grid">
         <thead>
             <tr>
                 <th>Expediente</th>
                 @if (!currentUser()->hasRole('solicitante'))
                     <th>Consultante</th>
                 @endif
                 @if (!currentUser()->hasRole('estudiante'))
                     <th>Estudiante</th>
                 @endif
                 <th>Tipo Consulta</th>
                 <th>Fecha</th>
                 <th>Estado</th>
                 <th>Acción</th>
             </tr>
         </thead>
         <tbody>

             @foreach ($expedientes as $expediente)
                 <tr role="row">
                     <td>{{ $expediente->expid }}</td>
                     @if ($expediente->solicitante and !currentUser()->hasRole('solicitante'))
                         <td>
                             <div @if (currentUser()->hasRole('docente')) class="textcor" @endif>
                                 {{ FullName($expediente->solicitante->name, $expediente->solicitante->lastname) }}
                             </div>
                         </td>
                     @endif
                     @if (!currentUser()->hasRole('estudiante'))
                         <td>
                             {{ FullName($expediente->estudiante->name, $expediente->estudiante->lastname) }}
                         </td>
                     @endif


                     <td>
                         @if ($expediente->exptipoproce_id == '1')
                             Asesoría
                         @elseif($expediente->exptipoproce_id == '2')
                             Seguimiento
                         @else
                             Defensa de Oficio
                         @endif
                     </td>

                     <td>
                         @if ($expediente->exptipoproce_id == '1' and $expediente->expestado_id != '2' and !currentUser()->hasRole('solicitante'))
                             <label class="pull-center badge-colors dis-block"
                                 style="border-radius:8px;border: 2px solid {{ $expediente->getDaysOrColorForClose('color') }}; color : {{ $expediente->getDaysOrColorForClose('color') }}">
                                 {{ $expediente->getDaysOrColorForClose('mensaje') }}

                             </label>
                         @else
                             @if ($expediente->getAsignacion() == null)
                                 Se debe revisar la asignación
                             @else
                                 {{ \Carbon\Carbon::parse($expediente->getAsignacion()->fecha_asig)->diffForHumans() }}
                             @endif
                         @endif




                     </td>
                     <td>

                         @if ($expediente->expestado_id == '1')
                             <span class="pull-center badge bg-green dis-block">
                                 @if ($expediente->exptipoproce_id != '1')
                                     @php $circle=$expediente->getActuacions($expediente->expid); @endphp
                                     <div class="{{ $circle[0] }}">
                                         {{ $circle[1] }}
                                     </div>
                                 @endif
                                 @if (!currentUser()->hasRole('solicitante'))
                                     {{ $expediente->estado->nombre_estado }}
                                 @else
                                     En proceso
                                 @endif
                             </span>
                         @elseif ($expediente->expestado_id == '4')
                             <span class="pull-center badge bg-yellow dis-block">
                                 @if ($expediente->exptipoproce_id != '1')
                                     @php $circle=$expediente->getActuacions($expediente->expid); @endphp
                                     <div class="{{ $circle[0] }}">
                                         {{ $circle[1] }}
                                     </div>
                                 @endif
                                 @if (!currentUser()->hasRole('solicitante'))
                                     {{ $expediente->estado->nombre_estado }}
                                 @else
                                     En revisión
                                 @endif
                             </span>
                         @elseif ($expediente->expestado_id == '2')
                             <span class="pull-center badge bg-blue dis-block">
                                 <div>
                                 </div>
                                 @if (!currentUser()->hasRole('solicitante'))
                                     {{ $expediente->estado->nombre_estado }}
                                 @else
                                     Revisado
                                 @endif
                             </span>
                         @elseif ($expediente->expestado_id == '3')
                             <span class="pull-center badge bg-red dis-block">
                                 @if ($expediente->exptipoproce_id != '1')
                                     @php $circle=$expediente->getActuacions($expediente->expid); @endphp
                                     <div class="{{ $circle[0] }}">
                                         {{ $circle[1] }}
                                     </div>
                                 @endif

                                 @if (!currentUser()->hasRole('solicitante'))
                                     {{ $expediente->estado->nombre_estado }}
                                 @else
                                     En proceso
                                 @endif
                             </span>
                         @elseif ($expediente->expestado_id == '5')
                             <span class="pull-center badge bg-blue dis-block">
                                 <div>
                                 </div>
                                 @if (!currentUser()->hasRole('solicitante'))
                                     {{ $expediente->estado->nombre_estado }}
                                 @else
                                     Sin revisión
                                 @endif
                             </span>
                         @else
                             <span class="pull-center badge bg-orange dis-block">
                                 <div>
                                 </div>
                                 {{ $expediente->estado->nombre_estado }}
                             </span>
                         @endif
                     </td>
                     <td>
                         @if (!currentUser()->hasRole('secretaria') and !currentUser()->hasRole('solicitante'))
                             @if (currentUser()->hasRole('estudiante') and $expediente->expestado_id == '1')
                                 @if ($expediente->exptipoproce_id == '3')
                                     {!! link_to_route(
                                         'oficio.edit',
                                         $title = 'Editar',
                                         $parameters = $expediente->expid,
                                         $attributes = ['class' => 'btn btn-primary btn-block mt-1 btn-sm btn-edit-le'],
                                     ) !!}
                                 @else
                                     {!! link_to_route(
                                         'expedientes.edit',
                                         $title = 'Editar',
                                         $parameters = $expediente->expid,
                                         $attributes = ['class' => 'btn btn-primary btn-block mt-1 btn-sm btn-edit-le'],
                                     ) !!}
                                 @endif
                             @elseif(
                                 !currentUser()->hasRole('estudiante') and ($expediente->expestado_id == '1' or $expediente->expestado_id == '4') or
                                     $expediente->expestado_id == '3')
                                 @if ($expediente->getAsignacion() == null)
                                     Se debe revisar la asignación
                                 @else
                                     @if ($expediente->exptipoproce_id == '3')
                                         {!! link_to_route(
                                             'oficio.edit',
                                             $title = 'Editar',
                                             $parameters = $expediente->expid,
                                             $attributes = ['class' => 'btn btn-primary btn-block mt-1 btn-sm btn-edit-le'],
                                         ) !!}
                                     @else
                                         {!! link_to_route(
                                             'expedientes.edit',
                                             $title = 'Editar',
                                             $parameters = $expediente->expid,
                                             $attributes = ['class' => 'btn btn-primary btn-block mt-1 btn-sm btn-edit-le'],
                                         ) !!}
                                     @endif
                                 @endif
                             @else
                                 @if ($expediente->exptipoproce_id == '3')
                                     {!! link_to_route(
                                         'oficio.show',
                                         $title = 'Ver',
                                         $parameters = $expediente->expid,
                                         $attributes = ['class' => 'btn btn-primary btn-block mt-1 btn-sm btn-edit-le'],
                                     ) !!}
                                 @else
                                     {!! link_to_route(
                                         'expedientes.show',
                                         $title = 'Ver',
                                         $parameters = $expediente->expid,
                                         $attributes = ['class' => 'btn btn-primary btn-block mt-1 btn-sm btn-edit-le'],
                                     ) !!}
                                 @endif
                             @endif
                         @else
                             @if ($expediente->exptipoproce_id == '3')
                                 {!! link_to_route(
                                     'oficio.show',
                                     $title = 'Ver',
                                     $parameters = $expediente->expid,
                                     $attributes = ['class' => 'btn btn-primary  btn-block mt-1 btn-sm btn-edit-le'],
                                 ) !!}
                             @else
                                 {!! link_to_route(
                                     'expedientes.show',
                                     $title = 'Ver',
                                     $parameters = $expediente->expid,
                                     $attributes = ['class' => 'btn btn-primary  btn-block mt-1 btn-sm btn-edit-le'],
                                 ) !!}
                             @endif
                         @endif


                         <!-- Trigger the modal with a button -->
                         <button type="button" class="btn btn-block mt-1 btn-success btn-sm" data-toggle="modal"
                             data-target="#myModal-{{ $expediente->id }}">Detalles</button>
                         <!-- Modal -->
                         @include('myforms.frm_modal_detalles_expedientes', [
                             'expediente' => $expediente,
                         ])
                     </td>
                 </tr>
             @endforeach
         </tbody>

     </table>
 </div>

 {{ $expedientes->appends(request()->query())->links() }}

 <script>
     (function() {
         element = document.getElementById('badgeCount');

         //element.innerHTML = valor;
     })();

     function convertirMoneda(valor) {
         number = parseInt(valor);
         number = number.toLocaleString('es-ES');
         return number;
     }
 </script>
