<div class="row">
    <input type="hidden" value="{{ $expediente->id }}" id="expediente_id" name="expediente_id">
    @if ($expediente->getAsignacion())
        <input type="hidden" value="{{ $expediente->getAsignacion()->fecha_asig }}" id="expediente_fecha_asig"
            name="fecha_asig">
        <input type="hidden" value="{{ $expediente->getAsignacion()->id }}" id="id_asig" name="id_asig">
    @endif
    @if (!currentUser()->hasRole('estudiante'))
        <div class="col-md-4">
            <div class="form-group">
                <label>Estudiante asignado</label>
                <input id="inputestudianteasignado" readonly type="text"
                    value="{{ $expediente->estudiante->name }} {{ $expediente->estudiante->lastname }}"
                    class="form-control">
                <div id="contselecestcasos" style="display: none">
                    <select data-live-search="true" disabled required id="selectexpidnumberest"
                        class="required form-control disabled-fun3 selectpicker">
                        @foreach ($estudiantes as $key => $estudiante)
                            <option {{ $expediente->expidnumberest != $estudiante['idnumber'] ?: 'selected' }}
                                value="{{ $estudiante['idnumber'] }}">
                                {{ $estudiante['full_name'] }}
                            </option>
                        @endforeach
                    </select>

                    <input type="hidden" name="oldexpidnumberest" value="{{ $expediente->expidnumberest }}"
                        id="oldexpidnumberest">
                    <input type="hidden" name="expidnumberest" id="idnumberest" disabled>

                </div>
              
                    <b><small>Cédula: {{ $expediente->estudiante->idnumber }} </small></b>

            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label>Curso</label><br>
                @if ($expediente->estudiante->curso->id != 1 and $expediente->estudiante->turno)
                    <label style="margin-left:8px; padding:2px 4px 2px 4px;border-radius:5px; font-size:13px"
                        class="label {{ $expediente->getColorTurno($expediente->estudiante->turno->color->ref_value) }}">

                        {{ $expediente->estudiante->curso->ref_nombre }}
                        @if ($expediente->estudiante->turno)
                            {{ $expediente->getMjs($expediente->estudiante->turno->horario->ref_value) }}
                        @endif
                    </label>
                @else
                    <label style="margin-left:8px;" class="label bg-orange">
                        Sin curso asignado
                    </label>
                @endif
            </div>
        </div>

        @if ($expediente->expfecha_res)
            <div class="col-md-3">
                <div class="form-group">
                    <label>Fecha respuesta</label>
                    <p>
                        <label for="" id="lbl_expfecha_res">
                            {{ $expediente->expfecha_res }}
                        </label>
                        @if (
                            !$readonly and
                                (currentUser()->hasRole('docente') or
                                    currentUser()->hasRole('diradmin') or
                                    currentUser()->hasRole('dirgral') or
                                    currentUser()->hasRole('amatai')))
                            &nbsp;&nbsp;
                            <a style="cursor: pointer;" data-toggle="modal" data-target="#fechalimitres">
                                Modificar
                            </a>
                        @endif
                    </p>
                </div>
            </div>
        @endif
        <div class="col-md-2 ">

            @if ($expediente->asignacion)

                @if (auth()->user()->can('editar_datos_caso'))
                    <a class="btn btn-primary btn-sm btn-block" id="btnEditar"><i class="fa fa-edit"> </i>
                        Editar</a>
                    <a class="btn btn-success btn-block btn-sm" id="btnActualizar" style="display: none;">
                        <i class="fa  fa-check-circle"> </i>
                        Actualizar</a>
                    <a class="btn btn-danger btn-sm btn-block" style="display: none;" id="btnCancelar">
                        <i class="fa  fa-remove"> </i>
                        Cancelar</a>
                @endif

                @if (
                    $expediente->getDocenteAsig()->idnumber != 'Sin asignar' and
                        $expediente->asignacion->procesojud_id == 1 and
                        $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                            currentUser()->hasRole('diradmin') ||
                            currentUser()->hasRole('dirgral') ||
                            currentUser()->hasRole('amatai'))
                    <a href="#" id="btn_act_proc_jur"
                        class="btn-block btn btn-sm btn-warning btn_act_proc_jur mt-1">
                        Activar como proceso jurídico
                    </a>
                @endif

                @if (
                    $expediente->getDocenteAsig()->idnumber != 'Sin asignar' and
                        $expediente->exptipoproce_id != 1 and
                        $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                            currentUser()->hasRole('diradmin') ||
                            currentUser()->hasRole('dirgral') ||
                            currentUser()->hasRole('amatai'))
                    @if ($expediente->expestado_id == 1 || $expediente->expestado_id == 3)
                        @if ($expediente->validateVacations())
                            {!! $expediente->validateVacations() !!}
                        @else
                            <a href="#" id="btn_act_pausa_exp" class="btn btn-block btn-sm btn-info mt-1">
                                Activar pausa
                            </a>
                        @endif
                    @endif

                    @if ($expediente->validateVacations())
                        {!! $expediente->validateVacations() !!}
                    @else
                        <a href="#" id="btn_quit_pausa_exp" class="btn btn-block btn-sm btn-info mt-1">
                            Admin. pausa
                        </a>
                    @endif

                @endif

                @if (!$readonly)
                    <div class="pull-right" style="margin-top:1px;">
                        @if (currentUser()->can('tomar_caso') and $expediente->getDocenteAsig()->name == 'Sin asignar')
                            <a class="btn btn-sm btn-success btn-block" id="btnTomarCaso"><i class="fa fa-check"> </i>
                                Tomar Caso</a>
                        @endif
                    </div>
                @endif
            @else
                Error en la asignación
            @endif
        </div>
    @endif
