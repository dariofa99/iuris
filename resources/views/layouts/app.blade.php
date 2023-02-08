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

            <!-- Bootstrap core CSS -->
    <link rel="stylesheet" media="all" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
        <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" >
      <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito" type="text/css">

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">


<!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{asset('/plugins/sweetalert2/sweetalert2.min.css')}}">
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

  <link rel="stylesheet" href="{{ asset('css/front.css?v=2') }}">
  

    


    <style>
/* Sticky footer styles
-------------------------------------------------- */

html {
  position: relative;
  min-height: 100%;
}
body {
  margin-bottom: 60px; /* Margin bottom by footer height */
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

.btn-warning {
    color: #fff; 
    background-color: #076933;
    
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

<div class="row" style="background-color: #222d32; opacity: 1; margin-right: 0px;" >
    <div class="col-md-3 image d-none d-sm-inline-block" style="padding-left: 50px;">
        <img src="{{ asset('dist/img/udenarbl.png') }}" class="elevation-2" style="width: 250px;margin:10px;" alt="User Image">
    </div>
    <div class="col-md-6 " style="padding-top: 25px; text-align: center; font-size: 17px;">
        <p style="color:#ffffff;     font-size: 20px; font-weight: 900;"><b>Consultorios Jurídicos y Centro de Conciliación<br>"Eduardo Alvarado Hurtado"</b></p> 
    </div>
</div>
   
<div clas="row" style="text-align:center;margin:17px;">

     <p style="color:#000000;     font-size: 20px;"><b>Sistema de atención virtual</b></p>   
</div>     




    <div id="app">
       {{-- <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <img src="{{asset('css/img/logo.png')}}" alt="Logo Provecol" class="logo img-fluid" style="opacity: .8;width:60px;height:auto">

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                    <li>    <h5><a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                    </a></h5>
                    </li>
                        <!-- Authentication Links  end-->

                        @guest

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Iniciar sesión') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Registrar') }}</a>
                                </li>
                            @endif


                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav> --}}

        <main class="py-4" style="padding-top: 3px !important;">
            @yield('content')
        </main>

    </div>
 
      
                       
        
    <footer class="footer">
  
        <div class="container container-footer" style="text-align: end;">
            <img src="https://iurisapp.udenar.edu.co/dist/img/consultorios.png" class="d-none d-sm-inline-block" style="width: 400px;margin-top:-370px;opacity: 0.3;z-index: -1;position: absolute;margin-left: -401px;" alt="User Image">
    
            <div class="row" style="text-align: center; margin: 0px 30px 0px 30px;">
                <div class="col-md-4" style="text-align: left;">
                    <span class="text-muted"><span style="color:#d1941e;">Contactos</span><br><i class="nav-icon fa fa-phone" style="margin-right: 7px;"></i> (032)7244309 ext. 555<br><i class="nav-icon fa fa-envelope" style="margin-right: 7px;"></i> infojuridicos@udenar.edu.co</span>
                </div>
                    <div class="col-md-4">
                    <span class="text-muted"><a href="http://derecho.udenar.edu.co/" target="_blank">Facultad de Derecho y Ciencias Políticas</a><br>Acreditado en Alta Calidad<br>Res. 2160 05/02/2016</span>
                </div>
                    <div class="col-md-4" style="text-align: right;">
                    <span class="text-muted"><a href="/register">Registro (Estudiantes matriculados)</a><br><span style="font-size: 11px;">IURIS - AMATAI Ingeniería Informática SAS<br>© {{date('Y')}}</span></span>
                </div>
            </div>
            
            
        </div>
    </footer>



    
    <div id="wait" style="display:none; position: absolute; width: 100%;min-height: 100%;height: auto;position: fixed;top:0; left:0;background-color: rgba(236, 240, 245, 0.8);" ><img src="{{asset('img/logo2.png')}}" id="load" width="67" height="71" style="margin-top:18%;margin-left:48%;padding:2px;" /><br><span style="margin-top:18%;margin-left:48%;padding:2px;color:#848484;font-size: 16px;">Cargando...<span></div>

</body>
<!-- Scripts -->
{!! Html::script('plugins/jQuery/jquery-2.2.3.min.js')!!}
<script src="{{ asset('js/app.js') }}" defer></script>
<script src="{{asset('plugins/slim/jquery-3.5.1.slim.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js" integrity="sha384-XEerZL0cuoUbHE4nZReLT7nx9gQrQreJekYhJD9WNWhH8nEW+0c5qq7aIo2Wl30J" crossorigin="anonymous"></script>
<!-- jQuery -->
{{-- <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script> --}}
<!-- SweetAlert2 -->
  <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
  <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
  {!! Html::script('plugins/datepicker/js/moment.min.js')!!}
@stack('scripts')

 
<script type="text/javascript">
var token = localStorage.getItem('tokensessionpc');
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});
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
