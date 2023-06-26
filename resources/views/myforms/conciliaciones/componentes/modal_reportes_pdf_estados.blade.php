@component('components.modal_dynamic_cols')
	
	@slot('trigger')
		myModal_reportes_pdf_estados
	@endslot

	@slot('title')
		Reportes 
	@endslot

    @slot('cols')
		col-md-8 col-md-offset-2
	@endslot 
	@slot('body')
	<div class="row" id="content_user_pdf_list" >
		<div class="col-md-12">               
            <table class="table" id="myReportPdfList">
                <thead>
                    <th>
                        Nombre 
                    </th>
                    <th colspan="5">
                        Acciones
                    </th>
                 </thead>
                <tbody>
                    
                </tbody>
            </table>
                   
		</div>		
	</div>
    <div class="row" id="content_user_pdf_firmas" style="display: none">
        <form id="myFormAsigFirmaPdf">
            <input type="hidden" name="estado_id">
            <div class="col-md-12">
                <h4>
                    Documento: <label id="lbl_pfd_report_name">Citacion en</label>
                </h4> 
            </div>
            <div class="col-md-9">
               
                <button type="submit" id="btn_enviar_email"  class="btn btn-primary btn-sm">Enviar y Guardar</button>
           
                <button type="button" id="btn_cancelar_asig_user" class="btn btn-default btn-sm ">Cancelar</button>
                
                @if(currentUser()->can('gen_doc_pdf_conciliaciones'))
                <button type="button" data-status_id="0" data-reporte_id="0" id="btn_generar_pdf" class="btn btn-success btn-sm btn_gene_pdf">Generar documento</button>
                @endif
               @if(currentUser()->can('revoc_firma_formatos_conciliac'))
               <button type="button" id="btn_revocar_firmas" data-estado_id="0" data-reporte_id="0" class="btn btn-danger btn-sm  btn_revocar_firmas">
                 Revocar firmas electrónicas
                </button>
               @endif

            </div>
            <div class="col-md-3">
                <button type="button" id="btn_select_volver_enviar_email" class="btn btn-warning btn-sm">Volver a envíar E-mail</button>
                <button type="button" style="display: none" id="btn_volver_enviar_email"  class="btn btn-primary btn-sm">
                    Reenviar emails
                </button>

            </div>
            <div class="col-md-12">
                
                <table class="table" id="table_list_pdf_users">
                    <thead>
                        <th>
                            Nombre 
                        </th>
                        <th>
                            Tipo
                        </th>
                        <th>
                            Tipo de firma
                        </th>
                        <th>
                            Envíar E-mail
                        </th>
                        <th class="volver_enviar_mail" style="display: none">
                          Envíar E-mail
                        </th>
                     </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </form>       
    </div>
    <div class="row" id="content_personalized_values_pdf" style="display: none">
        <form id="myFormEditPersonalizedReportValues">
           <input type="hidden" name="reporte_id">
        <div class="col-md-12">
                 <table class="table" id="table_personalized_values_pdf">
                <thead>
                    <th>
                        Campo
                    </th>
                    <th>
                        Valor
                    </th>
                </thead>
                <tbody>                 
                                   
                </tbody>
                </table>
               
        </div>
        <div class="col-md-4">
            <button type="submit">Enviar</button>
        </div>
    </form>
    </div>
@endslot
@endcomponent
<!-- /modal -->










