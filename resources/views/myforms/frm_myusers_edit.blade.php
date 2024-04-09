@extends('layouts.dashboard')
@section('titulo_area')
    Rol de Usuario: <strong> {{ $user->roles[0]->display_name }} </strong>
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
    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div align="center" class="pict_container_profile">
                        <div id="loader-container">
                            <div class="progress">
                                <div id="progress-bar" class="progress-bar progress-bar-success progress-bar-striped"
                                    role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                    style="width: 00%;">
                                    00%
                                </div>
                            </div>
                        </div>
                        <img class='img-circle img_profile' id="img_profile"
                            src="{{ is_file(public_path('thumbnails/' . $user->image)) ? asset('thumbnails/' . $user->image) : asset('thumbnails/default.jpg') }}"
                            alt="User">
                    </div>
                    <div align="center">
                        <input accept="image/*" style="display: none" id="file_picture" type="file" />
                        <i id="update_profile_picture" style="font-size:18px;cursor:pointer;margin:2px"
                            class="fa fa-camera"></i>
                    </div>

                    <h3 id="lbl_user_p_name" class="profile-username text-center lbl_user_name">
                        {{ $user->name }}</h3>

                    <p class="text-muted text-center" id="lbl_rol_name">

                        {{ count($user->roles) > 0 ? $user->roles[0]->name : 'Asignar rol' }}
                    </p>


                    @can('asig_rol')
                        <a href="#" id="btn_asignar_rol" class="btn btn-warning btn-xs btn-block"><b>Cambiar rol</b></a>
                    @endcan
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->



        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <form id="myFormUserEdit" autocomplete="off">
                <div class="card card-tabs">
                    <div class="card-header p-2">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active urlactive" href="#settings" data-toggle="tab">Datos generales</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link urlactive" id="identitaria-tab" data-toggle="tab" href="#identitaria_tab"
                                    role="tab" aria-controls="identitaria_tab" aria-selected="false">
                                    Información Identitaria
                                </a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link urlactive" id="identitaria-tab" data-toggle="tab" href="#identitaria_tab"
                                    role="tab" aria-controls="identitaria_tab" aria-selected="false">
                                    Información Discapacidad
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link urlactive" id="economica-tab" data-toggle="tab" href="#economica_tab"
                                    role="tab" aria-controls="economica_tab" aria-selected="false">
                                    Información socio-económica
                                </a>
                            </li>

                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">

                            <div class="tab-pane active" id="settings">
                                @include('myforms.components_user.user_edit_form')
                            </div>
                            <!-- /.tab-pane -->

                            <!-- /.tab-pane -->
                            <div id="identitaria_tab" class="tab-pane fade" role="tabpanel"
                                aria-labelledby="identitaria-tab">
                                <div class="row">
                                    @include('myforms.components_user.identitaria', [
                                        'disabled' => isset($user) ? '' : '',
                                    ])
                                </div>


                            </div>

                            <div id="economica_tab" class="tab-pane fade" role="tabpanel" aria-labelledby="economica-tab">
                                <div class="row">
                                    @include('myforms.components_user.socioeconomica', [
                                        'disabled' => isset($user) ? '' : '',
                                    ])
                                </div>

                            </div>
                            <!-- /.tab-pane -->

                            <!-- /.tab-pane -->

                            <!-- /.tab-pane -->

                            <!-- /.tab-pane -->


                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <button type="button" id="btn_actualizar_usuario"
                            class="btn btn-primary btn-block">Actualizar</button>
                    </div>
                </div>
            </form>

            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
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
    </script>
@endpush
