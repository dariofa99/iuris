<div class="row" >
    
   </div>
   
   <div class="row">
       <div class="col-md-12 table-responsive no-padding">
           <table class="table" id="table_list_user_asig">
               <thead>
                    
                   <th>
                       Nombres
                   </th>
                   <th>
                       Correo
                   </th>
                   <th>
                      Tipo
                   </th>
                   <th>
                    Estado
                 </th>
                   <th>
                       Fecha asignación
                   </th>
                 {{--   <th>
                       Acciones
                   </th> --}}
               </thead>
               <tbody>
                  @include('myforms.conciliaciones.componentes.solicitud_user_asig_notas_ajax')
               </tbody>
           </table>
       </div>
   </div>