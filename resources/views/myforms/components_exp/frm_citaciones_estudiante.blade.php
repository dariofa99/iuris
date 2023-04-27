@if($expediente->getDocenteAsig()->idnumber == currentUser()->idnumber)
<div class="row">
  <div class="col-md-12">
    <input type="button" id="btn_nueva_cita" value="Nueva cita" class="btn btn-primary">
  </div>
</div>
@endif

<div class="row">
  <div class="col-md-12">
    <table class="table" id="table_list_citaciones">
    <thead>
        <th width="20%">Docente</th>
        <th >Motivo</th>
        <th width="20%">Fecha</th>
        <th width="10%">Hora</th>
        <th> Acciones </th>
    </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>
@include('myforms.components_exp.frm_modal_citaciones_estudiante')
