<form id="myFormParteSolicitante" method="POST">
    @include('myforms.users.formulario_registro')

    @include('myforms.components_user.identitaria')
    @include('myforms.components_user.socioeconomica')
    <div class="col-md-12">
        <input type="hidden" name="sede_id">
    <label>
        Selecciona una sede
    </label>
    </div>
    @foreach($sedes as $key => $sede)    
    <div class="col-md-4 selected_sede">
       <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading">{{$sede->nombre}}</div>
                <div class="panel-body">                    
                        <p>{{$sede->ubicacion}}</p>
                </div>
              <div class="panel-footer" align="center">
                @if($sede->getActivePeriod())
                  <button data-id="{{$sede->id_sede}}" type="button" class="btn btn-primary btn_change_sede">
                    Seleccionar
                  </button>
                  @else
                     <small class="badge bg-warning">
                     <i class="fa fa-exclamation-circle"></i> No tiene un período activo
                    </small>
                 
                @endif
              </div>
            </div>           
    </div>     
    @endforeach
</form>