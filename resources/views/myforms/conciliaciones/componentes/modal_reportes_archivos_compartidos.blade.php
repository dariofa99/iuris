@component('components.modal_dynamic_cols')
	
	@slot('trigger')
		myModal_reportes_archivos_compartidos
	@endslot

	@slot('title')
		Reportes 
	@endslot

    @slot('cols')
		col-md-6 col-md-offset-3
	@endslot 
	@slot('body')
	
    <div class="row">
       <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#files">Compartir</a></li>
            <li><a data-toggle="tab" href="#compartidos">Compartidos</a></li>
           
          </ul>
          
          <div class="tab-content">
            <div id="files" class="tab-pane fade in active">
                <div class="row" id="content_msg_info">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h4>
                             <i class="fa fa-info-circle"></i>   <strong>No hay archivos para compartir.</strong>
                            </h4>
                           
                        </div>
                    </div>
                </div>
              <form id="myFormCompartirDocumento" method="POST">
                <input type="hidden" name="status_id">
                <input type="hidden" name="means_id" value="218">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category_id">Destinatario</label>
                            <select required class="form-control" name="category_id" id="category_id">
                                <option value="214">Partes de la conciliación</option>
                                <option value="215">Entidad</option>
                                <option value="216">Tercero</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category_id">Medio</label>
                            <select required disabled class="form-control" name="means_id" id="means_id">
                                <option value="218">Correo electrónico</option>
                                <option value="217">Enlace</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-9" id="content_shmail" style="display: none">
                       

                        <div class="input-group">
                            <input type="email" disabled id="input_email" class="shared_mail form-control" placeholder="Ingrese un correo electrónico" aria-describedby="basic-addon2">
                            <span class="btn btn-success input-group-addon" id="btn_addmail"><i class="fa fa-plus"></i></span>
                          </div>

                    </div>
                    

                    <div class="col-md-12" id="content_usermail" style="display: block">
                        
                            <div class="row" id="tbl_list_mail_partes">
                                                       
                            </div>
                        
                    </div>

                </div>
                <div class="row" id="content_datashared" style="display: none">
                    <div class="col-md-12">
                        <div id="content_url" style="word-break: break-all;">
                           
                            <div class="form-group" id="lbl_copy">
                               
                                Clave:  <label id="lbl_clave">  xB6yhf</label><br>
                                Enlace:                                
                                 <label id="lbl_url"></label>
                            
                            </div>
                           </div>
                        <a href="#" id="enlace_copiar">Copiar</a>
                    </div>
                </div>
               <div id="content_files">

             

                <div class="row">
                    <div class="col-md-8">
                       <strong>
                        Nombre del archivo
                        </strong> 
                    </div>
                    <div class="col-md-2">
                     <strong>
                        Descargar
                        </strong>  
                    </div>
                    <div class="col-md-2">
                        <strong>
                            Compartir
                        </strong> 
                     </div>
                </div>

                             
                <div style="overflow: auto; max-height:300px">
                    <div class="table" id="tbl_list_archivos_comp">       
                   
                    </div>
                    
                </div>
                <button id="btn_compcon_file" disabled class="btn btn-sm btn-primary">Compartir</button>
            </div>
            </form>  


            </div>
            <div id="compartidos" class="tab-pane fade">
              
                <div class="row">
                    <div class="col-md-2">
                        <label>
                            Clave
                        </label>
                    </div>
                    <div class="col-md-8">
                        <label>
                            Enlace
                        </label>
                    </div>
                </div>

              <div id="content_compartidos">

              </div>

  

            </div>
            
          </div>
       </div>
    </div>
@endslot
@endcomponent
<!-- /modal -->










