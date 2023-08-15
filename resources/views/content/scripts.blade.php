<script>
     @if (Session::has('message-information'))
            var message = '';
            message += '<b><div class="alert alert-warning" style="font-size:18px">';
            message += '<h3>Iuris fue actualizado, refresque el navegador con las teclas Ctrl+F5</h3>';
            message += '</div></b>';           
            $("#modal-show-alerts-content").html(message);
            $("#mymodalShowAlerts").modal("show")
        @endif
</script>