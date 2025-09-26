    {!! csrf_field() !!}

    <input {{ isset($disabled) ? $disabled : '' }} id='id' value="{{ isset($user) ? $user->id : '' }}"
        name='id' type="hidden" maxlength="12">


    <div class="col-md-{{ isset($col) ? $col : '6' }}">
        <div class="form-group has-feedback"><label for="idnumber">Tipo de Persona<span
                    class="ast_required">*</span></label>
            <select {{ isset($disabled) ? $disabled : '' }} required name="tipopers_id" id="tipopers_id"
                class="form-control form-control-sm required">
                <option value="">Seleccione...</option>
                @foreach ($tipopers as $key => $doc)
                    <option {{ (isset($user) and $user->tipopers_id == $key) ? 'selected' : '' }}
                        value="{{ $key }}">{{ $doc }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-{{ isset($col) ? $col : '6' }}">
        <div class="form-group has-feedback"><label for="tipodoc_id">Tipo documento
                <span class="ast_required">*</span></label>
            <select {{ isset($disabled) ? $disabled : '' }} name="tipodoc_id" id="tipodoc_id"
                class="form-control form-control-sm required" required>
                <option value="">Seleccione...</option>
                @foreach ($tipodoc as $key => $doc)
                    <option {{ (isset($user) and $user->tipodoc_id == $key) ? 'selected' : '' }}
                        value="{{ $key }}">
                        {{ $doc }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-{{ isset($col) ? $col : '6' }}">
        <div class="form-group has-feedback"><label for="idnumber">Número de documento<span
                    class="ast_required">*</span></label>
            <input {{ isset($disabled) ? $disabled : '' }} id='idnumber'
                value="{{ isset($user) ? $user->idnumber : '' }}" name='idnumber' required type="text"
                class="form-control form-control-sm onlynumber required" data-toggle="tooltip" title="Solo números"
                placeholder="Número de documento" maxlength="12">

        </div>
    </div>

    <div class="col-md-{{ isset($col) ? $col : '6' }}">
        <div class="form-group has-feedback"><label for="name">Nombres<span class="ast_required">*</span></label>
            <input {{ isset($disabled) ? $disabled : '' }} id='name'
                value="{{ isset($user) ? $user->name : '' }}" name='name' required type="text"
                class="form-control form-control-sm required" data-toggle="tooltip" title="Nombres"
                placeholder="Nombres" maxlength="20">

        </div>
    </div>

    <div class="col-md-{{ isset($col) ? $col : '6' }}">
        <div class="form-group has-feedback"><label for="name">Apellidos<span class="ast_required">*</span></label>
            <input {{ isset($disabled) ? $disabled : '' }} id='lastname'
                value="{{ isset($user) ? $user->lastname : '' }}" name='lastname' required type="text"
                class="form-control form-control-sm required" data-toggle="tooltip" title="Apellidos"
                placeholder="Apellidos" maxlength="20">

        </div>
    </div>
    <div class="col-md-{{ isset($col) ? $col : '6' }}">
        <div class="form-group has-feedback"><label for="name">Dirección para notificaciones<span
                    class="ast_required">*</span></label>
            <input {{ isset($disabled) ? $disabled : '' }} id='address'
                value="{{ isset($user) ? $user->address : '' }}" name='address' required type="text"
                class="form-control form-control-sm required" data-toggle="tooltip" title="Dirección de residencia"
                placeholder="Dirección de residencia" maxlength="200">

        </div>
    </div>

    <div class="col-md-{{ isset($col) ? $col : '6' }}">
        <div class="form-group has-feedback"><label for="name">Correo electrónico<span
                    class="ast_required">*</span></label>
            <input {{ isset($disabled) ? $disabled : '' }} id='email' name='email'
                value="{{ isset($user) ? $user->email : '' }}" required type="email"
                class="form-control form-control-sm required validate_email" data-toggle="tooltip"
                title="Correo electrónico" placeholder="Correo electrónico" maxlength="200">

        </div>
    </div>

    <div class="col-md-{{ isset($col) ? $col : '6' }}">
        <div class="form-group has-feedback"><label for="name">Telefóno celular<span
                    class="ast_required">*</span></label>
            <input {{ isset($disabled) ? $disabled : '' }} id='tel1'
                value="{{ isset($user) ? $user->tel1 : '' }}" name='tel1' required type="text"
                class="form-control form-control-sm onlynumber required" data-toggle="tooltip"
                title="Número de contacto" placeholder="Número de contacto" maxlength="10">

        </div>
    </div>

    <div class="col-md-{{ isset($col) ? $col : '6' }}">
        <div class="form-group has-feedback">
            <label for="name">Otro telefóno<span class="ast_required">*</span></label>
            <input autocomplete="off" {{ isset($disabled) ? $disabled : '' }} id='tel2'
                value="{{ isset($user) ? $user->tel2 : '' }}" name='tel2' required type="text"
                class="form-control form-control-sm onlynumber required" data-toggle="tooltip"
                title="Número de contacto 2" placeholder="Número de contacto 2" maxlength="10">
        </div>
    </div>

   

  <div class="col-md-{{ isset($col) ? $col : '6' }}"> 
        <div class="form-group">
            {!! Form::label('Fecha Nacimiento: ') !!}
            <span class="ast_required">*</span>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fa fa-calendar"></i>
                    </span>
                </div>
                 <input autocomplete="off" {{ isset($disabled) ? $disabled : '' }} id='fechanacimien'
                value="{{ isset($user) ? $user->fechanacimien : '' }}" name='fechanacimien' required type="date"
                class="form-control form-control-sm  required" data-toggle="tooltip"
                title="Fecha de nacimiento" data-mask data-inputmask = "'alias': 'yyyy/mm/dd'" placeholder="Fecha de nacimiento" maxlength="10">

                {{-- {!! Form::date('fechanacimien', isset($user) ? $user->fechanacimien : '', [
                    'class' => 'form-control form-control-sm required',
                    'required' => 'required',
                    'data-inputmask' => "'alias': 'yyyy/mm/dd'",
                    'data-mask',
                    
                ]) !!} --}}
            </div>
            <!-- /.input group -->
        </div>
    </div>

    @include('myforms.components_user.aditional_data', [
        'data' => getReferencesDataBySection('datos_personales', 'users'),
    ])
