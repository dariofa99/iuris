{!! Form::model($expediente, [
    'route' => ['expedientes.update', $expediente->id],
    'method' => 'PUT',
    'id' => 'form_expediente_edit',
]) !!}
<div class="shadow">
    @include('myforms.components_exp.frm_datos_gen')


</div>
<!--cont_data_req-->
<div @if (currentUser()->hasRole('estudiante')) id="cont_data_req" @endif>
    <div class="row">
        <div class="col-md-4">
            {!! Form::label('Identidicación: ') !!}
            <label class="lab-ast-req" title="Campo obligatorio"> * </label>

            <div class="input-group">

                <div class="input-group-btn">
                    @if ((currentUser()->hasRole('diradmin') || currentUser()->hasRole('estudiante'))  and !$readonly)
                        <button value="{{ $expediente->solicitante->idnumber }}"
                            data-tipo_doc="{{ $expediente->solicitante->tipodoc_id }}" type="button"
                            id="btn_exp_user_carga" style="background-color: green" class="btn btn-success"
                            data-toggle='modal' data-target='#myModal_exp_user_edit'>
                            Editar
                        </button>
                    @elseif(!currentUser()->hasRole('estudiante') || $readonly)
                        <button value="{{ $expediente->solicitante->idnumber }}"
                            data-tipo_doc="{{ $expediente->solicitante->tipodoc_id }}" type="button"
                            id="btn_exp_user_carga" style="background-color: green" class="btn btn-success"
                            data-toggle='modal' data-target='#myModal_exp_user_details'>
                            Detalles
                        </button>
                    @endif

                </div>
                <!-- /btn-group -->
                {!! Form::text('expidnumber', $expediente->solicitante->idnumber, [
                    $disabled,
                    'class' => 'form-control',
                    'required' => 'required',
                    'readonly',
                ]) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Nombres: ') !!}
                <label class="lab-ast-req" title="Campo obligatorio"> * </label>
                {!! Form::text('name', $expediente->solicitante->name, [
                    $disabled,
                    'class' => 'form-control required',
                    'readonly',
                ]) !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Apellidos: ') !!}
                <label class="lab-ast-req" title="Campo obligatorio"> * </label>
                {!! Form::text('lastname', $expediente->solicitante->lastname, [
                    $disabled,
                    'class' => 'form-control required',
                    'readonly',
                ]) !!}
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Departamento: ') !!}
                <label class="lab-ast-req" title="Campo obligatorio"> * </label>

                {!! Form::select('expdepto_id', $deptos, $expediente->expdepto_id, [
                    $disabled,
                    'placeholder' => 'Selecciona...',
                    'class' => 'form-control required',
                    'required' => 'required',
                    'onblur' => 'comprDato("form_expediente_user_edit")',
                    'data-name' => 'Departamento',
                ]) !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Municipio: ') !!}
                <label class="lab-ast-req" title="Campo obligatorio"> * </label>

                {!! Form::select('expmunicipio_id', $muncpios, $expediente->expmunicipio_id, [
                    $disabled,
                    'placeholder' => 'Selecciona...',
                    'class' => 'form-control required',
                    'required' => 'required',
                    'onblur' => 'comprDato("form_expediente_user_edit")',
                    'data-name' => 'Municipio',
                ]) !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Juzgado o entidad: ') !!}
                {!! Form::text('expjuzoent', null, ['class' => 'form-control', 'maxlength' => '120', $disabled]) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Número de proceso ') !!}
                {!! Form::text('expnumproc', null, ['class' => 'form-control', $disabled]) !!}
            </div>
        </div>



        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Persona Demandante') !!}
                {!! Form::text('exppersondemandante', null, ['class' => 'form-control', $disabled]) !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Persona Demandada') !!}
                {!! Form::text('exppersondemandada', null, ['class' => 'form-control', $disabled]) !!}
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6" style="padding-left: 0px;">
                        {!! Form::label('Hechos: ') !!}
                    </div>
                    <div class="col-md-6" style="padding-left: 0px; text-align:end;">
                         <a class="btn_historial" id="modalhcaso" data-name="{{ $expediente->expid }}"
                                style="cursor: pointer; border-bottom:1px solid rgb(206, 206, 206)"> Ver
                                historial</a>
                        @if ($expediente->fechaHistorialDatosCaso(141))
                            Última actualización {{ getSmallDate($expediente->fechaHistorialDatosCaso(141)) }}
                           
                        @else
                            Días despues de asignado
                            <span class="badge bg-{{ $expediente->getDaysAfterAsig() > 5 ? 'red' : 'green' }}">
                                {{ $expediente->getDaysAfterAsig() }}
                            </span>
                        @endif
                    </div>
                </div>
                     <textarea {{ $disabled }} name="exphechos" maxlength="10000" class="form-control" id="exp_hechos" cols="30"
                    rows="10">
@if ($expediente->historialHechosRespuesta()->where('hisdc_tipo_datos_caso', 141)->where('hisdc_idnumberest_id', $expediente->expidnumberest)->get()->isNotEmpty())
{{ old('exphechos', $expediente->exphechos) }}
@endif
</textarea>
              
            </div>
        </div>
    </div>
    @if (currentUser()->hasRole('estudiante') and $expediente->expestado_id == 1 || $expediente->expestado_id == 3 || $expediente->expestado_id == 6)
        <div class="row">
            <div class="col-md-12" align="right">
                <div class="form-group">
                    <br />
                    <button id="btn-enviar-dataEst" class="btn btn-primary btn-lg">
                        <i class="fa fa-save"> </i> Guardar Datos del Caso
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
<!--cont_data_req-->
{!! Form::close() !!}
