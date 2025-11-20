
 
 <table id="tblListaEncuestas" class="table table-striped table-valign-middle">
     <thead>
         <tr>
             <th>Nombre</th>
             <th>Versión</th>
             <th>Código</th>
             <th>Fecha vigencia</th>
             <th>Activo</th>
             <th>Acciones</th>
         </tr>
     </thead>
     <tbody>
         @foreach ($admin_encuestas as $key => $encuesta)
             <tr class="btnRowSelEnc" data-name="{{ $encuesta->nombre }}" data-id="{{ $encuesta->id }}">
                 <td>
                     {{ $encuesta->nombre }}
                 </td>
                 <td> {{ $encuesta->version }}</td>
                 <td>
                     {{ $encuesta->codigo }}
                 </td>
                 <td>
                     {{ $encuesta->fecha_vigencia }}
                 </td>
                 <td>
                   <input {{!$encuesta->activo ?: "checked"}} type="radio" name="is_esc_active" class="radioChangeActiveEncuesta">
                </td>
                <td>
                    <i  class="fa fa-list-alt btnIconSelEnc" style="font-size: 13px;cursor:pointer">
                        Preguntas
                    </i>
                   
                    <i  class="fa fa-edit" style="font-size: 13px;cursor:pointer">
                        Editar
                    </i>
            
                </td>
             </tr>
         @endforeach
     </tbody>
 </table>
