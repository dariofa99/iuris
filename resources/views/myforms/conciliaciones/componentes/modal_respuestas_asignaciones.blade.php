@component('components.modal_dynamic_cols')
	
	@slot('trigger')
		myModal_respuestas_asignaciones
	@endslot

	@slot('title')
    <label> Responder </label>
	@endslot

    @slot('cols')
		col-md-8 col-md-offset-2
	@endslot 
	@slot('body')
	<div class="row" id="content_respuesta" >
		<div class="col-md-12">
            
            <form id="myFormResponderCorreo">
                <input type="hidden" name="cuerpo_correo">
                <input type="hidden" name="user_estado_id">
                <input type="hidden" name="pivot_id">
                <div class="form-group">
                    
                    <div id="content_form_correo_est_responder" style="height: 50px !important" class="summernote">
                        
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Enviar</button>
                </div>

            </form>
        </div>	
	</div>
   
@endslot
@endcomponent
<!-- /modal -->










