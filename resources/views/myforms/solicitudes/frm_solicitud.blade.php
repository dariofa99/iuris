<form id="myformEditSolicitud">

  <input disabled value="{{$solicitud->tipodoc_id}}" name='tipodoc_id'  type="hidden" class="form-control">
  <input disabled value="{{$solicitud->id}}" name='id'  type="hidden" class="form-control">
        
<div class="row">

  <div class="col-md-9">
    <div class="row">
      <div class="col-md-6">	
          <div class="form-group"><label for="soli_idnumber">Número de documento</label>
            <input id='soli_idnumber' disabled value="{{$solicitud->idnumber}}" name='idnumber'  type="text" class="form-control">
          </div>  
      </div>
      <div class="col-md-6">
        <div class="form-group">
      <label for="estrato_id">Estrato</label>
            	<select name="estrato_id" disabled id="estrato_soli_id" class="form-control required" require>
				<option value="">Seleccione...</option>
				@foreach($estrato as $tipo) 
				<option {{$tipo->id==$solicitud->estrato_id ? 'selected':''}} value="{{$tipo->id}}">{{$tipo->ref_nombre}}</option>
				@endforeach
			</select>
        </div>  
      </div>
  <div class="col-md-6">
                            <div class="form-group "><label for="soli_name">Nombres</label>
                            <input disabled id='soli_name' value="{{$solicitud->name}}" name='name' type="text" class="form-control" placeholder="Tu nombre">
                        
                            </div>
  </div> 
    <div class="col-md-6">
          <div class="form-group "><label for="soli_lastname">Apellidos</label>
            <input id='soli_lastname' disabled value="{{$solicitud->lastname}}" name='lastname' type="text" class="form-control" placeholder="Tu apellido">
    </div>
    </div>
    <div class="col-md-6">
        <div class="form-group "><label for="tel1">Número de contacto</label>
          <input id='tel1_' disabled value="{{$solicitud->tel1}}" name='tel1' type="text" class="form-control" placeholder="Tu apellido">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group has-feedback"><label for="description">Descripción</label>
            <textarea disabled name="description" id="description_" class="form-control" rows="6">{{$solicitud->description}}</textarea>
        </div>
    </div>
    </div>
  </div>
  <div class="col-md-3">
      @if($solicitud->type_status_id==155 || $solicitud->type_status_id==156)    
      <br>   
            <div class="form-group" id="btns_change_s"> 
            @if($solicitud->type_status_id==155) 
              <button type="button"  id="btn_acept_solicitud" data-id="{{$solicitud->id}}" class="btn btn-success btn-block">
              Aceptar Solicitud</button>
            @endif                
            </div>              
      @endif 
      @if($solicitud->type_status_id==165 || $solicitud->type_status_id==155 || $solicitud->type_status_id==156 || $solicitud->type_status_id==155 || $solicitud->type_status_id==171)
      <div class="form-group" > 
        <a href="#" id="btn_denied_solicitud"  class="btn btn-warning btn-block">Rechazar</a>
      </div>
      @endif

      @if($solicitud->type_status_id==155 || $solicitud->type_status_id==156)
        <div class="form-group"> 
              <button type="button"  id = "btn_videollamada" data-type="{{ $solicitud->idnumber }}" class="btn btn-info btn-block">Vídeo llamada</button>
        </div>
      @endif    
      
      
         
      
  </div>
</div>

</form>