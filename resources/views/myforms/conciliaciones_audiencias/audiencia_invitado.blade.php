@extends('layouts.app')
@push('styles')
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
        </style>
@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" style="margin-bottom: 25px;">
                <div class="card-header"><b>Bienvenido(a) a la audiencia de conciliación.</b></div>
                <div class="card-body">
                    @include('msg.info')
                    <input type="hidden" id="user_session_idnumber" value="{{ $usuario->idnumber }}">
                    <div id='joinMsg'></div>
                    <div id="stamby_audiencia" style="height: 620px;width: 100%;background-color: rgba(25, 25, 25, 0.93);text-align: -webkit-center;padding-top: 100px; margin-bottom: 10px; display:none;">
                        <button id="btnvolver_audiencia" title="Volver a la audiencia" class="btn btn-primary btn-block btn-sm" style="width: 150px;">Volver a la audiencia</button>
                    </div>
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
                    <div class="row" >
                        <div class="col-md-2">
                            <input type="button" value="Acceder a sala alterna" class="btn btn-primary btn-block btn-sm" id="btm_access_room_alter{{ $id_conciliacion }}" @if($sala_alterna_url == "") style="display:none" @else onclick="openPopUpSalas('{{ $sala_alterna_url }}');" @endif>
                        </div>
                    </div>


                    <div class="iniciar_videollamada">
                        <input type="hidden" id="conciliacion_id" value="{{$conciliacion->id}}">
                        @include('myforms.conciliaciones.conciliacion_audiencia_chat')
                    </div>



                </div>

            </div>
        </div>
    </div>

</div>


@endsection
@push('scripts')

<!-- aqui van los scripts de cada vista -->
{!! Html::script('js/audiencia_conciliacion.js?v=1')!!}
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://meet.jit.si/external_api.js"></script>
{!! Html::script('js/config_jitsi.js?v=3')!!}
<script src="https://iurisapp.udenar.edu.co/plugins/new-push/io.js?v=1"></script>
<script src="https://iurisapp.udenar.edu.co/js/newpush.js?v=1"></script>

<script>




    function startvideollamada() { 

        if (numsalasvideollamada == 0) {
            var meeting_id = '{{ hash("sha256", md5($id_conciliacion), FALSE) }}'
            var nameroom = 'Sala de conciliación {{ $id_conciliacion }}'
            if (!meeting_id) {
                alert('Error al iniciar videollamada comuníquese con el administrador');
                return;
            }
            var dispNme = '{{$usuario->name}} {{$usuario->lastname}}';
            if (!dispNme) {
                dispNme = "Nuevo usuario";
            }
            $('#joinMsg').text('Conectando video llamada...').show();
            BindEvent();
            StartMeeting(meeting_id,dispNme,nameroom);
        }

    }





        $('[data-toggle="tooltip"]').tooltip()



    $(document).ready(function(){
        startvideollamada();
        $("#btnvolver_audiencia").on('click', function () {

            closePopUpSalas();
            startvideollamada();
            $(this).hide();
            $('#stamby_audiencia').hide(3000);
        })


    var idcon = '{{$id_conciliacion}}'
    socket.on('LIuOgI52dWJxe0ZMRoomAlterConciliacionDisabled'+idcon, function(data){
        //deshabilita la entrada a salas alterna en las audiencias de conciliacion
       //console.log('desactiva el acceso a las salas alternas')
       $( "#btm_access_room_alter"+idcon).css("display","none");
       $("#btm_access_room_alter"+idcon).removeAttr('onclick');
    });

    })
</script>
@endpush