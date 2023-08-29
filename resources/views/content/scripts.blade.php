<script>
  @if (Session::has('message-information'))
      var message = '';
      message += '<div class="alert alert-info" style="font-size:18px">';
      message += `<h4>
      <strong style="border-bottom:1px solid white">
        Bienvendido a {{ Str::upper(config('app.name')) }}!</strong> <br>
      Recuerda que estamos actualizando la plataforma, si presentas algún problema refresca el navegador
      con las teclas CTRL+F5 <i>o</i> CTRL+fn+F5 (portátiles). Tener en cuenta para conexión desde dispositivos móviles. <br>
      <i> <small> Últ. Act 29/08/2023 <br>
        Si presentas algún problema comunícate al 3106038006  
      </small></i>
    </h4>`
    
    message += '</div';
      $("#modal-show-alerts-content").html(message);
      $("#mymodalShowAlerts").modal("show")
  @endif
</script>