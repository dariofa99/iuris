<?php 

if(!isset($modo_nav_crl)){

//barra lateral izquierda
$modo_nav_crl="light";
$sidebar_modo="dark";
$sidebar_color="#191d36";
$sidebar_brand_modo="dark";
$sidebar_brand_color="#191d36";
//barra navegación superior 
$color_nav="white";
$modo_nav="light";

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
  <title>{{config('app.name')}}</title>
  
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
  {!! Html::style('/css/styles.css?v=4.0')!!}

  <style>
    body{
      font-family:   'Roboto', sans-serif;
      
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
      @include('layouts.sidebar') 
    <!-- /:main Sidebar Container -->
    
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      
      <!-- Content Header (Page header) -->  
       <input type="hidden" id="olderInputValue">
       <input type="hidden" id="inputHash" value="{{sha1(Auth::user()->id)}}">
       <input type="hidden" id="connectedData" value='{"ver_conectados_chat":"{{Auth::user()->can("ver_conectados_chat") ? "true": "false"}}","role":"{{(Auth::user()->roles[0]->name)}}","username":"{{Auth::user()->name}}","idusuario":"{{Auth::user()->id}}","correo":"{{ Auth::user()->email }}","imagen":"{{ asset(auth()->user()->image) }}"}'>
        
       <div class="card card-success card-outline">
        <div class="card-header">
          @yield('titulo_area')</h3>
          @yield('area_buttons')
        </div>
        <div class="card-body">
          @yield('area_forms')
        </div>
       </div>
      
   
    
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-{{ $modo_nav_crl  }}">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
      @include('layouts.footer')
    <!-- /:main Footer -->
  </div>
  <!-- ./wrapper -->
   <!-- wait  es la barra de carga de la pagina-->
  <div id="wait" style="display:none; position: absolute; width: 100%;min-height: 100%;height: auto;position: fixed;top:0; left:0;background-color: rgba(236, 240, 245, 0.8);" >
    <div class="container" style="margin-top:15%;padding:2px;">
      <div class="row justify-content-md-center">
        <div class="col col-lg-2">
         
        </div>
        <div class="col-md-auto text-center ">
          <img src="{{asset('img/logo2.png')}}" id="load" width="67" height="71"/><br>
          <span style="color:#515151;font-size: 16px;">Cargando...</span>
          
        </div>
        <div class="col col-lg-2">
        
        </div>
      </div>
            <div class="row justify-content-md-center">
        <div class="col col-lg-2">
         
        </div>
        <div class="col-md-auto text-center justify-content-center" >
          <div class="progress" id="progressbarwait"  style="min-width: 350px; height: 21px; background-color: #a5a5a5; display: none; ">
	          <div class="progress-bar progress-bar-striped" id="progressGeneral" style="width:0%; height: 21px;">0%</div>
          </div>        
        </div>
        <div class="col col-lg-2">
        
        </div>
      </div>
    </div>

  </div>
  <!-- ./wait  es la barra de carga de la pagina-->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI -->
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.js')}}"></script>
<!-- moment -->
<script src="{{ asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{ asset('plugins/moment/locale/es-us.js')}}"></script>
<!-- NewPush -->
<script>var tokendefault = '';</script>
{{-- <script src="{{ asset('plugins/new-push/io.js?v=1')}}"></script> --}}



<!-- OPTIONAL SCRIPTS -->
<script src="{{asset('dist/js/demo.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<!-- PAGE PLUGINS -->
{{-- <script src="{{ asset('plugins/alertifyJS/alertify.min.js') }}"></script>
{!! Html::script('plugins/amcharts/amcharts.js')!!}
{!! Html::script('plugins/amcharts/serial.js')!!}
{!! Html::script('plugins/amcharts/pie.js')!!}
 --}}
<!-- ChartJS -->
<!-- PAGE SCRIPTS -->

  <!-- our scripts -->
  <!-- /propios -->
<script  src={{asset("js/config.js?v=1")}}></script> 
{!! Html::script('js/application.js?v=1')!!}
{{-- {!! Html::script('scripts_serv.js?v=3.1')!!}
{!! Html::script('js/AdminRoles.js?v=3.1')!!}
{!! Html::script('js/java.js?v=3.1')!!}
{!! Html::script('js/graficas.js?v=3.1')!!}
{!! Html::script('js/excel.js?v=3.1')!!} --}}
 

  <script>//para que funcionen los tooltip
    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
      $("#wait").hide()
    });
  </script>
  <!-- PAGE PLUGINS -->
@stack('scripts')
@include('content.scripts') 



