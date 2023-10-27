<script> 
    @if (Session::has('message-information') && config('app.name') != 'ConciliApp')
        var message = '';
        message += '<div class="alert alert-success" style="font-size:18px">';
        message += `<h4>
      <strong style="border-bottom:1px solid white">
        Bienvendido a {{ Str::upper(config('app.name')) }}!</strong> <br>
      Recuerda que estamos actualizando la plataforma, si presentas algún problema refresca el navegador
      con las teclas CTRL+F5 <i>o</i> CTRL+fn+F5 (portátiles). Tener en cuenta para conexión desde dispositivos móviles. <br>
      
    </h4> </div>`;
    message += `<span> Últ. Atualización: 23 de oct. 2023 <br>
        Si el problema persiste comunícate al 3106038006  
      </span>`

        var keyCir = localStorage.getItem("keyCir");
        if (keyCir == null) {            
            message = `<embed  src="{{ asset('recursos/Circular.pdf#toolbar=0') }}" id="pdfViewer" >`
            message += `<button class="btn btn-success" id="btnNotFalse" sandbox >No volver a mostrar!</button>`

        }
        $("#modal-show-alerts-content").html(message);
        $("#mymodalShowAlerts").modal("show");
    @endif
    $("#mymodalShowAlerts").on("click",'#btnNotFalse',function (e) {
      localStorage.setItem('keyCir', true);
      $("#mymodalShowAlerts").modal("hide");
      e.preventDefault();

    })
</script>
