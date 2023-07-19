@component('components.b4.modal_large')

    @slot('trigger')
        myModal_act_edit
    @endslot

    @slot('title')
        Editar
    @endslot


    @slot('body')
        {!! Form::open(['id' => 'myform_act_edit', 'files' => true]) !!}
        <div class="row">


            <input type="hidden" id="idact">

            @include('msg.ajax.success')


            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('Código expediente') !!}
                    {!! Form::text('actexpid', $expediente->expid, ['id' => 'actexpid', 'class' => 'form-control', 'readonly']) !!}
                </div>
            </div>

            <div class="col-sm-6">
                {!! Form::label('Fecha creación: ') !!}

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-default">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>
                    {!! Form::text('actfecha_edit', null, [
                        'id' => 'actfecha_edit',
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
                    {!! Form::label('Actuación') !!}
                    {!! Form::text('actnombre', null, [
                        'id' => 'actnombre_edit',
                        'class' => 'form-control required',
                        'maxlength' => '225',
                    ]) !!}
                </div>
            </div>

            @if (currentUser()->hasRole('docente'))
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('fecha_limit', 'Fecha limite de entrega', ['id' => 'lbl_type_act']) !!}
                        {!! Form::date('fecha_limit', null, [
                            'class' => 'form-control required',
                            'maxlength' => '60',
                            'id' => 'fecha_limite',
                        ]) !!}
                    </div>
                </div>
            @endif

            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Descripción: ') !!}
                    {!! Form::textarea('actdescrip', null, [
                        'id' => 'actdescrip_edit',
                        'class' => 'form-control required',
                        'maxlength' => '225',
                        'rows' => 5,
                    ]) !!}
                </div>
            </div>

            <div class="col-md-12">
                {!! form::label('Archivo', 'Cambiar archivo') !!} -
                <strong>
                    <i>
                        <small id="lbl_nom_archivo_est"></small>
                    </i>
                </strong>
                <div class="form-group">
                    {!! form::file('actdocnomgen', null, ['class' => 'form-control required', 'id' => 'actdocnomgen']) !!}
                    <label for="" class="lab_doc_file_est"><i></i></label>
                </div>
            </div>



            <div class="col-md-12">
                <div class="form-group">
                    <br>
                    <button id="myformEditActButton" class="btn btn-primary btn-sm" type="button">
                        Actualizar actuación
                    </button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    @endslot
@endcomponent
<!-- /modal -->
