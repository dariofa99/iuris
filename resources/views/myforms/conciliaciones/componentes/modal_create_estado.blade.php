@component('components.modal')
	
	@slot('trigger')
		myModal_create_estado
	@endslot

	@slot('title')
		Cambiando estado
	@endslot
	@slot('body')
	<div class="row">
		<div class="col-md-12">   
            <form method="POST" class="form_store" enctype="multipart/form-data" accept-charset="UTF-8" id="myformCreateEstado1">
                <input type="hidden" name="estado_id">
                <div class="form-group">
                    <label for="description">Estado</label>
                   @include("myforms.conciliaciones.componentes.estados_select_list")       
                         
                </div>
                <div class="form-group">
                    <label for="description">Concepto</label>
                    <textarea name="concepto" class="form-control"  rows="5"></textarea>                        
                </div> 

                <div class="form-group"> 
                    <label for="description">Subir archivo</label>
                    <input type="file" name="status_file">                      
                </div> 
                    <div class="row">
                    <div class="col-md-12">
                        <table class="table" id="myReportList">
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <div class="row" id="alertmyReportList" style="display: none">
                        {{-- <div class="col-md-12">
                            <div class="alert alert-info">
                                Debe tener en cuenta que una vez guardado el estado se generaran los archivos correspondientes en pdf y estos no se podrán modificar
                            </div>
                        </div> --}}
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










