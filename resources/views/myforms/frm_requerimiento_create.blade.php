 <!-- modal -->

 @if ($expediente->expestado_id != '2' and $expediente->expestado_id != '4')
     <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal_req_create"
         id="btn_modal_req">Nueva cita/req.</button>
 @endif

 @component('components.b4.modal_large')
     @slot('trigger')
         myModal_req_create
     @endslot

     @slot('title')
         <label id="lbl_title_fract"></label>
     @endslot


     @slot('body')
         {!! Form::open(['route' => 'requerimientos.store', 'method' => 'post', 'id' => 'myform_req']) !!}
         <div class="row">

             {!! Form::hidden('id_control_list_req', $expediente->expid, [
                 'id' => 'id_control_list_req',
                 'class' => 'form-control',
                 'readonly',
             ]) !!}


             {!! Form::hidden('reqidest', $expediente->estudiante->idnumber, [
                 'id' => 'id_control_list',
                 'class' => 'form-control',
                 'readonly',
             ]) !!}

             <div class="col-md-6">
                 <div class="form-group">
                     {!! Form::label('Código expediente') !!}
                     {!! Form::text('reqexpid', $expediente->expid, ['id' => 'actexpid', 'class' => 'form-control', 'readonly']) !!}
                 </div>
             </div>
             <div class="col-sm-6">
                 {!! Form::label('Fecha: ') !!}

                 <div class="input-group mb-3">
                     <div class="input-group-prepend">
                         <span class="input-group-text" id="basic-addon1">
                             <i class="fa fa-calendar"></i>
                         </span>
                     </div>
                     {!! Form::date('reqfecha', fechaActual(), [
                         'class' => 'form-control',
                         'required' => 'required',
                         'readonly',
                     ]) !!}
                 </div>
             </div>
             <div class="col-md-12">
                 <div class="form-group">
                     {!! Form::label('Cédula: ') !!}
                     {!! Form::text('reqidsolicitan', $expediente->solicitante->idnumber, [
                         'class' => 'form-control',
                         'required' => 'required',
                         'readonly',
                     ]) !!}
                 </div>
             </div>
             <div class="col-md-6">
                 <div class="form-group">
                     {!! Form::label('Nombres: ') !!}
                     {!! Form::text('name', $expediente->solicitante->name, ['class' => 'form-control', 'readonly']) !!}
                 </div>
             </div>
             <div class="col-md-6">
                 <div class="form-group">
                     {!! Form::label('Apellidos: ') !!}
                     {!! Form::text('lastname', $expediente->solicitante->lastname, ['class' => 'form-control', 'readonly']) !!}
                 </div>
             </div>
             <div class="col-md-6">
                 {!! Form::label('Fecha citación: ') !!}

                 <div class="input-group mb-3">
                     <div class="input-group-prepend">
                         <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                     </div>
                     {!! Form::date('reqfecha',null, [
                         'class' => 'form-control required',
                         'id' => 'reqfecha',
                         'required' => 'required',
                         
                     ]) !!}
                 </div>
                 <!-- /.input group -->
             </div>
             <div class="col-md-6">
                 <div class="bootstrap-timepicker">
                     <div class="bootstrap-timepicker-widget dropdown-menu">
                         <table>
                             <tbody>
                                 <tr>
                                     <td><a href="#" data-action="incrementHour"><i
                                                 class="glyphicon glyphicon-chevron-up"></i></a></td>
                                     <td class="separator">&nbsp;</td>
                                     <td><a href="#" data-action="incrementMinute"><i
                                                 class="glyphicon glyphicon-chevron-up"></i></a></td>
                                     <td class="separator">&nbsp;</td>
                                     <td class="meridian-column"><a href="#" data-action="toggleMeridian"><i
                                                 class="glyphicon glyphicon-chevron-up"></i></a></td>
                                 </tr>
                                 <tr>
                                     <td><span class="bootstrap-timepicker-hour">04</span></td>
                                     <td class="separator">:</td>
                                     <td><span class="bootstrap-timepicker-minute">15</span></td>
                                     <td class="separator">&nbsp;</td>
                                     <td><span class="bootstrap-timepicker-meridian">PM</span></td>
                                 </tr>
                                 <tr>
                                     <td><a href="#" data-action="decrementHour"><i
                                                 class="glyphicon glyphicon-chevron-down"></i></a></td>
                                     <td class="separator"></td>
                                     <td><a href="#" data-action="decrementMinute"><i
                                                 class="glyphicon glyphicon-chevron-down"></i></a></td>
                                     <td class="separator">&nbsp;</td>
                                     <td><a href="#" data-action="toggleMeridian"><i
                                                 class="glyphicon glyphicon-chevron-down"></i></a></td>
                                 </tr>
                             </tbody>
                         </table>
                     </div>
                     <div class="form-group">
                         <label>Hora citación:</label>

                         <div class="input-group">
                             <input type="time" class="form-control timepicker" id="reqhora" name="reqhora">

                             <div class="input-group-addon">
                                 <i class="fa fa-clock-o"></i>
                             </div>
                         </div>
                         <!-- /.input group -->
                     </div>
                     <!-- /.form group -->
                 </div>
             </div>
             <div class="col-md-12">
                 <div class="form-group">
                     {!! Form::label('Motivo') !!}
                     {!! Form::text('reqmotivo', null, ['class' => 'form-control required', 'maxlength' => '95']) !!}
                 </div>
             </div>
             <div class="col-md-12">
                 <div class="form-group">
                     {!! Form::label('Descripción: ') !!}
                     {!! Form::textarea(
                         'reqdescrip',
                         "Por medio de la presente me permito requerirlo para seguir	con el asunto radicado bajo el código: {$expediente->expid} que Usted presentó ante Consultorios Jurídicos de la Universidad Nariño. Por lo anterior, le solicito asistir a las instalaciones de Consultorios Jurídicos ubicados en la Calle 19 con Carrera 22 esquina, en la fecha indicada. En caso de no presentarse a esta citación se procederá al cierre y archivo del caso, advirtiéndole que Usted podrá acercarse nuevamente a presentar su asunto y será designado a un nuevo estudiante.	",
                     
                         ['class' => 'form-control required', 'maxlength' => '700'],
                     ) !!}
                 </div>
             </div>
             <div class="col-md-12">
                 <div class="form-group">
                     <br>
                     {!! link_to(
                         '#',
                         'Nuevo',
                         $attributes = ['id' => 'btn_enviar_req', 'type' => 'button', 'class' => 'btn btn-primary'],
                         $secure = null,
                     ) !!}
                 </div>
             </div>

         </div>
         {!! Form::close() !!}
     @endslot
 @endcomponent





 <!-- /modal -->
 @include('myforms.frm_requerimiento_edit')
 @include('myforms.frm_requerimiento_asist')
 @include('myforms.frm_requerimiento_details')









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
             <th>Evaluado</th>
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
                 <td>
                     {{ $label }}
                 </td>
                
                 <td>
                     @if (currentUser()->hasRole('estudiante') and !$req->reqentregado)
                         <a href='#' data-id="{{ $req->id }}" data-modal='#myModal_req_edit'
                             class='btn btn-primary btn-sm btn-block btn_editar_req' role='button'>
                             Editar
                         </a>


                         <a href='#' data-id="{{ $req->id }}"
                             class='btn btn-danger btn_delete_requerimiento btn-sm btn-block' role='button'>
                             Eliminar
                         </a>
                     @endif

                     @if (currentUser()->hasRole('coordprac') 
                     || currentUser()->hasRole('amatai') 
                     || (currentUser()->hasRole('estudiante') and $expediente->expidnumberest  == currentUser()->idnumber )
					 || ($expediente->getDocenteAsig()->idnumber == currentUser()->idnumber))
                         <button href='#' {{ $estadoBtn }} data-id="{{ $req->id }}"
                             data-modal='#myModal_req_asist' class='btn btn-info btn-sm btn-block btn_editar_req'
                             role='button'>
                             {{ $label }}
                         </button>
                     @endif

                     @if (!$req->evaluado and (currentUser()->hasRole('amatai') ||
                             currentUser()->hasRole('secretaria') ||
                             currentUser()->hasRole('coordprac')))
                         <a href='#' data-id="{{ $req->id }}" data-estado="{{ $req->reqentregado }}"
                             class='btn btn-primary btn-sm btn-block btn_cambiar_estado_requerimiento' role='button'>
                             {{ $req->reqentregado ? 'Marcar como no entregado' : 'Marcar como entregado' }}
                         </a>
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
