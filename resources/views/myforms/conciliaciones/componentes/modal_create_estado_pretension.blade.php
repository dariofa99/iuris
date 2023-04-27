@component('components.modal')
	
	@slot('trigger')
		myModal_create_estado_pretension
	@endslot

	@slot('title')
		Cambiando estado
	@endslot
	@slot('body')
	<div class="row">
		<div class="col-md-12">   
            <form method="POST" accept-charset="UTF-8" id="myformEditEstadoPretension">
                <input type="hidden" name="id">
                <div class="form-group">
                    <label for="description">Estado</label>
                    <select name="estado_id" class="form-control" required>
                       <option value="">Seleccione</option>
                       @foreach($types_status_pretension as $key => $type_status)                       
                            <option value="{{$key}}">{{$type_status}}</option>                     
                       @endforeach
                    </select>                   
                </div>
                               <div class="col-md-12">
                    <div class="form-group">
                            <br>
                    <button type="submit" class="btn btn-block btn-primary btn-sm">
                        Guardar
                    </button>
                    <button type="button" style="display:none" id="btn_ancel_upsoldoc" class="btn btn-block btn-default btn-sm">
                        Cancelar
                    </button>                            
                    </div>
                </div>
                </form>
		</div>		
	</div>
@endslot
@endcomponent
<!-- /modal -->










