@component('components.modal')
	
	@slot('trigger')
		myModal_create_document
	@endslot

	@slot('title')
		Agregando anexo
	@endslot


	@slot('body')

	<div class="row">
		<div class="col-md-12">
			<form method="POST" class="form_store" accept-charset="UTF-8" id="myformCreateConciliacionAnexo" enctype="multipart/form-data">
                                <input type="hidden" name="file_id">
<div class="form-group">
            <label for="description">Concepto</label> 
           <input type="text" class="form-control " required="" name="concept" id="concept_id">
</div>

     
    
        <div class="form-group">
        <div class="custom-file">
                <input required="" type="file" name="conciliacion_file" class="custom-file-input" id="conciliacion_file">
                <label class="custom-file-label" for="customFile"></label>
        </div>
        </div>                         
                              
                              

                    <div class="col-md-12">
                        <div class="form-group">
                            <br>
                            <button type="submit" class="btn btn-block btn-primary btn-sm">
                            Guardar
                            </button>

                            <button type="button" style="display:none" id="btn_cancel_upsoldo" class="btn btn-block btn-default btn-sm">
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










