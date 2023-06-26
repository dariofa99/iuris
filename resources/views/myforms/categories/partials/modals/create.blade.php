@component('components.modal')
	
	@slot('trigger')
		myModal_create_category
	@endslot

	@slot('title')
		<label id="lbl_modal_title">      Creando categoria</label> 
	@endslot

 
	@slot('body')

    <form method="POST" class="form_store" accept-charset="UTF-8" id="myformCreateCategory" enctype="multipart/form-data">
        {{csrf_field()}}
<input type="hidden" name="id" id="log_id"> 
<input type="hidden" name="items_deleted" id="items_deleted"> 

<div class="form-group">
    <label for="description">Nombre de la categoria</label>
   <input type="text" class="form-control " required name="name" id="name">
</div>

<div class="form-group">
  <label for="description">Nombre corto</label>
 <input type="text" class="form-control " required name="short_name" id="short_name">
</div>

<div class="form-group">
    <label for="description">Utilizar en</label>
   <select required name="table" id="table" class="form-control">      
       <option value="users">Usuarios</option> 
       <option value="conciliaciones">Conciliaciones</option> 
       <option value="pdf_reportes">Reportes</option>     
   </select>
</div>

<div class="form-group">
  <label for="description">Parte</label>
 <select style="width: 100%" multiple required name="parte[]" id="parte" class="form-control select2">
     
     <option value="solicitante">Solicitante</option>
     <option value="rep_legal_solicitante">Representante legal - solicitante</option>
     <option value="apoderado_solicitante">Apoderado de la parte solicitante</option>
     <option value="solicitada" >Solicitada</option>
     <option value="rep_legal_solicitada">Representante legal - solicitada</option>

     <option value="solicitante">No aplica</option>

</select>
</div>

<div class="form-group">
    <label for="description">Sección</label>
   <select  required name="section" id="section" class="form-control">
       <option value="" >Seleccione...</option>
       <option value="datos_personales">Datos personales</option>
       <option value="enfoque_diferencial">Enfoque diferencial</option>
       <option value="discapacidad" >Discapacidad</option>
       <option value="socio_economica" >Inf. Socio-económica</option>
       <option value="grupo_etnico" >Grupo etnico</option>
       <option value="sin_seccion" >Sin sección</option>
     
   </select>
</div>

<div class="form-group">
    <label for="description">Tipo</label>
   <select name="type_data_id" id="type_data_id" class="form-control" required>
       <option value="" >Seleccione...</option>
      @forelse($type_data as $key => $data)
   <option value="{{$key}}">{{$data}}</option>
      @empty
      <option disabled>No se encontraron datos</option>
      @endforelse
   </select>
</div>

<div class="adoptions adoptions_g" id="content_aditional_options" style="display: none">
    <table id="aditional_options_table" class="table">
      <thead>
        <tr>
          <th></th>
          <th><small>Activa otro</small></th>
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
    <table>
      <tbody>
         <tr>
          <td>
            <label class="btn_add_field">Agregar campo</label>
          </td>                
        </tr>
      </tbody>
    </table>
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


	@endslot
@endcomponent
<!-- /modal -->










