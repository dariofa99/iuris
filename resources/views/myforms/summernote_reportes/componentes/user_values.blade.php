<div class="row" style="margin-top: 5px">
    <div class="col-md-12">
        <div class="form-group item_value">
            <small data-table="users" data-model="user" data-summernote="{{ $mySummernote }}" data-short_name="name"
                class="item_con" data-type="{{ $tipo_usuario_id }}" data-name="nombre_{{ $parte }}">
                Nombres [{{ $parte }}]</small>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group item_value">
            <small data-table="users" data-model="user" data-summernote="{{ $mySummernote }}" data-short_name="lastname"
                class="item_con" data-type="{{ $tipo_usuario_id }}" data-name="apellido_{{ $parte }}">
                Apellidos [{{ $parte }}]</small>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group item_value">
            <small data-table="users" data-model="user" data-summernote="{{ $mySummernote }}" data-short_name="tipodoc"
                class="item_con" data-type="{{ $tipo_usuario_id }}" data-name="tipoidentificacion_{{ $parte }}">
                Tipo Identificación [{{ $parte }}]</small>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group item_value">
            <small data-table="users" data-model="user" data-summernote="{{ $mySummernote }}"
                data-short_name="idnumber" class="item_con" data-type="{{ $tipo_usuario_id }}"
                data-name="identificacion_{{ $parte }}">
                No. Identificación [{{ $parte }}]</small>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group item_value">
            <small data-table="users" data-model="user" data-summernote="{{ $mySummernote }}" data-short_name="tel1"
                class="item_con" data-type="{{ $tipo_usuario_id }}" data-name="telefono_{{ $parte }}">
                Teléfono [{{ $parte }}]</small>


        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group item_value">
            <small data-table="users" data-model="user" data-summernote="{{ $mySummernote }}" data-short_name="address"
                class="item_con" data-type="{{ $tipo_usuario_id }}" data-name="direccion_{{ $parte }}">
                Dirección [{{ $parte }}]</small>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group item_value">
            <small data-table="users" data-model="user" data-summernote="{{ $mySummernote }}" data-short_name="email"
                class="item_con" data-type="{{ $tipo_usuario_id }}" data-name="email_{{ $parte }}">
                Correo electrónico [{{ $parte }}]</small>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group item_value">
            <small data-table="users" data-model="user" data-summernote="{{ $mySummernote }}"
                data-short_name="codigo_estudiantil" class="item_con" data-type="{{ $tipo_usuario_id }}"
                data-name="codigo_{{ $parte }}">
                Código estudiantil [{{ $parte }}]</small>
        </div>
    </div>

    @include('myforms.summernote_reportes.componentes.categories_ajax', [
        'categories_report' => getReferencesDataBySection('datos_personales', 'users'),
        'model' => 'user',
        'user_type' => $tipo_usuario_id,
    ])

    @include('myforms.summernote_reportes.componentes.categories_ajax', [
        'categories_report' => getReferencesDataBySection('socio_economica', 'users'),
        'model' => 'user',
        'user_type' => $tipo_usuario_id,
    ])

    @include('myforms.summernote_reportes.componentes.categories_ajax', [
        'categories_report' => getReferencesDataBySection('enfoque_diferencial', 'users'),
        'model' => 'user',
        'user_type' => $tipo_usuario_id,
    ])

</div>
