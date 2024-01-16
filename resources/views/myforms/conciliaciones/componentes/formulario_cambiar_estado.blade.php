<form method="POST" class="form_store" enctype="multipart/form-data" accept-charset="UTF-8" id="myformCreateEstado">
                <input type="hidden" name="estado_id">
                <div class="form-group">
                    <label for="description">Cambiar a estado</label>
                   @include("myforms.conciliaciones.componentes.estados_select_list")       
                         
                </div>
                <div class="form-group">
                    <label for="description">Concepto</label>
                    <textarea name="concepto" required class="form-control"  rows="5"></textarea>                        
                </div> 

            {{--     <div class="form-group"> 
                    <label for="description">Subir archivo</label>
                    <input type="file" name="status_file">                      
                </div>  --}}
                  
                    <div class="row" id="alertmyReportList" style="display: none">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                Debe tener en cuenta que una vez asignado el nuevo estado 
                                podrá generar los siguientes formatos en la sección de documentos.
                            

                            <table class="table" id="myReportList">
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>

                        </div> 
                       
                    </div>


                <div class="col-md-12">
                    <div class="form-group">
                            <br>
                    <button type="submit" class="btn btn-block btn-primary btn-sm">
                        Cambiar estado
                    </button>
                    <button type="button" style="display:none" id="btn_ancel_upsoldoc" class="btn btn-block btn-default btn-sm">
                        Cancelar
                    </button>                            
                    </div>
                </div>
                </form>