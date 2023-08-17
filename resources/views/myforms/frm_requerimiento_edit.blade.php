@component('components.b4.modal_large')
    @slot('trigger')
        myModal_req_edit
    @endslot

    @slot('title')
        Editar
    @endslot


    @slot('body')
        {!! Form::open(['route' => 'requerimientos.store', 'method' => 'post', 'id' => 'myform_req_edit']) !!}
        <div class="row">


            {!! Form::hidden('id', '.', ['id' => 'reqid', 'class' => 'form-control form-control-sm', 'readonly']) !!}
           

            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('Código expediente') !!}
                    {!! Form::text('reqexpid', $expediente->expid, ['id' => 'actexpid', 'class' => 'form-control form-control-sm', 'readonly']) !!}
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
                    {!! Form::date('reqcreated_at', null, [
                        'class' => 'form-control form-control-sm',
                        'required' => 'required',
                        'readonly','disabled',
                        'id' => 'reqcreated_at',
                    ]) !!}
                </div>
                <!-- /.input group -->
            </div>



            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('Cédula: ') !!}
                    {!! Form::text('reqidsolicitan', $expediente->solicitante->idnumber, [
                        'class' => 'form-control form-control-sm',
                        'required' => 'required',
                        'readonly',
                        'id' => 'cedula',
                    ]) !!}
                </div>
            </div>



            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('Nombres: ') !!}
                    {!! Form::text('name', $expediente->solicitante->name, ['class' => 'form-control form-control-sm', 'readonly', 'id' => 'name']) !!}
                </div>
            </div>




            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('Apellidos: ') !!}
                    {!! Form::text('lastname', $expediente->solicitante->lastname, [
                        'class' => 'form-control form-control-sm',
                        'readonly',
                        'id' => 'lastname',
                    ]) !!}
                </div>
            </div>



            <div class="col-md-6">
                {!! Form::label('Fecha citación: ') !!}
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>
                    {!! Form::date('reqfecha', null, [
                        'class' => 'form-control form-control-sm',
                        'id' => 'reqfecha_ed',
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
                            <input type="time" class="form-control form-control-sm timepicker" id="reqhora_ed" name="reqhora">

                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                        <!-- /.input group -->
                    </div>
                    <!-- /.form group -->
                </div>
            </div>




            <!--  <input type="time" value="" id="reqfechahoracomp"> -->



            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Motivo') !!}
                    {!! Form::text('reqmotivo', null, [
                        'class' => 'form-control form-control-sm required',
                        'id' => 'reqmotivo',
                        'maxlength' => '95',
                    ]) !!}
                </div>
            </div>




            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Descripción: ') !!}
                    {!! Form::textarea('reqdescrip', null, [
                        'class' => 'form-control form-control-sm required',
                        'id' => 'reqdescrip',
                        'maxlength' => '700',
                    ]) !!}
                </div>
            </div>





            <div class="col-md-12">
                <div class="form-group">
                    <br>
                    {!! link_to(
                        '#',
                        'Actualizar',
                        ['id' => 'btn_act_req', 
						'type' => 'button', 'class' => 'btn btn-warning']
                    ) !!}
                </div>
            </div>


        </div>

        {!! Form::close() !!}
    @endslot
@endcomponent
<!-- /modal -->
