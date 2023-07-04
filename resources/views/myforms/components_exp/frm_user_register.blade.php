<form id="{{isset($user) ? 'myFormUserEditExpediente': 'myFormUserCreateExpediente'}}" method="POST">
   <div class="row">
    @include('myforms.users.formulario_registro')   
  
     @include('myforms.components_user.identitaria')
    @include('myforms.components_user.socioeconomica')
</div>
    <button type="button" id="{{isset($user) ? 'actualizar_exp_us': 'registrar_exp_us'}}" class="btn btn-primary btn-block"> Asignar usuario </button>
</form> 