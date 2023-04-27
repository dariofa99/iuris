@if($solicitud->type_status_id == 155 || $solicitud->type_status_id == 157  || $solicitud->type_status_id == 158)
<div class="row">
    <div class="col-md-8">
  <div class="alert alert-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
     <strong>Atención!</strong>
    Los mensajes del chat no serán recibidos hasta aceptar la solicitud.
  </div> 
    </div>
</div>
@endif
<div class="row">
<div class="col-md-8">
<input type="hidden" id="tokenc" value="">
<div class="embed-responsive embed-responsive-4by3" style=" height: 500px; ">
    {!! \Facades\App\Facades\NewChat::room($solicitud->number)
    ->can_write(($solicitud->type_status_id == 157  || $solicitud->type_status_id == 158) ? false : true)->render() !!}
</div> 
</div> 
<div class="col-md-3">
 
<button @if($solicitud->user==null || ($solicitud->type_status_id == 155 || $solicitud->type_status_id == 157  || $solicitud->type_status_id == 158)) data-toggle="tooltip" title="Para habilitar el registro debes aceptar la solicitud y esperar el registro del usuario" disabled @endif class="btn btn-success btn-sm btn-block"  id="btn_adm_documentos">
  Documentos
</button>
<button type="button"  id = "btn_videollamada" data-type="{{ $solicitud->idnumber }}" class="btn btn-info btn-block">Vídeo llamada</button>
 
@if($solicitud->user!=null and ($solicitud->type_status_id==156 || $solicitud->type_status_id==162 || $solicitud->type_status_id==155 || $solicitud->type_status_id==165))                    
<button class="btn btn-primary btn-block" value="{{$solicitud->idnumber}}" id="btn_exp_user_find" type="button">
Actualizar datos de usuario</button>
@endif

@if($solicitud->user==null and ($solicitud->type_status_id==155 || $solicitud->type_status_id==156))
<button @if($solicitud->type_status_id==155) data-toggle="tooltip" title="Para habilitar el registro debes aceptar la solicitud" disabled @endif class="btn btn-success btn-block" data-id="{{$solicitud->id}}" value="{{$solicitud->idnumber}}" id="btn_active_reguser" type="button">
Habilitar registro a usuario
</button>
@endif 
@if( $solicitud->user==null and ($solicitud->type_status_id==155 || $solicitud->type_status_id==156 || $solicitud->type_status_id==171))
<button @if($solicitud->type_status_id==155)  data-toggle="tooltip" title="Para habilitar el registro debes aceptar la solicitud" disabled @else id="btn_going_chat" @endif class="btn btn-primary btn-block" value="{{$solicitud->idnumber}}" id="btn_reg" type="button">
Registrar usuario
</button>
@endif


{{-- @if($solicitud->type_status_id == 165)
{{-- <button class="btn btn-primary btn-sm btn-block" id="btn_aprob_solicitud" data-id="{{$solicitud->id}}">Aprobar caso</button>  
<a href="#" id="btn_denied_solicitud"  class="btn btn-warning btn-sm btn-block">Rechazar</a>
@endif --}}
</div>
</div>  
