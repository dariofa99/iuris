 <!-- modal -->

 @if (!$readonly and $expediente->expestado_id != '2' and $expediente->expestado_id != '4'
 && currentUser()->can("crear_requerimiento"))
     <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal_req_create"
         id="btn_modal_req">Nueva cita/requerimiento</button>
 @endif 
 <!-- /modal -->

 <div class="col-md-12">
     <br>
 </div>

 <table id="tbl_ajax_req" class="table table-bordered table-striped dataTable" role="grid">
     <thead>
         <tr>
             <th>Fecha creación</th>
             <th>Motivo</th>
             <th>Fecha Cita</th>
             <th>Hora Cita</th>
             <th>Asistencia</th>
             <th>Estado</th>
             {{-- <th>Evaluado</th> --}}
             <th>Acción</th>
         </tr>
     </thead>
     <tbody id="datos_req">
         @foreach ($expediente->requerimientos as $key => $req)
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
                     {{ getSmallDate($req->reqfecha) }}
                 </td>
                 <td>
                     {{ $req->reqhora }}
                 </td>
                 <td>
                     {{ $req->req_asistencia->ref_reqasistencia }}
                 </td>
                 <td>
                     {{ $req->reqentregado ? 'Entregado' : 'Sin entregar' }}
                 </td>
                 {{--  <td>
                     {{ $label }}
                 </td> --}}

                 <td>
                     @if (!$readonly)
                         @if (
                             (currentUser()->hasRole('estudiante') and !$req->reqentregado) ||
                                 (currentUser()->hasRole('diradmin') || currentUser()->hasRole('amatai')))
                             <a href='#' data-id="{{ $req->id }}" data-modal='#myModal_req_edit'
                                 class='btn btn-primary btn-sm btn-block btn_editar_req' role='button'>
                                 Editar
                             </a>


                             <a href='#' data-id="{{ $req->id }}"
                                 class='btn btn-danger btn_delete_requerimiento btn-sm btn-block' role='button'>
                                 Eliminar
                             </a>
                         @endif

                         @if (currentUser()->hasRole('coordprac') ||
                                 currentUser()->hasRole('amatai') ||
                                 (currentUser()->hasRole('estudiante') and $expediente->expidnumberest == currentUser()->idnumber) ||
                                 $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber)
                             <button href='#' {{ $estadoBtn }} data-id="{{ $req->id }}"
                                 data-modal='#myModal_req_asist' class='btn btn-info btn-sm btn-block btn_editar_req'
                                 role='button'>
                                 {{ $label }}
                             </button>
                         @endif

                         @if (
                             !$req->evaluado and
                                 currentUser()->hasRole('amatai') || currentUser()->hasRole('secretaria') || currentUser()->hasRole('coordprac'))
                             <a href='#' data-id="{{ $req->id }}" data-estado="{{ $req->reqentregado }}"
                                 class='btn btn-primary btn-sm btn-block btn_cambiar_estado_requerimiento'
                                 role='button'>
                                 {{ $req->reqentregado ? 'Marcar como no entregado' : 'Marcar como entregado' }}
                             </a>
                         @endif
                     @endif

                     <a href="{{ url('/reqpdfgen', $req->id) }}" target='_blank'
                         class='btn btn-warning btn-sm btn-block' role='button'>
                         Imprimir
                     </a>


                     <a href='#' data-id="{{ $req->id }}" data-modal='#myModal_req_details'
                         class='btn_editar_req btn btn-success btn-sm btn-block' role='button'>
                         Detalles
                     </a>
                 </td>
             </tr>
         @endforeach
     </tbody>
 </table>
