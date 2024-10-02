@component('components.b4.modal_medium')
    @slot('trigger')
        myModal_act_add_revision
    @endslot

    @slot('title')
        <label class="lab_id_act"> Agregando Corrección a Actuación: </label>
    @endslot


    @slot('body')
        {!! Form::open([
            'route' => 'actuaciones.store',
            'method' => 'post',
            'id' => 'myformAddActuacion',
            'files' => true,
        ]) !!}
        <div class="row">

            <input type="hidden" name="parent_actuacion_id" value="" id="parent_actuacion_id">
            <input type="hidden" name="act_id" value="" id="act_id">
            {!! Form::hidden('actestado_id', '101', ['id' => 'actestado_id2', 'class' => 'form-control', 'readonly']) !!}

            @section('msg-contenido')
                Registrado
            @endsection
            @include('msg.ajax.success')

            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('Código expediente') !!}
                    {!! Form::text('actexpid', $expediente->expid, ['id' => 'actexpid', 'class' => 'form-control', 'readonly']) !!}
                </div>
            </div>

            <div class="col-sm-6">
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
                        'readonly',
                    ]) !!}
                </div>

                <!-- /.input group -->
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label id="lbl_type_actadd">Actuación</label>
                    {!! Form::text('actnombre', null, [
                        'class' => 'form-control required',
                        'maxlength' => '60',
                        'id' => 'actnombre',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Descripción: ') !!}
                    {!! Form::textarea('actdescrip', null, ['class' => 'form-control required', 'maxlength' => '2000', 'rows' => 5]) !!}
                </div>
            </div>
            <div class="col-md-12">
                {!! form::label('Archivo', 'Subir archivo') !!}
                <div class="form-group">
                    {!! form::file('actdocnomgen', null, ['class' => 'form-control required', 'id' => 'actdocnomgen']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <br>
                    <button id="myformCreateCorreccionActButton" class="btn btn-primary btn-sm" type="button">
                        Agregar corrección
                    </button>
                    {{--    {!! link_to(
                        '#',
                        'Agregar Corrección',
                        $attributes = [
                            'id' => 'btn_enviar_actuacion',
                            'type' => 'button',
                            'class' => 'btn btn-primary',
                            'onclick' => 'storeActuacion("myformAddActuacion")',
                        ],
                        $secure = null,
                    ) !!} --}}
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    @endslot
@endcomponent
<!-- /modal -->
