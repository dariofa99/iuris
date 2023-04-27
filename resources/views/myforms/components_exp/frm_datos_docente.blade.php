<div class="pull-right" style="font-size:14px">

<input type="hidden" value="{{$expediente->getDocenteAsig()->idnumber}}" name="doc_id_number" id="doc_id_number">
<input type="hidden" value="{{$expediente->getDocenteAsig()->name}} {{$expediente->getDocenteAsig()->lastname}}" name="doc_full_name" id="doc_full_name">

Docente:<i style="font-size:15px">
 {{$expediente->getDocenteAsig()->name}} {{$expediente->getDocenteAsig()->lastname}}
 </i> 
 
 <br>
 @if((currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai')) and $expediente->getDocenteAsig()->name!='Sin asignar')
 <a href="#" class="btn_change_doc_exp" data-lastname="{{Auth::user()->lastname}}" data-name="{{Auth::user()->name}}" data-idnumber="{{Auth::user()->idnumber}}" id="btn_change_doc_exp">
 Cambiar</a>

 <a href="#" class="btn_change_doc_exp" data-lastname="{{Auth::user()->lastname}}" data-name="{{Auth::user()->name}}" data-idnumber="{{Auth::user()->idnumber}}" id="btn_delete_doc_exp">
 Eliminar</a>

 @endif

 @if($expediente->getAsignacion()->asig_docente!==null and (currentUser()->idnumber == $expediente->getAsignacion()->asig_docente->cambio_docidnumber))
<a href="#"  class="btn_change_doc_exp" id="btn_accept_change_doc_exp">Aceptar solicitud</a>
@endif

@if($expediente->getDocenteAsig()->idnumber == currentUser()->idnumber || (currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai')))

 @if($expediente->getAsignacion()->asig_docente!==null and $expediente->getAsignacion()->asig_docente->cambio_docidnumber!==null)
 <a href="#"  class="btn_change_doc_exp" id="btn_cancel_change_doc_exp">Cancelar solicitud</a>
 
@elseif($expediente->getDocenteAsig()->idnumber!='Sin asignar' and $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber || (currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai')))
<a href="#"  class="btn_change_doc_exp" id="btn_send_exp_change">Solicitar Cambio</a>
@endif 

 @endif

@if($expediente->getDocenteAsig()->name=='Sin asignar' and (currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai')))
<a href="#"  class="btn_change_doc_exp" id="btn_asig_exp_doc" data-lastname="{{Auth::user()->lastname}}" data-name="{{Auth::user()->name}}" data-idnumber="{{Auth::user()->idnumber}}">
Asignar
</a>
@endif

@if(!$expediente->getDocenteAsig()->hasRole('docente_prueba') and (currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai')))
<a href="#"  class="btn_change_doc_exp" id="btn_dar_baja_exp" data-lastname="{{Auth::user()->lastname}}" data-name="{{Auth::user()->name}}" data-idnumber="{{Auth::user()->idnumber}}">
Dar de baja
</a>
@endif

</div>

<!--currentUser()->casos()->where([['cambio_docidnumber','<>',null],['asig_caso_id','=',$expediente->getAsignacion()->id]])->get()-->