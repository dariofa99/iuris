var apiObj = null;
var numsalasvideollamada=0;

function BindEvent(){

    $("#btnHangup").on('click', function () {
        numsalasvideollamada = 0;
        $("#btn_iniciar_videollamada").prop( "disabled", false );
        apiObj.executeCommand('hangup');
        //$( "#jitsi-meet-conf-container" ).remove();
        $('#jitsi-meet-conf-container').empty();
        $('#container-meet').hide();
    });
    
    $("#btnCustomMic").on('click', function () {
        apiObj.executeCommand('toggleAudio');
    });
    $("#btnCustomCamera").on('click', function () {
        apiObj.executeCommand('toggleVideo');
    });
    $("#btnCustomTileView").on('click', function () {
        apiObj.executeCommand('toggleTileView');
    });
    $("#btnScreenShareCustom").on('click', function () {
        apiObj.executeCommand('toggleShareScreen');
    });
}

function StartMeeting(roomName,dispNme,nameAlt){
    numsalasvideollamada = 1;
    const domain = 'meet.jit.si';

    //var roomName = 'newRoome_' + (new Date()).getTime();

    const options = {
        roomName: roomName,
        width: '100%',
        height: '100%',
        parentNode: document.querySelector('#jitsi-meet-conf-container'),
        DEFAULT_REMOTE_DISPLAY_NAME: 'Nuevo Usuario',
        userInfo: {
            displayName: dispNme
        },
        configOverwrite:{
            doNotStoreRoom: true,
            startVideoMuted: 0,
            startWithVideoMuted: true,
            startWithAudioMuted: true,
            enableWelcomePage: false,
            prejoinPageEnabled: false,
            disableRemoteMute: true,
            remoteVideoMenu: {
                disableKick: true
            },
        },
        interfaceConfigOverwrite: {
            filmStripOnly: false,
            SHOW_JITSI_WATERMARK: false,
            SHOW_WATERMARK_FOR_GUESTS: false,
            DEFAULT_REMOTE_DISPLAY_NAME: 'Nuevo Usuario',
            TOOLBAR_BUTTONS: []
        },
        onload: function () {
            //alert('loaded');
            $('#joinMsg').hide();
            $('#container-meet').show();
            $('#toolbox').show();
        }
    };
    apiObj = new JitsiMeetExternalAPI(domain, options);

    apiObj.addEventListeners({
        readyToClose: function () {
            numsalasvideollamada = 0;
            //alert('going to close');
            $('#jitsi-meet-conf-container').empty();
            $('#toolbox').hide();
            $('#container-meet').hide();
            $('#joinMsg').show().text('Meeting Ended');
        },
        audioMuteStatusChanged: function (data) {
            //iconos microfono
            if(data.muted) {
                $("#btnCustomMic").html('<i class="fa fa-microphone-slash" aria-hidden="true"></i>');
                //$("#btnCustomMic").tooltip({title: "Encender micrófono"});
                $("#btnCustomMic").prop('title','Encender micrófono');

            } else {
                $("#btnCustomMic").html('<i class="fa fa-microphone" aria-hidden="true"></i>');
              //  $("#btnCustomMic").tooltip({title: "Apagar micrófono"});
                $("#btnCustomMic").prop('title','Apagar micrófono');
            }
        },
        videoMuteStatusChanged: function (data) {
            if(data.muted) {
                $("#btnCustomCamera").html('<i class="fas fa-video-slash"></i>');
                $("#btnCustomCamera").prop('title','Encender Cámara');
                //$("#btnCustomCamera").tooltip({title: "Apagar micrófono"});
            } else {
                $("#btnCustomCamera").html('<i class="fas fa-video"></i>');
                $("#btnCustomCamera").prop('title','Apagar Cámara');
                //$("#btnCustomCamera").tooltip({title: "Apagar Cámara"});
            }

        },
        tileViewChanged: function (data) {

        },
        screenSharingStatusChanged: function (data) {
            if(data.on) {
                $("#btnScreenShareCustom i").css("color", "red");
                $("#btnScreenShareCustom").prop('title','Dejar de compartir pantalla');
            } else {
                $("#btnScreenShareCustom i").css("color", "white");
                $("#btnScreenShareCustom").prop('title','Compartir pantalla');
            }
        },
        participantJoined: function(data){
            console.log('participantJoined', data);
        },
        participantLeft: function(data){
            console.log('participantLeft', data);
        }
    });

    apiObj.executeCommand('subject', nameAlt);
}