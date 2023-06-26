<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lybra') }}</title>

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
{!! Html::style('/css/jitsi.css?v=2')!!}
<!-- aqui van los estilos de cada vista -->
<style>
            .container-meet {
                /*position: relative;
                border:1px red  solid;*/
                width: 100%;
                height:600px;
                margin-bottom: 30px;
                text-align: center;
            }
            .toolbox {
               /* position: absolute;*/
                bottom: 0px;
                /*border:1px black solid;*/
                width: 100%;
                height:50px;
                background-color: rgb(71, 71, 71);
            }
            #jitsi-meet-conf-container{
                width: 100%;
                height:570px;
            }
    .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
        position: relative;
        min-height: 1px;
        padding-left: 0px;
        padding-right: 0px;
    }

        </style>
</head>
<body class="content-wrapper" style="background-image: linear-gradient(-90deg,#c0c0c0 0,#ffffff 50%,#c0c0c0 100%);">

<div class="row" style="background-color: #222d32; opacity: 1; margin-right: 0px;" >

    <div class="col-md-12 " style="padding-top: 10px; text-align: center; font-size: 17px;">
        <p style="color:#ffffff;     font-size: 20px; font-weight: 900;"><b>Consultorios Jurídicos y Centro de Conciliación<br>"Eduardo Alvarado Hurtado"</b></p> 
    </div>
</div>

    <div id="app">


<div class="container" style="min-width: 100%;">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="text-align: center;"><b>{{$sala}}</b></div>
                <div class="card-body">
                    @include('msg.info')
                    <div id='joinMsg'></div>
                    <div id='container-meet' class="container-meet" style="display:none;">
                        <div id='jitsi-meet-conf-container'></div>
                        <div id="toolbox" class="toolbox" style="display:none;">
                            <button id='btnCustomMic' class="boton-redondo jitsi-mic" ><i class="fa fa-microphone" aria-hidden="true"></i></button>
                            <button id='btnCustomCamera' class="boton-redondo jitsi-cam">Cam</button>
                            <button id='btnCustomTileView' class="boton-redondo"  title="Cambiar vista"><i class="fa fa-th-large" aria-hidden="true"></i></button>
                            <button id='btnScreenShareCustom' class="boton-redondo" title="Compartir pantalla"><i class="fa fa-desktop" aria-hidden="true"></i></button>
                            <button id='btnHangup' class="boton-redondo jitsi-exit" title="Colgar">Colgar</button>
                        </div>
                    </div>



                </div>

            </div>
        </div>
    </div>

</div>



</div>


</body>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js" integrity="sha384-XEerZL0cuoUbHE4nZReLT7nx9gQrQreJekYhJD9WNWhH8nEW+0c5qq7aIo2Wl30J" crossorigin="anonymous"></script>
<!-- jQuery -->
{{-- <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script> --}}
<!-- SweetAlert2 -->
<script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
{!! Html::script('plugins/datepicker/js/moment.min.js')!!}
<!-- aqui van los scripts de cada vista -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://meet.jit.si/external_api.js"></script>
{!! Html::script('js/config_jitsi.js?v=3')!!}

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    $(function(){
            var meeting_id = '{{ hash("sha512", $access_room, FALSE) }}'
            var nameroom = '{{ $sala }}'
            if (!meeting_id) {
                alert('Error al iniciar videollamada comuníquese con el administrador');
                return;
            }
            var dispNme = '{{--$usuario->name--}} {{--$usuario->lastname--}}';
            if (!dispNme) {
                dispNme = "Nuevo usuario";
            }
            $('#joinMsg').text('Conectando...');
            BindEvent();
            StartMeeting(meeting_id,dispNme,nameroom);
    });
</script>


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


