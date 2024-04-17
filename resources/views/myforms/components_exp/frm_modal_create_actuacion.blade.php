@component('components.b4.modal_medium')

    @slot('trigger')
        myModal_act_create
    @endslot

    @slot('title')
        <label id="lbl_title_fract"></label>
    @endslot


    @slot('body')
      

        {!! Form::open(['method' => 'post', 'id' => 'myformCreateAct']) !!}
        <div class="row">



            <div class="form-group">
                {!! Form::hidden('actestado_id', '101', ['id' => 'actestado_id', 'class' => 'form-control', 'readonly']) !!}
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('Código expediente') !!}
                    {!! Form::text('actexpid', $expediente->expid, ['id' => 'actexpid', 'class' => 'form-control', 'readonly']) !!}
                </div>
            </div>




            <!-- 					<div class="col-md-6">
                    <div class="form-group">
                     {!! Form::label('Estado de la actuación') !!}
                     {!! Form::select(
                         'expestado_id',
                         [
                             '1' => 'Enviado a revisión',
                             '2' => 'Solicitud de modificaciones',
                             '3' => 'Enviado con correcciones',
                             '4' => 'Aprobado',
                         ],
                     
                         null,
                         ['placeholder' => 'Selecciona...', 'class' => 'form-control', 'readonly'],
                     ) !!}
                    </div>
                   </div> -->


            <div class="col-md-6">
                {!! Form::label('Fecha: ') !!}

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-default">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>
                    {!! Form::text('actfecha', fechaActual(), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'data-inputmask' => "'alias': 'yyyy/mm/dd'",
                        'data-mask',
                        'readonly',
                    ]) !!}
                </div>
                <!-- /.input group -->
            </div>






            <div class="col-md-12">
                <div class="form-group">
                    <label id="lbl_type_actuacion">Nueva actuación</label>
                    {!! Form::text('actnombre', null, ['class' => 'form-control required', 'maxlength' => '60']) !!}
                </div>
            </div>
            @if (currentUser()->hasRole('docente'))
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('fecha_limit', 'Fecha limite de entrega', ['id' => 'fecha']) !!}
                        {!! Form::date('fecha_limit', null, ['class' => 'form-control required', 'maxlength' => '60']) !!}
                    </div>
                </div>
            @endif

            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Descripción: ') !!}
                    {!! Form::textarea('actdescrip', null, ['class' => 'form-control required', 'maxlength' => '2000', 'rows' => 5]) !!}
                </div>
            </div>
            <div class="col-md-12">
                {!! form::label('Archivo', 'Subir archivo') !!}
                <div class="form-group">
                    {!! form::file('actdocnomgen', null, ['class' => 'form-control required', 'id' => 'actdocnomgen','required']) !!}
                    {!! form::hidden('actdocnompropio', '.', ['class' => 'form-control']) !!}
                    {!! form::hidden('actdocruta', '.', ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <br>
                    <button id="myformCreateActButton" class="btn btn-primary btn-sm" type="button">
                        Crear actuación
                    </button>

                </div>
            </div>
        </div>
        {!! Form::close() !!}
    @endslot
@endcomponent
<!-- /modal -->
