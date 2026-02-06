 @component('components.b4.modal_large')
     @slot('trigger')
         myModalCreateConcHechosPretensiones
     @endslot

     @slot('title')
         <label id="lbl_title_modal">Agregando Información</label>
     @endslot



     @slot('body')
         <div class="container-fluid py-2">

             <form method="POST" id="myformCreateHechoPretension" class="form_store">

                 <input type="hidden" name="id">
                 <input type="hidden" name="tipo_id">

                 {{-- CONTENEDOR --}}
                 <div id="content_create_descrip_hepr" class="hepr-container"></div>

                 {{-- BOTONES --}}
                 <div class="d-flex justify-content-between mt-4">

                     <button type="button" id="btn_add_he_pret_input" class="btn btn-outline-primary btn-sm px-4">
                         <i class="fas fa-plus mr-1"></i>
                         Agregar
                     </button>

                     <button type="submit" class="btn btn-primary px-4 shadow-sm">
                         <i class="fas fa-save mr-1"></i>
                         Guardar
                     </button>

                 </div>

             </form>

         </div>
     @endSlot
 @endcomponent
 <!-- /modal -->
