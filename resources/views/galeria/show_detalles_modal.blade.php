@component('components.modal')
  
  @slot('trigger')
    myModal_show_details
  @endslot

  @slot('title')
    Detalles Biblioteca
  @endslot

 
  @slot('body')

<div class="row">
    <div class="col-md-10 col-md-offset-1">
    <div id="contenedo">
    <div class="box-body table-responsive no-padding">
     <table class="table">
       <tr>
         <td>Nombre</td><td><label id="label_biblinombre"></label></td>      
       </tr>
       <tr>
         <td>Subido por</td><td><label for="" id="label_user_create"></label></td>   
         </tr>
         <tr>
         <td>Nombre del Archivo</td><td><label for="" id="label_biblidocnompropio"></label></td>   
         </tr>
         <tr>
         <td>Tamaño del Archivo</td><td><label for="" id="label_biblidoctamano"></label></td>   
         </tr>
         <tr>
         <td>Actualizado la ultima ver por</td><td><label for="" id="label_bibliuserupdated"></label></td>   
         </tr>

       <tr>
          <td>Rama del Derecho</td><td><label for="" id="label_bibliid_ramaderecho"></label></td>
        </tr>
         <tr>
           <td>Categoria</td><td><label for="" id="label_bibliid_tipoarchivo"></label></td>
        </tr>
         <tr>
         <td>Descripción</td><td><label for="" id="label_biblidescrip"></label></td>   
         </tr>

     </table>
      </div>

        </div>
  </div>
  </div>
  
<div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
      </div>


  @endslot
@endcomponent
<!-- /modal -->










