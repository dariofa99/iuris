<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lybra') }}</title>

    <link rel="shortcut icon" href="{{ asset('dist/img/favicon.png') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
    html {
  position: relative;
  min-height: 100%;
}
body {
  margin-bottom: 60px; /* Margin bottom by footer height */
  color: #ffffff !important;
}

.content-wrapper {
    background-image: url('/dist/img/fondo1.jpg');
    background-color: #cccccc; 
    
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  
}

.card-header {
    position: relative;
    font-size: 17px !important;
    color: #fff;
    background-color: #ca2329c7;
}

.card {

    background-color: #00000082;
    min-height: 280px;
}

.btn-danger {

    background-color: #ca2329;
    
}

.footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 100px; /* Set the fixed height of the footer here */
  /*line-height: 30px;  */
  background-color: #191d36 !important;
}


.textfooter-right {
    text-align: right;
}
.textfooter-left {
    text-align: left;
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
  background-color: #191d36;
}

a {
    color: #CA2329;
}

.text-muted {
    color: #d8d3c3!important;
}


@media only screen and (max-width: 600px) {
.textfooter-right {
    text-align: center;
}
.textfooter-left {
    text-align: center;
}
}

    </style>
    
</head>
<body class="content-wrapper" >
     
<div class="row" style="background-color: #191d36; margin-right: 0px;" >
    <div class="col-md-3 image d-none d-sm-inline-block" style="padding-left: 50px;">
        <img src="{{ asset('dist/img/logo_web.png') }}" class="elevation-2" style="width: 250px;margin:10px;" alt="User Image">
    </div>
    <div class="col-md-6 " style="padding-top: 25px; text-align: center; font-size: 17px;">
        <p style="color:#ffffff;     font-size: 20px; font-weight: 900;"><b>Sistema de atención de usuarios Lybra<br>Oficina virtual</b></p> 
    </div>
</div>
   
<div clas="row" style="text-align:center;margin:17px;">

        
</div>     



    <div id="app">
      

        <main class="py-4" style="padding-top: 3px !important;">
            @yield('content') 
        </main>
    </div>

    <footer class="footer">
  
        <div class="container container-footer" style="text-align: end;">
         {{--     <img src="{{ asset('dist/img/leglis.jpg') }}" class="d-none d-sm-inline-block" style="width: 400px;margin-top:-370px;opacity: 0.3;z-index: -1;position: absolute;margin-left: -401px;" alt="User Image">--}}
    
            <div class="row" style="text-align: center; margin: 0px 30px 0px 30px;">
                <div class="col-md-4 textfooter-left">
                    <span class="text-muted"><span style="color:#CA2329;">Contactos</span><br><i class="nav-icon fa fa-phone" style="margin-right: 7px;"></i> (057) 3185460451<br><i class="nav-icon fa fa-envelope" style="margin-right: 7px;"></i> contacto@legalisabogados.com</span>
                </div>
                    <div class="col-md-4">
                    <span class="text-muted"><a href="https://legalisabogados.com/" target="_blank">LEGALIS ABOGADOS SAS</a><br>Oficinas: Calle 19 # 22-66 Centro<br>Pasto-Nariño</span>
                </div>
                    <div class="col-md-4 textfooter-right">
                    <span class="text-muted"><a href="https://juridicos.co" target="_blank">Sistema Lybra v1.0</a><br><span style="font-size: 11px;"><a href="https://amatai.co" target="_blank" style="color: #d8d3c3;">AMATAI Ingenieria Informatica SAS</a><br>Copyright © 2020</span></span>
                </div>
            </div>
            
            
        </div>
    </footer>


</body>
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

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
