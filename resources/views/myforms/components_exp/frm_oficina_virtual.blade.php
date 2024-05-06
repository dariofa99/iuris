<div class="row">
    @if (count($expediente->solicitudes) > 0)
   
        <div class="col-md-8">           
            @include('content.chat.chat', [
                    'token' => $expediente->solicitudes[0]->number,
                ])
        </div> 
       {{--  <div class="col-md-4">
            <button class="btn btn-success btn-sm btn-block" id="btn_adm_documentos">Documentos</button>
            <button class="btn btn-warning btn-sm btn-block" data-type="151"
                id="btn_adm_notificaciones">Notificaciones</button>

            @if (currentUser()->can('crear_videollamada_oficina_vir'))
                <button class="btn btn-info btn-sm btn-block" id="btn_videollamada"
                    data-type="{{ $expediente->expidnumber }}">Video llamada</button>
            @endif
        </div> --}}
    @else
        <div class="col-md-12">
            <div class="alert alert-info">
                <h5>El usuario no esta activo o no tiene una solicitud. Para activar actualice la información del
                    usuario en datos del caso.</h5>
            </div>
        </div>
    @endif
</div>
