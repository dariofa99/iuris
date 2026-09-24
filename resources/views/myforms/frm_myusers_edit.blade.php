@extends('layouts.dashboard')
@section('titulo_area')
    {{--  Rol de Usuario: <strong> {{ count($user->roles) > 0 ? $user->roles[0]->display_name : 'Sin rol' }} </strong> --}}
@endsection
@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
    <style>

    </style>
@endpush

@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('area_forms')

    @include('msg.alerts')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @php
        $canedit = true;
    @endphp
    <div class="iuris-profile">

        {{-- =========================================================
        SIDEBAR DEL PERFIL
    ========================================================== --}}
        <div class="iuris-profile-sidebar">

            {{-- INFORMACIÓN DEL USUARIO --}}
            <div class="iuris-profile-user">

                <div class="iuris-profile-avatar">

                    <img id="img_profile"
                        src="{{ is_file(public_path('thumbnails/' . $user->image))
                            ? asset('thumbnails/' . $user->image)
                            : asset('thumbnails/default.jpg') }}"
                        alt="Usuario">

                    <button type="button" id="update_profile_picture" class="iuris-avatar-edit" title="Cambiar foto">
                        <i class="fa fa-camera"></i>
                    </button>

                </div>


                {{-- INPUT OCULTO PARA LA FOTO --}}
                <input accept="image/*" style="display:none" id="file_picture" type="file">


                {{-- LOADER DE LA FOTO --}}
                <div id="loader-container" style="display:none;">

                    <div class="progress">

                        <div id="progress-bar" class="progress-bar progress-bar-success progress-bar-striped"
                            role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;">
                            0%
                        </div>

                    </div>

                </div>


                {{-- NOMBRE --}}
                <h3 id="lbl_user_p_name">
                    {{ $user->name }}
                </h3>


                {{-- ROL --}}
                <span class="iuris-profile-role" id="lbl_rol_name">
                    {{ count($user->roles) > 0 ? $user->roles[0]->name : 'Sin rol' }}
                </span>


                {{-- ESTADO --}}
                <span class="iuris-profile-status">

                    <i class="fa fa-circle"></i>

                    Usuario activo

                </span>

            </div>


            {{-- =====================================================
            MENÚ DE SECCIONES
        ====================================================== --}}
            <div class="iuris-profile-menu">



                {{-- DATOS docente --}}
                <button type="button" class="iuris-profile-menu-item active" data-tab-target="docente_tab">

                    <span class="iuris-profile-menu-icon">
                        <i class="fa fa-graduation-cap"></i>
                    </span>

                    <span>
                        <strong>Actuar docente</strong>
                        <small>Información del docente</small>
                    </span>

                </button>


                {{-- DATOS GENERALES --}}
                <button type="button" class="iuris-profile-menu-item" data-tab-target="settings">

                    <span class="iuris-profile-menu-icon">
                        <i class="fa fa-user"></i>
                    </span>

                    <span>
                        <strong>Datos generales</strong>
                        <small>Información personal</small>
                    </span>

                </button>


                {{-- INFORMACIÓN IDENTITARIA --}}
                <button type="button" class="iuris-profile-menu-item" data-tab-target="identitaria_tab">

                    <span class="iuris-profile-menu-icon">
                        <i class="fa fa-id-card"></i>
                    </span>

                    <span>
                        <strong>Información identitaria</strong>
                        <small>Datos de identificación</small>
                    </span>

                </button>


                {{-- DISCAPACIDAD --}}
                <button type="button" class="iuris-profile-menu-item" data-tab-target="discapacidad_tab">

                    <span class="iuris-profile-menu-icon">
                        <i class="fa fa-wheelchair"></i>
                    </span>

                    <span>
                        <strong>Discapacidad</strong>
                        <small>Información registrada</small>
                    </span>

                </button>


                {{-- INFORMACIÓN SOCIOECONÓMICA --}}
                <button type="button" class="iuris-profile-menu-item" data-tab-target="economica_tab">

                    <span class="iuris-profile-menu-icon">
                        <i class="fa fa-line-chart"></i>
                    </span>

                    <span>
                        <strong>Información socioeconómica</strong>
                        <small>Situación económica</small>
                    </span>

                </button>

            </div>


            {{-- =====================================================
            CAMBIAR ROL
        ====================================================== --}}
            @can('asig_rol')
                <div class="iuris-profile-role-action">

                    <button type="button" id="btn_asignar_rol" class="btn btn-block">

                        <i class="fa fa-shield"></i>

                        Cambiar rol

                    </button>

                </div>
            @endcan

        </div>


        {{-- =========================================================
        CONTENIDO PRINCIPAL
    ========================================================== --}}
        <div class="iuris-profile-content">

            <form id="myFormUserEdit" autocomplete="off">


                {{-- =================================================
                PANEL
            ================================================== --}}
                <div class="iuris-profile-panel">


                    {{-- CABECERA --}}
                    <div class="iuris-profile-panel-header">

                        <div>

                            <span class="iuris-profile-eyebrow">
                                PERFIL DE USUARIO
                            </span>

                            <h3>
                                Información del usuario
                            </h3>

                            <p>
                                Actualiza la información registrada del usuario.
                            </p>

                        </div>

                    </div>


                    {{-- =================================================
                    CONTENIDO DE LAS PESTAÑAS
                ================================================== --}}
                    <div class="iuris-profile-tabs-content">


                        {{-- =============================================
                        DATOS GENERALES
                    ============================================== --}}
                        <div class="iuris-profile-tab " id="settings">

                            @include('myforms.components_user.user_edit_form')

                        </div>

                        {{-- =============================================
                        INFORMACIÓN DOCENTE
                    ============================================== --}}
                        <div class="iuris-profile-tab active" id="docente_tab">

                            <div class="row">

                                @include('myforms.components_user.info_docente')

                            </div>

                        </div>

                        {{-- =============================================
                        INFORMACIÓN IDENTITARIA
                    ============================================== --}}
                        <div class="iuris-profile-tab" id="identitaria_tab">

                            <div class="row">

                                @include('myforms.components_user.identitaria', [
                                    'disabled' => isset($user) ? '' : '',
                                ])

                            </div>

                        </div>


                        {{-- =============================================
                        DISCAPACIDAD
                    ============================================== --}}
                        <div class="iuris-profile-tab" id="discapacidad_tab">

                            <div class="row">

                                @include('myforms.components_user.discapacidad', [
                                    'disabled' => isset($user) ? '' : '',
                                ])

                            </div>

                        </div>


                        {{-- =============================================
                        INFORMACIÓN SOCIOECONÓMICA
                    ============================================== --}}
                        <div class="iuris-profile-tab" id="economica_tab">

                            <div class="row">

                                @include('myforms.components_user.socioeconomica', [
                                    'disabled' => isset($user) ? '' : '',
                                ])

                            </div>

                        </div>


                    </div>

                </div>


                {{-- =====================================================
                BOTÓN ACTUALIZAR
            ====================================================== --}}
                @if (currentUser()->can('edit_usuarios'))
                    <div class="iuris-profile-actions">

                        <button type="button" id="btn_actualizar_usuario" class="btn-iuris-primary">

                            <i class="fa fa-save"></i>

                            <span>
                                Actualizar información
                            </span>

                        </button>

                    </div>
                @endif


            </form>

        </div>

    </div>


    {{-- =============================================================
    JAVASCRIPT DE LAS PESTAÑAS
============================================================= --}}
    <script>
        $(document).ready(function() {

            /*
            |--------------------------------------------------------------------------
            | CAMBIO DE PESTAÑAS
            |--------------------------------------------------------------------------
            */

            $('.iuris-profile-menu-item').on('click', function(e) {

                e.preventDefault();

                // ID de la pestaña que queremos mostrar
                const target = $(this).data('tab-target');

                console.log('Cambiando a pestaña:', target);


                /*
                |--------------------------------------------------------------------------
                | DESACTIVAR TODOS LOS BOTONES
                |--------------------------------------------------------------------------
                */

                $('.iuris-profile-menu-item')
                    .removeClass('active');


                /*
                |--------------------------------------------------------------------------
                | ACTIVAR EL BOTÓN SELECCIONADO
                |--------------------------------------------------------------------------
                */

                $(this)
                    .addClass('active');


                /*
                |--------------------------------------------------------------------------
                | OCULTAR TODAS LAS PESTAÑAS
                |--------------------------------------------------------------------------
                */

                $('.iuris-profile-tab')
                    .removeClass('active');


                /*
                |--------------------------------------------------------------------------
                | MOSTRAR LA PESTAÑA SELECCIONADA
                |--------------------------------------------------------------------------
                */

                $('#' + target)
                    .addClass('active');

            });


            /*
            |--------------------------------------------------------------------------
            | CAMBIAR FOTO
            |--------------------------------------------------------------------------
            */

            $('#update_profile_picture').on('click', function() {

                $('#file_picture').click();

            });

        });
    </script>
