 @foreach ($expediente->getActuaciones($only_estu) as $key => $actuacion)
     @php
         $haycorrecciones = true;
         $hayactuaciones = false;
         $ultima_id = '';
         if (count($actuacion->getHijos($actuacion)) > 0) {
             foreach ($actuacion->getHijos($actuacion) as $key => $hijo) {
                 if ($hijo->actestado_id == '102') {
                     $haycorrecciones = true;
                     $hayactuaciones = true;
                     $ultima_id = $hijo->id;
                 }
                 if ($hijo->actestado_id == '101' || $hijo->actestado_id == '104' || $hijo->actestado_id == '234') {
                     $haycorrecciones = false;
                     $hayactuaciones = true;
                 }
             }
         }
     @endphp
     <tr style="background-color: rgb(243, 242, 242) !important">
         <td>
             {{ $actuacion->actnombre }}
         </td>
         <td>
             {{ $actuacion->actdescrip }}
         </td>
         <td>
             <span class="badge badge-success" style="background-color: {{ $actuacion->estado->color }} !important">
                 {{ $actuacion->estado->ref_nombre }} {{ $actuacion->actestado_id }}
             </span>

         </td>
         <td>
             @if ($actuacion->fecha_limit != '' and !$hayactuaciones)
                 {{ getDiffDays(date('Y-m-d'), $actuacion->fecha_limit) }} Días
             @else
                 {{ getSmallDate($actuacion->actfecha) }}
             @endif

         </td>
         <td>
             <a target="_blank"
                 href="{{ url('/actpdfdownload/' . $actuacion->id . '/estudiante') }}">{{ $actuacion->actdocnompropio }}</a>
         </td>
         <td width="14%">
             @if ($actuacion->actestado_id != 136 and $actuacion->actestado_id != 138)
                 <button type='button' value="{{ $actuacion->id }}" data-modal="#myModal_act_add_revision"
                     class='btn btn-block btn-default btn-sm buscar_actuacion' style='color:#777'
                     title='Agregar anexo a actuación' data-status='136'>
                     Ag. Anexo
                 </button>
             @endif
             @if (
                 $actuacion->actestado_id == 136 and
                     $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                 <button type="button" value="{{ $actuacion->id }}"
                     class="btn btn-default btn-block btn-sm btn_change_status" style="color:#777">
                     Revisado
                 </button>

                 <button type='button' data-estado='235' value="{{ $actuacion->id }}"
                     class='btn btn-danger btn-block btn-sm btn_change_status'>
                     Anular</button>
             @endif

             @if (
                 $actuacion->actestado_id == 235 and
                     $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                 <button type='button' data-estado='136' value="{{ $actuacion->id }}"
                     class='btn btn-danger btn-block btn-sm btn_change_status'>
                     Des-anular</button>
             @endif
             @if (
                 $actuacion->actestado_id == 138 and
                     $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                 <button type='button' value="{{ $actuacion->id }}"
                     class='btn btn-default btn-block btn-sm btn_change_status' style='color:#777'>
                     Quitar revisado
                 </button>
             @endif
             @if (
                 $actuacion->actestado_id == 101 and
                     $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                 <button data-modal="#myModal_act_edit_docen" type='button' value="{{ $actuacion->id }}"
                     class='btn btn-primary btn-block btn-sm buscar_actuacion'>
                     Revisar
                 </button>
             @endif

             @if (
                 $actuacion->actestado_id == 102 and
                     count($actuacion->getHijos($actuacion)) <= 0 and
                     $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                 <button data-modal="#myModal_act_edit_docen" type='button' value="{{ $actuacion->id }}"
                     class='btn btn-warning btn-sm btn-block buscar_actuacion'>
                     Editar Revisón
                 </button>
             @endif

             @if (
                 $actuacion->actestado_id == 101 || $actuacion->actestado_id == 136 and
                     $actuacion->actusercreated == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                 <button data-modal="#myModal_act_edit" type='button' value="{{ $actuacion->id }}"
                     class='btn btn-primary btn-sm buscar_actuacion btn-block'> Editar
                 </button>
             @endif

             @if (
                 $actuacion->actestado_id == 102 and
                     count($actuacion->getHijos($actuacion)) <= 0 || $haycorrecciones and
                     $expediente->expidnumberest == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                 <button data-modal="#myModal_act_add_revision" type='button' value="{{ $actuacion->id }}"
                     class='btn btn-warning btn-sm btn-block buscar_actuacion' data-titulo_modal='Nueva actuación'>
                     Ag. Corrección </button>
             @endif
             @if (
                 $actuacion->actestado_id == 101 || $actuacion->actestado_id == 136 and
                     $actuacion->actusercreated == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                 <button type='button' value="{{ $actuacion->id }}"
                     class='btn btn-danger btn-block btn-sm delete_act'>
                     Eliminar
                 </button>
             @endif
             <button data-modal="#myModal_act_details" type='button' value="{{ $actuacion->id }}"
                 class='btn btn-success btn-sm buscar_actuacion btn-block'> Detalles
             </button>
         </td>
     </tr>
     @if (count($actuacion->getHijos($actuacion)) > 0)
         @foreach ($actuacion->getHijos($actuacion) as $key => $hijo)
             <tr style="background-color: rgb(232, 237, 237) !important">
                 <td>
                     <i class="fa fa-reply" style="transform:rotate(180deg)"> </i>
                     {{ $hijo->actnombre }}
                 </td>
                 <td>
                     {{ $hijo->actdescrip }}
                 </td>
                 <td>
                     <span class="badge badge-success" style="background-color: {{ $hijo->estado->color }} !important">
                         {{ $hijo->estado->ref_nombre }} {{ $hijo->actestado_id }}
                     </span>
                 </td>
                 <td>
                    @if ($hijo->fecha_limit != '' and  $hijo->actestado_id == 102 and $ultima_id == $hijo->id )
                    {{ getDiffDays(date('Y-m-d'), $hijo->fecha_limit) }} Días
                @else
                    {{ getSmallDate($hijo->actfecha) }} 
                @endif
                 </td>
                 <td>
                     <a target="_blank"
                         href="{{ url('/actpdfdownload/' . $hijo->id . '/estudiante') }}">{{ $hijo->actdocnompropio }}</a>
                 </td>
                 <td>

                     @if (
                         $hijo->actestado_id == 136 and
                             $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                         <button type="button" value="{{ $hijo->id }}"
                             class="btn btn-default btn-block btn-sm btn_change_status" style="color:#777">
                             Revisado
                         </button>

                         <button type='button' data-estado='235' value="{{ $hijo->id }}"
                             class='btn btn-danger btn-block btn-sm btn_change_status'>
                             Anular</button>
                     @endif

                     @if (
                         $hijo->actestado_id == 235 and
                             $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                         <button type='button' data-estado='136' value="{{ $hijo->id }}"
                             class='btn btn-danger btn-block btn-sm btn_change_status'>
                             Des-anular</button>
                     @endif
                     @if (
                         $hijo->actestado_id == 138 and
                             $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                         <button type='button' value="{{ $hijo->id }}"
                             class='btn btn-default btn-block btn-sm btn_change_status' style='color:#777'>
                             Quitar revisado
                         </button>
                     @endif

                     @if (
                         $hijo->actestado_id == 101 and
                             $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                         <button data-modal="#myModal_act_edit_docen" type='button' value="{{ $hijo->id }}"
                             class='btn btn-primary btn-block btn-sm buscar_actuacion'>
                             Revisar
                         </button>
                     @endif
                     @if (
                         $hijo->actestado_id == 102 and $ultima_id == $hijo->id and
                             $haycorrecciones and
                             $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                         <button data-modal="#myModal_act_edit_docen" type='button' value="{{ $hijo->id }}"
                             class='btn btn-warning btn-sm btn-block buscar_actuacion'>
                             Editar Revisón
                         </button>
                     @endif


                     @if (
                         $hijo->actestado_id == 101 || $hijo->actestado_id == 136 and
                             $hijo->actusercreated == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                         <button data-modal="#myModal_act_edit" type='button' value="{{ $hijo->id }}"
                             class='btn btn-primary btn-sm buscar_actuacion btn-block'>
                             Editar
                         </button>
                     @endif
                     @if (
                         $hijo->actestado_id == 101 || $hijo->actestado_id == 136 and
                             $hijo->actusercreated == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                         <button type='button' value="{{ $hijo->id }}"
                             class='btn btn-danger btn-block btn-sm delete_act'>
                             Eliminar
                         </button>
                     @endif
                     <button data-modal="#myModal_act_details" type='button' value="{{ $hijo->id }}"
                         class='btn btn-success btn-sm buscar_actuacion btn-block'>
                         Detalles
                     </button>
                 </td>
             </tr>
         @endforeach
     @endif
 @endforeach
