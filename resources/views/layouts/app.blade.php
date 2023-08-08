<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Iuris') }}</title>

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
    <link rel="stylesheet" href="{{ asset('css/front.css') }}">
    

  

    


    <style>
/* Sticky footer styles
-------------------------------------------------- */
*{
    font-size: 16px;
}
form label{
    font-size: 14px !important;
}
html {
  position: relative;
  min-height: 100%;
}
body {
  margin-bottom: 100px; /* Margin bottom by footer height */  
}

.card-header {
    position: relative;
    font-size: 17px !important;
    color: #fff;
    background-color: #00923f;
}

.card {

    background-color: #ffffffc2;
    min-height: 280px;
}


.footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 100px; /* Set the fixed height of the footer here */
  /*line-height: 30px;  */
  background-color: #222d32 !important;
}

#app .container {
    margin-bottom: 50px !important;
}

.has-feedback .form-control {
    padding-right: 5.5px !important;
}
.has-feedback label~.form-control-feedback {
    top: 39px !important;
}

.has-feedback .form-control-feedback {
    top: 12px !important;
}


/* Custom page CSS
-------------------------------------------------- */
/* Not required for template or sticky footer method. */

.container-footer {

  width: 100%;
  max-width: 100%;
  padding: 15px 15px;
  background-color: #222d32;
}

a {
    color: #d1941e;
}

.text-muted {
    color: #d8d3c3!important;
}

    </style>
@stack('styles')
</head>
<body class="content-wrapper" style="background-image: linear-gradient(-90deg,#c0c0c0 0,#ffffff 50%,#c0c0c0 100%);">

@include('layouts.app_header')
   




    <div id="app">
        <main style="padding-top: 3px !important;margin:10px">
            @yield('content')
        </main>

    </div>
 
    
@include('layouts.app_footer')

    
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

$(document).ready(function(){
    $("#myLoginForm").on('submit',function(e){
      
      if (typeof(Storage) !== 'undefined') {
        // Código cuando Storage es compatible
        var token = localStorage.getItem('tokensessionpc');
        //token = token;
       $(this).append($('<input>',{
            'type':'hidden',
            'value':token,
            'name':'token'
        }));
    } else {
       // Código cuando Storage NO es compatible
    } 
   // e.preventDefault();
})
});



</script>

</html>
