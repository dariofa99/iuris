<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lybra') }}</title>

    <link rel="shortcut icon" href="{{ asset('dist/img/favicon.png') }}">

    <!-- Scripts -->


        <!-- Bootstrap core CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

     <!-- SweetAlert2 -->
     <link rel="stylesheet" href="{{asset('/plugins/sweetalert2/sweetalert2.min.css')}}">
     <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

    <!-- Styles -->  
    <link href="{{ asset('css/front.css') }}" rel="stylesheet">

    <style>
/* Sticky footer styles
-------------------------------------------------- */
html {
  position: relative;
  min-height: 100%;
}
body {
    font-size: 18px !important;
  margin-bottom: 60px; /* Margin bottom by footer height */
}



.card {
    background-color: #ffffffc7;    
    margin-bottom: 70px;
    min-height: 200px;
}

.btn-default {
    color: #fff;
    background-color: #a4185e;
    border-color: #a4185e;
}

.text-header {
    padding-top: 25px;
    padding-bottom: 23px;
    text-align: center;
    font-size: 19px;
    font-weight: 600;
}

.img-header {
width: 100px;
margin:10px;    
}

.image {
    padding-left: 50px;
}
.footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 150px; /* Set the fixed height of the footer here */
  line-height: 30px; /* Vertically center the text there */
  background-color: #222d32;
}


/* Custom page CSS
-------------------------------------------------- */
/* Not required for template or sticky footer method. */

.container-footer {
    color: #ffffff !important;
  width: 100%;
  max-width: 100%;
  padding: 15px 15px;
  background-color: #222d32 !important;
}

.img-brading-footer {
    /*width: 70%;*/ 
    max-width:1500px; 
    margin-bottom: -80px;
}

@media (max-width: 800px) {
    


.image {
    padding-left: 30px;
    text-align: center;
    
}
.text-header {
    padding-top: 0px;
    padding-bottom: 0px;
    font-size: 15px;
    font-weight: 600;
}
a {
    color: #d1941e;
}

.text-muted {
    color: #ffffff !important;
}

}

    </style>
 @stack('styles')   
</head>
<body class="content-wrapper" style="background-image: linear-gradient(-90deg,#c0c0c0 0,#ffffff 50%,#c0c0c0 100%);">

<div class="row" style="background-color: #222d32; opacity: 1; margin-right: 0px;" >
    <div class="col-md-3 image d-none d-sm-inline-block" style="padding-left: 50px;">
        <a href="/">
            <img src="{{ asset('dist/img/udenarbl.png') }}" class="elevation-2" style="width: 250px;margin:10px;" alt="User Image">
        </a>
    </div>
    <div class="col-md-6 " style="padding-top: 25px; text-align: center; font-size: 17px;">
        <p style="color:#ffffff;     font-size: 20px; font-weight: 900;"><b>Consultorios Jurídicos y Centro de Conciliación<br>"Eduardo Alvarado Hurtado"</b></p> 
    </div>
</div>
   
<div clas="row" style="text-align:center;margin:17px;">

     <p style="color:#000000;     font-size: 20px;">Sistema de atención de casos</p>   
</div>     




    <div id="app">
     
        <main class="py-4" style="padding-top: 3px !important;">
            @yield('content')
        </main>

    </div>
 
      
                       
        
    <footer class="footer" style="color: #d1941e !important">
  
      <div class="container container-footer" style="text-align:center;padding-bottom: 0px !important;">
        <img src="https://iurisapp.udenar.edu.co/dist/img/consultorios.png" class="d-none d-sm-inline-block" style="width: 400px;margin-top:-370px;opacity: 0.3;z-index: -1;position: absolute;margin-left: -401px;" alt="User Image">
        <div class="row" style="text-align: center; margin: 0px 30px 0px 30px;">
            <div class="col-md-4" style="text-align: left;">
                <span class="text-muted" style="color: #d1941e !important">
                    <span style="color:#d1941e;">
                    Contactos</span>
                    <br>
                    <i class="nav-icon fa fa-phone" style="margin-right: 7px;">
                    </i> 
                    (032)7244309 ext. 555
                    <br>
                    <i class="nav-icon fa fa-envelope" style="margin-right: 7px;"></i> 
                    infojuridicos@udenar.edu.co
                </span>
            </div>
                <div class="col-md-4">
                <span class="text-muted" style="color: #d1941e !important">
                    <a href="http://derecho.udenar.edu.co/" target="_blank">
                        Facultad de Derecho y Ciencias Políticas</a><br>
                        Acreditado en Alta Calidad<br>Res. 2160 05/02/2016
                    </span>
                </div>
                <div class="col-md-4" style="text-align: right;">
                <span class="text-muted" style="color: #d1941e !important"><a href="/register">
                    Registro (Estudiantes matriculados)</a><br>
                    <span style="font-size: 11px;">IURIS - AMATAI Ingeniería Informática SAS
                        <br>© {{date('Y')}}</span></span>
            </div>
        </div>  
          </div>
    </footer>



    
    
</body>
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js" integrity="sha384-XEerZL0cuoUbHE4nZReLT7nx9gQrQreJekYhJD9WNWhH8nEW+0c5qq7aIo2Wl30J" crossorigin="anonymous"></script>

<!-- SweetAlert2 -->
<script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<script src="{{ asset('js/app.js') }}" defer></script>
<script  src={{asset("js/config.js")}}></script> 
{!! Html::script('js/application.js?v=1')!!}
<script type="text/javascript">
var token = localStorage.getItem('tokensessionpc');
$(document).ready(function(){
    
     $('.onlynumber').keyup(function (){
        this.value = (this.value + '').replace(/[^0-9]/g, '');
      });
  
  $('.onlynumber').tooltip({
      placement: "top",
      trigger: "focus"
  });   
  $( ".onlynumber" ).focus();
    
    
    
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
@stack('scripts')
</html>
