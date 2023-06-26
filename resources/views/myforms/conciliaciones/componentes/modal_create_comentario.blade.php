@component('components.modal')
	
	@slot('trigger')
		myModal_create_comentario
	@endslot

	@slot('title')
		Agregando comentario
	@endslot


	@slot('body')

	<div class="row">
		<div class="col-md-12">
	
    
            <form method="POST" class="form_store" accept-charset="UTF-8" id="myformCreateComentario">
    
      <input type="hidden" name="comentario_id">

        <div class="form-group">
                    <label for="description">Comentario</label>
                    <textarea name="comentario" class="form-control"  rows="5"></textarea>

            <div class="row">
                <div  class="col-md-6">	
                    <i>Compartir:  </i>	
                    <input type="hidden" name="compartido" value="0">
                    <input type="checkbox" name="compartido" value="1">							
                    {{--  <i class="fa fa-toggle-on switch-off" id="switch_edit" ></i> --}}
            
                </div>
            </div>  

        </div>
                     
                              

                    <div class="col-md-12">
                        <div class="form-group">
                            <br>
                            <button type="submit" class="btn btn-block btn-primary btn-sm">
                            Guardar
                            </button>

                            <button type="button" style="display:none" id="btn_cancel_upsoldoc" class="btn btn-block btn-default btn-sm">
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










