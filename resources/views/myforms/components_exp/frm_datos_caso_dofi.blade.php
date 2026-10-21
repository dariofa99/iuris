<form action="{{ route('expedientes.update', $expediente->id) }}" method="POST" id="form_expediente_edit">
    @csrf
    @method('PUT')

    <div class="shadow">
        @include('myforms.components_exp.frm_datos_gen')
    </div>

    <div @if (currentUser()->hasRole('estudiante')) id="cont_data_req" @endif>
        <div class="shadow">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="expidnumber">Identificación</label>
                        <label class="lab-ast-req" title="Campo obligatorio">*</label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                @if (currentUser()->hasRole('diradmin') || currentUser()->hasRole('estudiante') and !$readonly)
                                    <button value="{{ $expediente->solicitante->idnumber }}"
                                        data-tipo_doc="{{ $expediente->solicitante->tipodoc_id }}" type="button"
                                        id="btn_exp_user_carga" class="btn btn-iuris-success" data-toggle="modal"
                                        data-target="#myModal_exp_user_edit">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                        Editar
                                    </button>
                                @elseif (!currentUser()->hasRole('estudiante') || $readonly)
                                    <button value="{{ $expediente->solicitante->idnumber }}"
                                        data-tipo_doc="{{ $expediente->solicitante->tipodoc_id }}" type="button"
                                        id="btn_exp_user_carga" class="btn btn-iuris-success" data-toggle="modal"
                                        data-target="#myModal_exp_user_details">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        Detalles
                                    </button>
                                @endif
                            </div>

                            <input type="text" name="expidnumber" id="expidnumber" class="form-control"
                                value="{{ old('expidnumber', $expediente->solicitante->idnumber) }}" required readonly
                                {{ $disabled }}>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Nombres</label>
                        <label class="lab-ast-req" title="Campo obligatorio">*</label>
                        <input type="text" name="name" id="name" class="form-control required"
                            value="{{ old('name', $expediente->solicitante->name) }}" readonly {{ $disabled }}>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="lastname">Apellidos</label>
                        <label class="lab-ast-req" title="Campo obligatorio">*</label>
                        <input type="text" name="lastname" id="lastname" class="form-control required"
                            value="{{ old('lastname', $expediente->solicitante->lastname) }}" readonly
                            {{ $disabled }}>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="expdepto_id">Departamento</label>
                        <label class="lab-ast-req" title="Campo obligatorio">*</label>
                        <select name="expdepto_id" id="expdepto_id" class="form-control required" required
                            onblur="comprDato('form_expediente_user_edit')" data-name="Departamento"
                            {{ $disabled }}>
                            <option value="">Selecciona...</option>
                            @foreach ($deptos as $id => $departamento)
                                <option value="{{ $id }}"
                                    {{ (string) old('expdepto_id', $expediente->expdepto_id) === (string) $id ? 'selected' : '' }}>
                                    {{ $departamento }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="expmunicipio_id">Municipio</label>
                        <label class="lab-ast-req" title="Campo obligatorio">*</label>
                        <select name="expmunicipio_id" id="expmunicipio_id" class="form-control required" required
                            onblur="comprDato('form_expediente_user_edit')" data-name="Municipio" {{ $disabled }}>
                            <option value="">Selecciona...</option>
                            @foreach ($muncpios as $id => $municipio)
                                <option value="{{ $id }}"
                                    {{ (string) old('expmunicipio_id', $expediente->expmunicipio_id) === (string) $id ? 'selected' : '' }}>
                                    {{ $municipio }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="expjuzoent">Juzgado o entidad</label>
                        <input type="text" name="expjuzoent" id="expjuzoent" class="form-control"
                            value="{{ old('expjuzoent', $expediente->expjuzoent) }}" maxlength="120"
                            {{ $disabled }}>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="expnumproc">Número de proceso</label>
                        <input type="text" name="expnumproc" id="expnumproc" class="form-control"
                            value="{{ old('expnumproc', $expediente->expnumproc) }}" {{ $disabled }}>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="exppersondemandante">Persona demandante</label>
                        <input type="text" name="exppersondemandante" id="exppersondemandante"
                            class="form-control"
                            value="{{ old('exppersondemandante', $expediente->exppersondemandante) }}"
                            {{ $disabled }}>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="exppersondemandada">Persona demandada</label>
                        <input type="text" name="exppersondemandada" id="exppersondemandada" class="form-control"
                            value="{{ old('exppersondemandada', $expediente->exppersondemandada) }}"
                            {{ $disabled }}>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                            <label for="exp_hechos" class="mb-1">Hechos</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                            <span class="text-muted small">
                                @if ($expediente->fechaHistorialDatosCaso(141))
                                    Última actualización: {{ getSmallDate($expediente->fechaHistorialDatosCaso(141)) }}
                                @else
                                    Días desde la asignación:
                                    <span
                                        class="badge badge-{{ $expediente->getDaysAfterAsig() > 5 ? 'danger' : 'success' }}">
                                        {{ $expediente->getDaysAfterAsig() }}
                                    </span>
                                @endif
                            </span>

                            <a class="btn btn-sm btn-outline-secondary btn_historial" id="modalhcaso"
                                data-name="{{ $expediente->expid }}" role="button">
                                <i class="fa fa-history" aria-hidden="true"></i>
                                Ver historial
                            </a>
                        </div>

                        <textarea {{ $disabled }} name="exphechos" maxlength="10000" class="form-control" id="exp_hechos"
                            rows="10">
@if ($expediente->historialHechosRespuesta()->where('hisdc_tipo_datos_caso', 141)->where('hisdc_idnumberest_id', $expediente->expidnumberest)->get()->isNotEmpty())
{{ old('exphechos', $expediente->exphechos) }}
@endif
</textarea>
                    </div>
                </div>
            </div>
        </div>

        @if (currentUser()->hasRole('estudiante') and
                $expediente->expestado_id == 1 || $expediente->expestado_id == 3 || $expediente->expestado_id == 6)
            <div class="iuris-form-footer">
                <span class="iuris-required">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                    Revise los datos del caso antes de guardar.
                </span>

                <button id="btn-enviar-dataEst" type="submit" class="btn btn-iuris-primary">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    Guardar datos del caso
                </button>
            </div>
        @endif
    </div>
</form>
