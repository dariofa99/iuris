@php
    $solicitante = $conciliacion->getUser(205);
    $soliIdnumber = '';
    if ($solicitante->id != null) {
        $soliIdnumber = $solicitante->idnumber;
    } 
@endphp



<input type="hidden" name="id" value="{{ isset($user) ? $user->id : '' }}">
<input type="hidden" name="solicitante_idnumber" value="{{ $soliIdnumber }}" >

<div class="col-md-3">
    <div class="form-group has-feedback"><label for="idnumber">Tipo documento<span class="ast_required">*</span></label>
        <select {{ isset($disabled) ? $disabled : '' }} name="tipodoc_id" id="tipodoc_id"
            class="form-control form-control-sm required" required>
            <option value="">Seleccione...</option>
            @foreach ($tipodoc as $key => $doc)
                <option {{ (isset($user) and $user->tipodoc_id == $key) ? 'selected' : '' }}
                    value="{{ $key }}">
                    {{ $doc }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label>
            No. Documento<span class="ast_required">*</span>
        </label>
        <input {{ isset($disabled) ? $disabled : '' }} data-name="cc_nit" required type="text"
            value="{{ $user->idnumber }}" name="idnumber" class="form-control form-control-sm required">

    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>
            Nombres<span class="ast_required">*</span></label>
        <input data-name="nombre" required type="text" {{ isset($disabled) ? $disabled : '' }}
            value="{{ $user->name }}" name="name" class="form-control form-control-sm required">

    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label>
            Apellidos<span class="ast_required">*</span></label>
        <input {{ isset($disabled) ? $disabled : '' }} data-name="nombre" required type="text"
            value="{{ $user->lastname }}" name="lastname" class="form-control form-control-sm required">

    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label>Teléfono<span class="ast_required">*</span>
        </label>
        <input {{ isset($disabled) ? $disabled : '' }} data-name="tel1" name="tel1" required type="text"
            value="{{ $user->tel1 }}" name="tel1" class="form-control form-control-sm required">

    </div>
</div>

<div class="col-md-{{ isset($col) ? $col : '3' }}">
    <div class="form-group has-feedback"><label for="name">Dirección para notificaciones<span
                class="ast_required">*</span></label>
        <input {{ isset($disabled) ? $disabled : '' }} id='address' value="{{ isset($user) ? $user->address : '' }}"
            name='address' required type="text" class="form-control form-control-sm required" data-toggle="tooltip"
            title="Dirección de residencia" placeholder="Dirección de residencia" maxlength="200">

    </div>
</div>

<div class="col-md-{{ isset($col) ? $col : '3' }}">
    <div class="form-group has-feedback"><label for="name">Correo electrónico<span
                class="ast_required">*</span></label>
        <input {{ isset($disabled) ? $disabled : '' }} id='email' name='email'
            value="{{ isset($user) ? $user->email : '' }}" required type="email"
            class="form-control form-control-sm required validate_email" data-toggle="tooltip"
            title="Correo electrónico" placeholder="Correo electrónico" maxlength="200">

    </div>
</div>

<div class="col-md-{{ isset($col) ? $col : '3' }}">
    <div class="form-group has-feedback"><label for="name">Telefóno celular<span
                class="ast_required">*</span></label>
        <input {{ isset($disabled) ? $disabled : '' }} id='tel1' value="{{ isset($user) ? $user->tel1 : '' }}"
            name='tel1' required type="text" class="form-control form-control-sm onlynumber required"
            data-toggle="tooltip" title="Número de contacto" placeholder="Número de contacto" maxlength="10">

    </div>
</div>

@include('myforms.categorias.partials.ajax.pregunta', [
    'reference' => getAditionalDataByShortName('tipo_de_tarjeta', 'users'),
    'col' => 3,
    'required' => true,
    'model' => $user,
])

<div class="col-md-3">
    <div class="form-group">
        <label>No.Tarj. Profesional<span class="ast_required">*</span>
        </label>
        <input {{ isset($disabled) ? $disabled : '' }} data-name="codigo_estudiantil" name="codigo_estudiantil"
            required type="text" value="{{ $user->codigo_estudiantil }}"
            class="form-control form-control-sm required">

    </div>
</div>
