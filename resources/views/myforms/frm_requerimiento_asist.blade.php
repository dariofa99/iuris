@component('components.modal_dynamic')


  @slot('size')
    modal-dialog modal-lg
  @endslot
  
  @slot('trigger')
    myModal_req_asist
  @endslot 

  @slot('title')
    Control Requerimientos
  @endslot

 
  @slot('body')
  {!!Form::open(['method'=>'post', 'id'=>'myformUpdateReq' , 'files' => true])!!}
  <input type="text" id="req_id" hidden>
<div class="box-body table-responsive no-padding">
<table class="table">
     
    <tr>
      <td width="15%">
        Código Expediente
      </td>
      <td>
        <label id="lab_cod_exp"></label>
      </td>
      <td width="15%">
        Fecha Creación
      </td>
      <td>
        <label id="lab_fech_crea" ></label>
      </td>
    </tr>
    <tr>
      <td>
        Cédula
      </td>
      <td>
        <label id="lab_ced_solic"></label>
      </td>
    </tr>
    <tr>
      <td>
        Nombres
      </td>
      <td>
        <label id="lab_nom_solic"></label>
      </td>
      <td>
        Apellidos
      </td>
      <td>
        <label id="lab_apell_solic"></label>
      </td>
    </tr>
    <tr>
      <td>
        Fecha Citación
      </td>
      <td>
        <label id="lab_fech_cita"></label>
      </td>
      <td>
        Hora Citación
      </td>
      <td>
        <label id="lab_hora_cita"></label>
      </td>
    </tr>

    @if(currentUser()->hasRole("coordprac") || currentUser()->hasRole('diradmin'))
    <tr>



      <td>
        Asistencia
      </td>
      <td colspan="3">

        <select name="reqid_asistencia" id="reqid_asistencia" class="form-control required"> 
          <option value="1">Asistieron Ambos</option>
          @foreach($reqasis as $asis)          
            <option value="{{ $asis->reqid_refasis }}">{{ $asis->ref_reqasistencia }}</option>
          @endforeach  
        </select>
      </td>
    </tr>
    <tr>
      <td>
        Comentario
      </td>
      <td colspan="3">
        <textarea name="reqcomentario_coorprac" id="reqcomentario_coorprac" rows="5" class="form-control required"></textarea>
      </td>
    </tr>
  @endif
 
  @if(currentUser()->hasRole("docente") || currentUser()->hasRole("amatai") || currentUser()->hasRole("diradmin") )
  {!!Form::hidden('orgntsid',3, ['class' => 'form-control required','id'=>'orgntsid' ]); !!}
  {!!Form::hidden('tpntid',1, ['class' => 'form-control required','id'=>'tpntid' ]); !!}
  @if(isset($segmento)  and $segmento and isset($periodo) and $periodo)
  {!!Form::hidden('segid',$segmento->id, ['class' => 'form-control required','id'=>'segid' ]); !!}
  {!!Form::hidden('perid',$periodo->id, ['class' => 'form-control required','id'=>'perid' ]); !!}
@endif
    <tr>
      <td>
        Nota Ética
      </td>
      <td colspan="3">
        <input type="text" name="ntaetica" id="val_input" class="form-control required"   data-inputmask="'mask': ['9.9']" data-mask>
      </td>
    </tr>
    <tr>
      <td> 
        Concepto Notas
      </td>
      <td colspan="3">
        <textarea name="ntaconcepto" id="com_docente" rows="5" class="form-control required"></textarea>
      </td>
    </tr>
  @endif

  @if(currentUser()->hasRole("estudiante"))
    {{-- <tr>
      <td>
        Nota
      </td>
      <td colspan="3">
        <input type="text" name="nota" id="val_input">
      </td>
    </tr> --}}
    <tr>
      <td>
       Comentario
      </td>
      <td colspan="3">
        <textarea name="reqcomentario_est" id="reqcomentario_est" rows="5" class="form-control required"></textarea>
      </td>
    </tr>
  @endif


    <tr>
      <td colspan="4">
        <a class="btn btn-primary pull-right" onclick="updateRequerimiento()"> Guardar </a>
      </td>
    </tr>










</table>
</div>
{!! Form::close() !!}

  @endslot
@endcomponent
<!-- /modal -->










