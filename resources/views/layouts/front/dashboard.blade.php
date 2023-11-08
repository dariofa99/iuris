<?php

if (!isset($modo_nav_crl)) {
    //barra lateral izquierda
    $modo_nav_crl = 'light';
    $sidebar_modo = 'dark';
    $sidebar_color = '#191d36';
    $sidebar_brand_modo = 'dark';
    $sidebar_brand_color = '#191d36';
    //barra navegación superior
    $color_nav = 'white';
    $modo_nav = 'light';
}

?>






<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" id="token">
    <title>{{ config('app.name') }}</title>

    <link rel="shortcut icon" href="{{ asset('dist/img/favicon.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Open+Sans">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">


    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->



    <!-- Custom -->
    {!! Html::style('/css/styles.css?v=4.0') !!}

    <style>
        body {
            font-family: 'Roboto', sans-serif;

        }
    </style>



    <!-- Style plugins -->
    @stack('styles')
    <!-- our styles -->

    <!-- Our Styles -->



</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

    <div class="wrapper">
        <!-- Navbar -->
        @yield('navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('layouts.front.sidebar')
        <!-- /:main Sidebar Container -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Content Header (Page header) -->
            <input type="hidden" id="olderInputValue">
            <input type="hidden" id="inputHash" value="{{ sha1(Auth::user()->id) }}">
            <input type="hidden" id="connectedData"
                value='{"ver_conectados_chat":"{{ Auth::user()->can('ver_conectados_chat') ? 'true' : 'false' }}","role":"{{ Auth::user()->roles[0]->name }}","username":"{{ Auth::user()->name }}","idusuario":"{{ Auth::user()->id }}","correo":"{{ Auth::user()->email }}","imagen":"{{ asset(auth()->user()->image) }}"}'>
            
               <input type="hidden" id="authdata"
                value="{{ Auth::user() }}">
 
            <div style="min-height: 450px;" class="card card-success card-outline">
                <div class="card-header">
                    @yield('titulo_area')
                    @yield('area_buttons')
                </div>
                <div class="card-body">
                    @yield('area_forms')
                </div>
            </div>



        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-{{ $modo_nav_crl }}">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        @include('layouts.footer')
        <!-- /:main Footer -->
    </div>
    <!-- ./wrapper -->
    <!-- wait  es la barra de carga de la pagina-->
    @include('layouts.wait')
    <!-- ./wait  es la barra de carga de la pagina-->
    @include('myforms.frm_modal_show_alerts')
    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <!-- moment -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/locale/es-us.js') }}"></script>



    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

    <script src={{ asset('js/config.js?v=1') }}></script>
    {!! Html::script('js/application.js?v=1') !!}
    <script type="module" src="{{ asset('js/scripts.js?v=1') }}"></script>

    <script>
        //para que funcionen los tooltip
        var tokendefault = '';
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
            $("#wait").hide()
        });
        
    </script>
    <!-- PAGE PLUGINS -->
    @stack('scripts')
    @include('content.scripts')
