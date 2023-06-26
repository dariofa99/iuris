
<div class="row">
	<div class="col-md-12">
	<div class="box-body table-responsive no-padding">
		<table id="tbl_ajax" class="table dataTable dataTable" role="grid">
	
	  <thead>
		<tr>
		  <th>Actuación</th>
		  <th>Descripción</th>
		  <th>Estado</th>
		  <th>Fecha</th>
		  <th>Archivo</th>	
		  <th width="5px">Acción</th>	                      
		</tr>
	  </thead>
	 <tbody id="datos">
	
				 
	 
	 </tbody>
	</table>
	</div>
	</div>
</div>

<div class="row">
<div class="col-md-4">
	@if($expediente->exptipoproce_id != '1')
	<button class="btn btn-default" id="search_previous_act"><i class="fa fa-plus"> </i> Ver anteriores </button>
	@endif
</div>

	<div class="col-md-12">
	<div class="cont_act_prev" style="display:none">
	<div class="box-body table-responsive no-padding">
			<table id="tbl_ajax" class="table dataTable dataTable" role="grid">
	
	  <thead>
		
	  </thead>
	 <tbody id="datos_prev">
	
				 <tr>
				 <td>
				 No se encontraron registros...  
				 </td>
				 </tr>
	
	 </tbody>
	</table>
	</div>
	</div>

	</div>
</div>


	@include('myforms.frm_actuacion_edit_docen')   


	@include('myforms.frm_actuacion_edit') 	
	@include('myforms.frm_actuacion_add_revision')

@include('myforms.frm_actuacion_details_est')
@include('myforms.frm_actuacion_edit_doc') 

@include('myforms.frm_actuacion_det') 

