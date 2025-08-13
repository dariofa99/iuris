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



    @include('myforms.components_exp.usuarios_caso')




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
                <select {{ $disabled }} name="expperacargo" id="expperacargo" class="form-control required">
                    @php $num=0 @endphp
                    <option value="">Seleccione</option>
                    @while ($num <= 9)
                        <option {{ $expediente->expperacargo != $num ?: 'selected' }} value="{{ $num }}">
                            {{ $num }}</option>
                        @php $num++ @endphp
                    @endwhile
                    <option {{ $expediente->expperacargo != 10 ?: 'selected' }} value="10">10 o más</option>
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
                        <a class="btn_historial" id="modalhcaso" data-name="{{ $expediente->expid }}"
                                style="cursor: pointer; border-bottom:1px solid rgb(206, 206, 206)"> Ver
                                historial</a>
                        @if ($expediente->fechaHistorialDatosCaso(141))
                            Última actualización {{ getSmallDate($expediente->fechaHistorialDatosCaso(141)) }}
                            
                        @elseif($expediente->expestado_id == 6)
                            <span class="badge bg-green">
                                El caso esta en pausa.
                            </span>
                        @else
                            <span class="badge bg-{{ $expediente->getTextForTH('dias') > 5 ? 'red' : 'green' }}">
                                {!! $expediente->getDaysForEvaHechos() !!}
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
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6" style="padding-left: 0px;">
                        {!! Form::label('Respuesta estudiante: ') !!}
                    </div>
                    <div class="col-md-6" style="padding-left: 0px; text-align:end;">
                        <a class="btn_historial" style="cursor: pointer;border-bottom:1px solid rgb(206, 206, 206)" id="modalresestudiante"
                            data-name="{{ $expediente->expid }}">
                            Ver historial</a>
                        @if ($expediente->fechaHistorialDatosCaso(142))
                            Última actualización {{ getSmallDate($expediente->fechaHistorialDatosCaso(142)) }}
                        @elseif($expediente->expestado_id == 6)
                            <span class="badge bg-green">
                                El caso esta en pausa.
                            </span>
                        @else
                            {{-- Días despues de asignado: --}}
                            <span class="badge bg-{{ $expediente->getTextForTH('dias') > 5 ? 'red' : 'green' }}">
                                {!! $expediente->getDaysForEvaHechos() !!}
                            </span>
                        @endif
                    </div>
                </div>




                <textarea {{ $disabled }} name="exprtaest" maxlength="10000" class="form-control" id="exp_resp_est" cols="30"
                    rows="10">
@if ($expediente->historialHechosRespuesta()->where('hisdc_tipo_datos_caso', 142)->where('hisdc_idnumberest_id', $expediente->expidnumberest)->get()->isNotEmpty())
{{ old('exprtaest', $expediente->exprtaest) }}
@endif
</textarea>

            </div>
        </div>
    </div>
    @if (currentUser()->hasRole('estudiante') and
            $expediente->expestado_id == '1' || $expediente->expestado_id == '3' || $expediente->expestado_id == '6')
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
