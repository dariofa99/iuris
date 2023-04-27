<div class="row">
<div class="col-md-8">
<input type="hidden" id="tokenc" value="">
<div class="embed-responsive embed-responsive-4by3" style=" height: 500px; ">
   {!! \Facades\App\Facades\NewChat::room($solicitud->number)->render() !!}
</div> 
</div>
<div class="col-md-4">
<button class="btn btn-success btn-sm btn-block"  id="btn_adm_documentos">Documentos</button>
@if(count($solicitud->expedientes)>0)
<button class="btn btn-warning btn-sm btn-block"  id="btn_adm_notificaciones">Notificaciones</button>
@endif
</div> 
</div>