</div>
<div class="row">
    <div class="col-md-2">
        @if (currentUser()->hasRole('estudiante'))
            <a href="#" id="btn_act_pausa_exp" class="btn btn-block btn-sm btn-info mt-1">
                Ver pausas
            </a>
        @endif
    </div>
</div>
<div class="row">

    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label('Número expediente') !!}
            {!! Form::text('expid', null, ['class' => 'form-control', 'readonly', 'id' => 'expid']) !!}
        </div>
    </div>
    <div class="col-sm-2">
        {!! Form::label('Fecha Expediente: ') !!}
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroup-sizing-default">
                    <i class="fa fa-calendar"></i>
                </span>
            </div>
            {!! Form::text('expfecha', null, [
                'class' => 'form-control',
                'required' => 'required',
                'data-inputmask' => "'alias': 'yyyy/mm/dd'",
                'data-mask',
                'readonly',
            ]) !!}
        </div>

        <!-- /.input group -->
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Rama del Derecho: ') !!}
            @if ($expediente->exptipoproce_id != 3)
                {!! Form::select('expramaderecho_id', $rama_derecho, $expediente->rama_derecho->id, [
                    'placeholder' => 'Selecciona...',
                    'class' => 'form-control disabled required',
                    'required' => 'required',
                    'disabled',
                    'id' => 'expramaderecho_id',
                ]) !!}
            @else
                {!! Form::select('expramaderecho_id', $rama_derecho_defensas, $expediente->rama_derecho->id, [
                    'placeholder' => 'Selecciona...',
                    'class' => 'form-control disabled required',
                    'required' => 'required',
                    'disabled',
                    'id' => 'expramaderecho_id',
                ]) !!}
            @endif
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label('Estado del caso') !!}
            {!! Form::select('expestado_id', $estadosPluck, null, [
                'placeholder' => 'Selecciona...',
                'class' => 'form-control required',
                'required' => 'required',
                'disabled',
                'id' => 'expestado_id',
            ]) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label('Tipo procedimiento: ') !!}


            <select name="exptipoproce_id" id="exptipoproce_id" class="form-control disabled required"
                disabled="disabled" required="required">
                @foreach ($tipo_proceso as $tipo_p)
                    <option @if ($tipo_p->id == $expediente->exptipoproce_id) selected @endif value="{{ $tipo_p->id }}">
                        {{ $tipo_p->ref_tipproceso }}</option>
                @endforeach
            </select>

        </div>
    </div>

</div>
