<form id="myFormParteSolicitante" method="POST">
  <div class="row">
    @include('myforms.users.formulario_registro')

    @include('myforms.components_user.identitaria')
    @include('myforms.components_user.socioeconomica')
  </div>
   <div class="row">  
    <div class="col-md-12">
        <input type="hidden" name="sede_id">
        <label>
            Selecciona una sede*
        </label>
    </div>
    @foreach($sedes as $key => $sede)    
    <div class="col-md-4 selected_sede">
       <div class="card">
                <!-- Default panel contents -->
                <div class="card-header">{{$sede->nombre}}</div>
                <div class="card-body">                    
                <p>{{$sede->ubicacion}}</p>
            
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
  </div>
</form>