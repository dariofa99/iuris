 @if (!$readonly and 
 (currentUser()->hasRole('estudiante')
 // or currentUser()->hasRole('diradmin')
  or currentUser()->hasRole('dirgral')
  or currentUser()->hasRole('amatai')))
  
     <div class="row">
         <div class="col-md-3">
             <button class="btn btn-primary btn-block btn-sm" id="btn_nueva_autorizacion">
                 Nueva autorización
             </button>
         </div>
     </div>
 @endif
 <div class="row">
     <div class="col-md-12">
         <table class="table" id="table_list_autorizaciones">
             <thead>
                 <th>
                     Nombre del estudiante
                 </th>
                 <th>
                     Calidad
                 </th>
                 <th>
                     Tipo de proceso
                 </th>
                 <th>
                     Estado
                 </th>
             </thead>
             <tbody>
                 @include('myforms.components_exp.frm_autorizaciones_ajax')
             </tbody>
         </table>
     </div>
 </div>
