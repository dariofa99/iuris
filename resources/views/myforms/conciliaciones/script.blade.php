<script>
    function startvideollamada() {
        if (numsalasvideollamada == 0) {
            var meeting_id = '{{ hash("sha256", md5($conciliacion->id), FALSE) }}'
            var nameroom = 'Sala de conciliación {{ $conciliacion->id }}'
            if (!meeting_id) {
                alert('Error al iniciar videollamada comuníquese con el administrador');
                return;
            }
            var dispNme = '{{Auth::user()->name}} {{Auth::user()->lastname}}';
            if (!dispNme) {
                dispNme = "Nuevo usuario";
            }
            $('#joinMsg').text('Conectando video llamada...').show();
            BindEvent();
            StartMeeting(meeting_id,dispNme,nameroom);
        }
    }
    $(function(){  
        $("#btn_iniciar_videollamada").on('click', function () {
            $(this).prop( "disabled", true );
            $('.iniciar_videollamada').css('display','block')
            startvideollamada();
        });



    $("#btnvolver_audiencia").on('click', function () {
        closePopUpSalas();
        startvideollamada();
        $(this).hide();
        $('#stamby_audiencia').hide(3000);


    })


    });
</script>