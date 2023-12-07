{!! Form::model($expediente, [
    'route' => ['expedientes.update', $expediente->id],
    'method' => 'POST',
    'id' => 'form_expediente_edit',
]) !!}
<div class="shadow">
    @include('myforms.components_exp.frm_datos_gen')
</div>
<!--cont_data_req-->
<div @if (currentUser()->hasRole('estudiante')) id="cont_data_req" @endif>
    <div class="row">
        <div class="col-sm-4">
            {!! Form::label('Identidicación: ') !!}
            <label class="lab-ast-req" title="Campo obligatorio"> * </label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">

                    @if (currentUser()->hasRole('estudiante') and !$readonly)
                        <button value="{{ $expediente->solicitante->idnumber }}" data-tipo_doc="{{ $expediente->solicitante->tipodoc_id }}" type="button" id="btn_exp_user_carga"
                            style="background-color: green" class="btn btn-success" data-toggle='modal'
                            data-target='#myModal_exp_user_edit'>
                            Editar
                        </button>
                    @elseif(!currentUser()->hasRole('estudiante') || $readonly)
                        <button value="{{ $expediente->solicitante->idnumber }}" data-tipo_doc="{{ $expediente->solicitante->tipodoc_id }}" type="button" id="btn_exp_user_carga"
                            style="background-color: green" class="btn btn-success" data-toggle='modal'
                            data-target='#myModal_exp_user_details'>
                            Detalles
                        </button> 
                    @endif

                </div>
                {!! Form::text('expidnumber', $expediente->solicitante->idnumber, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'readonly',
                ]) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Nombres: ') !!}
                {!! Form::text('name', $expediente->solicitante->name, ['class' => 'form-control required', 'readonly']) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Apellidos: ') !!}
                {!! Form::text('lastname', $expediente->solicitante->lastname, ['class' => 'form-control required', 'readonly']) !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Departamento: ') !!}
                <label class="lab-ast-req" title="Campo obligatorio"> * </label>
                {!! Form::select('expdepto_id', $deptos, $expediente->expdepto_id, [
                    'placeholder' => 'Selecciona...',
                    'class' => 'form-control required',
                    'required' => 'required',
                    'data-name' => 'Departamento',
                    $disabled,
                ]) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Municipio: ') !!}
                <label class="lab-ast-req" title="Campo obligatorio"> * </label>

                {!! Form::select('expmunicipio_id', $muncpios, $expediente->expmunicipio_id, [
                    'placeholder' => 'Selecciona...',
                    'class' => 'form-control required',
                    'required' => 'required',
                    'data-name' => 'Municipio',
                    $disabled,
                ]) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Tipo de vivienda: ') !!}
                <label class="lab-ast-req" title="Campo obligatorio"> * </label>

                {!! Form::select('exptipovivien_id', $tipvivienda, $expediente->exptipovivien_id, [
                    'placeholder' => 'Selecciona...',
                    'class' => 'form-control required',
                    'required' => 'required',
                    'data-name' => 'Tipo de vivienda',
                    $disabled,
                ]) !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Personas a cargo: ') !!}
                <label class="lab-ast-req" title="Campo obligatorio"> * </label>
                <select {{$disabled}} name="expperacargo" id="expperacargo" class="form-control required">
                    @php $num=0 @endphp
                    <option value="">Seleccione</option>
                    @while ($num <= 9)
                        <option {{$expediente->expperacargo != $num ?:"selected"}} value="{{ $num }}">{{ $num }}</option>
                        @php $num++ @endphp
                    @endwhile
                    <option {{$expediente->expperacargo != 10 ?:"selected"}}  value="10">10 o más</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Ingreso mensual: ') !!}
                <label class="lab-ast-req" title="Campo obligatorio"> * </label>

                {!! Form::number('expingremensual', null, [
                    'class' => 'form-control required',
                    'max' => '9999999999',
                    'min' => '0',
                    'data-name' => 'Ing. Mensual',
                    $disabled,
                ]) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Egreso mensual: ') !!}
                <label class="lab-ast-req" title="Campo obligatorio"> * </label>
                {!! Form::number('expegremensual', null, [
                    'class' => 'form-control required',
                    'max' => '9999999999',
                    'min' => '0',
                    $disabled,
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
                        @if ($expediente->fechaHistorialDatosCaso(141))
                            Última actualización {{ getSmallDate($expediente->fechaHistorialDatosCaso(141)) }}
                            <a id="modalhcaso" data-name="{{ $expediente->expid }}"
                                style="cursor: pointer; border-bottom:1px solid rgb(206, 206, 206)"> Ver
                                historial</a>
                        @else
                           
                            <span class="badge bg-{{ $expediente->getDaysAfterAsig() > 5 ? 'red' : 'green' }}">
                                {!! $expediente->getTextForTH() !!}
                            </span>
                        @endif
                    </div>
                </div>

                {!! Form::textarea('exphechos', null, [
                    'class' => 'form-control',
                    'maxlength' => '100000',
                    'id' => 'exp_hechos',
                    $disabled,
                ]) !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6" style="padding-left: 0px;">
                        {!! Form::label('Respuesta estudiante: ') !!}
                    </div>
                    <div class="col-md-6" style="padding-left: 0px; text-align:end;">
                        @if ($expediente->fechaHistorialDatosCaso(142))
                            Última actualización {{ getSmallDate($expediente->fechaHistorialDatosCaso(142)) }}
                            <a style="cursor: pointer;border-bottom:1px solid rgb(206, 206, 206)"
                                id="modalresestudiante" data-name="{{ $expediente->expid }}">
                                Ver historial</a>
                        @else
                            Días despues de asignado:
                            <span class="badge bg-{{ $expediente->getDaysAfterAsig() > 5 ? 'red' : 'green' }}">
                                {!! $expediente->getTextForTH() !!}
                            </span>
                        @endif
                    </div>
                </div>
                {!! Form::textarea('exprtaest', null, [
                    'class' => 'form-control',
                    'maxlength' => '100000',
                    'id' => 'exp_resp_est',
                    $disabled,
                ]) !!}
            </div>
        </div>
    </div>
    @if (currentUser()->hasRole('estudiante') and $expediente->expestado_id == '1' || $expediente->expestado_id == '3')
        <div class="row">
            <div class="col-md-12" align="right">
                <div class="form-group">
                    <br />
                    <button id="btn-enviar-dataEst" class="btn btn-primary btn-lg" {{ $disabled }}>
                        <i class="fa fa-save"> </i> Guardar Datos del Caso
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
<!--cont_data_req-->
{!! Form::close() !!}
