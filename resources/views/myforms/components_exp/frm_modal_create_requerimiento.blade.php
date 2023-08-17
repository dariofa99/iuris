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
                     {!! Form::textarea('reqdescrip',null,['class' => 'form-control required', 'maxlength' => '900'],
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

