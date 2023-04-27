@component('components.modal')
	
	@slot('trigger')
        myModalCreateConcHechosPretenciones
	@endslot

	@slot('title')
	<label id="lbl_title_modal">Agregando Información</label>
	@endslot


	@slot('body')

	<div class="row">
		<div class="col-md-12">
	
    
            <form method="POST" class="form_store" accept-charset="UTF-8" id="myformCreateHechoPretencion">
    
      <input type="hidden" name="id">
      <input type="hidden" name="tipo_id">
        <div class="form-group">
                <label for="description">Descripción</label>
                <textarea name="descripcion" class="form-control"  rows="5"></textarea>
        </div>
     
                  
                              

                    <div class="col-md-12">
                        <div class="form-group">
                            <br>
                            <button type="submit" class="btn btn-block btn-primary btn-sm">
                            Guardar
                            </button>

                            
                    </div>
                    </div>
                </form>
		</div>
		
	</div> 
 	

	@endslot
@endcomponent
<!-- /modal -->










