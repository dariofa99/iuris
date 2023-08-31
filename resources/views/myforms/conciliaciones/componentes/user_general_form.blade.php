 <form id="myUserConciliacionesForm" data-view="user_general_form" data-content="user_gen_conciliacion_form">
       <div class="row">

  
       @include('myforms.users.formulario_registro',
        [
                "disabled"=> (isset($user) and $user !=null) ? 'disabled':''
        ])
        @include('myforms.components_user.identitaria',
        [
                "disabled"=> (isset($user) and $user !=null) ? 'disabled':''
        ])
        @include('myforms.components_user.socioeconomica',
        [
                "disabled"=> (isset($user) and $user !=null) ? 'disabled':''
        ]) 

        <div class="col-md-12">
                <div class="form-group">
                <label for="tipo_usuario">Tipo de usuario</label>
                <select name="tipo_usuario_id" id="tipo_usuario" class="form-control required">
                                <option value="">Seleccione</option>
                                @foreach(getReferencesTableByCategory('type_user_conciliacion') as $key => $value)
                                        <option   value="{{$value->id}}">{{$value->ref_nombre}}</option>
                                @endforeach
                </select>
                </div>
        </div>   
</div>
</form>