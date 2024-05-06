<div class="row" id="content-video-call" style="display: none">
    <div class="col-md-12">

        <div id='joinMsg'></div>
       
        <div id='container-meet' class="container-meet" style="display:blcok;">
            <div id='jitsi-meet-conf-container' style="height: 600px"></div>
            <div id="toolbox" class="toolbox" style="display:block;">
                <button id='btnCustomMic' class="boton-redondo jitsi-mic" ><i class="fa fa-microphone" aria-hidden="true"></i></button>
                <button id='btnCustomCamera' class="boton-redondo jitsi-cam">Cam</button>
                <button id='btnCustomTileView' class="boton-redondo"  title="Cambiar vista"><i class="fa fa-th-large" aria-hidden="true"></i></button>
                <button id='btnScreenShareCustom' class="boton-redondo" title="Compartir pantalla"><i class="fa fa-desktop" aria-hidden="true"></i></button>
                <button id='btnHangup' class="boton-redondo jitsi-exit" title="Colgar">Colgar</button>
            </div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-10">    
        {!! \Facades\App\Facades\ApiChat::room($token)->render() !!}
    </div>
    <div class="col-md-2">
        <button class="btn btn-success btn_create_document">
            Compartir documentos
        </button>
        <button class="btn btn-warning btn_activate_video mt-3">
            Activar video llamada
        </button>
            </div>
</div>


 