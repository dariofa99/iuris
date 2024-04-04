<div class="row">
    <div class="col-md-6">
        @if (currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') 
        || currentUser()->hasRole('dirgral'))
            <div class="form-group" align="right">
                {!! Form::hidden('active_asignacion', '0') !!}
                <input value="1" type="checkbox" {{ $user->active_asignacion == '1' ? 'checked' : '' }}
                    name="active_asignacion" id="active_asignacion">

                {!! Form::label('Asignación casos ') !!}
            </div>
        @endif
    </div>
    <div class="col-md-6">
        @if (currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral'))
            <div class="form-group" align="right">
                {!! Form::hidden('active', '0') !!}

                <input value="1" type="checkbox" {{ $user->active == '1' ? 'checked' : '' }} name="active"
                    id="active">
                {!! Form::label('Usuario Activo ') !!}
            </div>
        @endif
    </div>
    @include('myforms.users.formulario_registro', [
        'disabled' => isset($user) ? '' : '',
        'col' => 6,
    ])

    <div class="col-md-6">
        <label for="password">Contraseña</label>
        <div class="form-group">

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text show_password" id="basic-addon1" style="cursor: pointer;">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
                {!! Form::password('password', [
                    'class' => 'form-control form-control-sm',
                    'autocomplete' => 'nope',
                    'id' => 'password',
                ]) !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Fecha Nacimiento: ') !!}

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fa fa-calendar"></i>
                    </span>
                </div>
                {!! Form::date('fechanacimien', isset($user) ? $user->fechanacimien : '', [
                    'class' => 'form-control form-control-sm',
                    'required' => 'required',
                    'data-inputmask' => "'alias': 'yyyy/mm/dd'",
                    'data-mask',
                    isset($user) ? '' : '',
                ]) !!}
            </div>
            <!-- /.input group -->
        </div>
    </div>
    @if (currentUser()->hasRole('amatai') ||
            currentUser()->hasRole('coordprac') ||
            currentUser()->hasRole('diradmin') ||
            currentUser()->hasRole('dirgral'))
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Rol de Usuario: *') !!}
                <select {{ isset($user) ? '' : '' }} name="id_rol" id="id_rol" required
                    class="form-control form-control-sm required">
                    <option value="">Seleccione...</option>
                    @foreach ($roles as $key => $rol)
                        <option {{ (isset($user) and $user->roles[0]->id == $key) ? 'selected' : '' }}
                            value="{{ $key }}">{{ $rol }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
    @if (currentUser()->hasRole('estudiante') || ($user->hasRole('estudiante') and currentUser()->hasRole('amatai')) and
            !$user->turno)

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Año Cursando ') !!}
                {!! Form::select('cursando_id', $cursando, $user->cursando_id, [
                    'placeholder' => 'Selecciona...',
                    'class' => 'form-control form-control-sm',
                    'required' => 'required',
                ]) !!}
            </div>
        </div>
    @else
        @if (currentUser()->hasRole('estudiante') ||
                currentUser()->hasRole('amatai') ||
                currentUser()->hasRole('diradmin') ||
                currentUser()->hasRole('dirgral'))
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('Año Cursando') !!}
                    {!! Form::select('cursandosa_id', $cursando, $user->cursando_id, [
                        'placeholder' => 'Selecciona...',
                        'class' => 'form-control form-control-sm',
                        'disabled' => 'disabled',
                    ]) !!}
                </div>
            </div>
        @endif
    @endif

    @if (currentUser()->can('cambiar_sede'))
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Sede') !!}
                <select multiple class="form-control form-control-sm select2_ramas selectpicker" name="sede_id[]">
                    @foreach ($sedes as $key => $sede)
                        @php
                            $selected = in_array($sede->id_sede, $user->sedes->pluck('pivot.sede_id')->toArray()) ? 'selected' : '';
                        @endphp
                        <option {{ $selected }} value="{{ $sede->id_sede }}">{{ $sede->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
    @if (
        $user->hasRole('docente') ||
            (currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral')))
        <div class="col-md-6">
            <div class="form-group">
                {!! form::label('ramaderecho_id', 'Ramas de derecho') !!}
                <select class="form-control selectpicker form-control-sm" multiple name="ramaderecho_id[]"
                    id="ramaderecho_id">
                    @foreach ($ramas_derecho as $id => $rama)
                        @php
                            $selected = in_array($id, $user->ramas_derecho->pluck('pivot.ramaderecho_id')->toArray()) ? 'selected' : '';
                        @endphp
                        <option {{ $selected }} value="{{ $id }}">{{ $rama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
    <div class="col-md-6">
        <div class="form-group">
            <label>
                {{ $user->hasRole('estudiante') ? 'Código estudiantil' : 'Tarjeta profesional' }}
            </label>
            {!! Form::text('codigo_estudiantil', $user->codigo_estudiantil, [
                'placeholder' => 'Ej. 1234',
                'class' => 'form-control form-control-sm',
                'required' => 'required',
            ]) !!}
        </div>
    </div>
    @include('myforms.components_user.aditional_data', [
        'data' => getReferencesDataBySection('datos_personales', 'users'),
    ])

</div>