@stop
@push('scripts')
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>
    <script type="module" src={{ asset('js/admin_users.js') }}></script>
    <script>
        @if (currentUser()->hasRole('estudiante') and $user->turno == null)
            var message = `<div class="alert alert-danger" style="font-size:18px">
            <h4>Estimado estudiante para el registro del curso tenga en cuenta lo siguiente:</h4>
            <h3>
            <ul>
            <li>
             Para cursar consultorios 1 en la jornada de la mañana seleccione 4B

            </li>
             <li>
             Para cursar consultorios 1 en la jornada de la tarde seleccione 4A

            </li>
             <li>
             Para cursar consultorios 2 en la jornada de la mañana seleccione 5B

            </li>
             <li>
             Para cursar consultorios 2 en la jornada de la tarde seleccione 5A

            </li>
            </ul>
                          </h3>
            <h4>
            Recuerde refrescar el navegador con las teclas CTRL + F5 o SHIFT + F5
              
                </h4>           
            </div>`;


            /*   var message = `<h5>Estimado estudiante debido a cambios de horario se habilitará la 
          asignación del curso en el transcurso de esta semana.</h5>`;
                                                 
                                                 Comunícate con el director
                                                 
                                                  */

            $("#modal-show-alerts-content").html(message);
            $("#mymodalShowAlerts").modal("show")
        @endif


        $(document).ready(function() {

            $('.iuris-profile-menu-item').on('click', function(e) {

                e.preventDefault();

                const target = $(this).data('tab-target');

                console.log('Abriendo pestaña:', target);


                // Quitar activo de todos los botones
                $('.iuris-profile-menu-item')
                    .removeClass('active');


                // Activar botón seleccionado
                $(this)
                    .addClass('active');


                // Ocultar todos los contenidos
                $('.iuris-profile-tab')
                    .removeClass('active');


                // Mostrar el seleccionado
                $('#' + target)
                    .addClass('active');

            });

        });
    </script>
@endpush
