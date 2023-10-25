<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">

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
    
    <link rel="stylesheet"
        href="{{ config('app.name') != 'ConciliApp' ? asset('css/landing_iuris.css') : asset('css/conciliappfront.css') }}">

    @stack('styles')
</head>

<body class="content-wrapper" style="background-color: #ffffff ;">

    @if (config('app.name') == 'ConciliApp')
        @include('layouts.partials.conciliapp_header')
    @else
        @include('layouts.partials.app_header')
    @endif


    <div id="app">
        <main style="padding-top: 3px !important;margin:10px">
            @yield('content')
        </main>
    </div>
    <hr>

    @if (config('app.name') == 'ConciliApp')
        @include('layouts.partials.conciliapp_footer')
    @else
        @include('layouts.partials.app_footer')
    @endif



    @include('layouts.wait')
</body>

<!-- jQuery 3 -->
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

@stack('scripts')


<script type="text/javascript">
    var token = localStorage.getItem('tokensessionpc');
</script>

</html>
