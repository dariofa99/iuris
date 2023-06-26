<!-- modal para turnos estudiantes -->
<!-- Trigger the modal with a button -->
<div  id="mymodal" class="modal fade" role="dialog" >
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
	<div class="row"  id="tituloturnos"  >
	</div>
	</h4>
      </div>
       {!!Form::open([ 'id'=>'myFormCalendar','url' => '/horarios','method'=>'post'])!!} 

      <div class="modal-body">


     <div>

      <div class="row">
        <div class="col-md-12">
           <table id="tbl_turnos_list" class="table table-bordered table-striped dataTable" role="grid">

                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Nombre</th>
                      <th>Curso</th>
@if (currentUser()->hasRole('coordprac') OR currentUser()->hasRole('diradmin') OR currentUser()->hasRole('dirgral') OR currentUser()->hasRole('amatai'))
                      <th>Asistencia</th> 
                      <th>Lugar</th>
                      <th>Motivo</th>
@endif
                    </tr>
                  </thead>
                <tbody id="contencalendarid">
                </tbody>
                </table>
        </div>
      </div>

      </div>
<input type="hidden" id="fechaestasis" name="fechaestasis" value="">

      <div class="modal-footer">
@if (currentUser()->hasRole('coordprac') OR currentUser()->hasRole('diradmin') OR currentUser()->hasRole('dirgral') OR currentUser()->hasRole('amatai'))
       <button type="button" class="btn btn-success"  id="addest">Añadir estudiante</button>
       <button type="submit" class="btn btn-primary">Guardar cambios</button>
@endif
       {{--  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> --}}
      </div>
    </div>
{!! Form::close() !!}
  </div>
</div>

</div>

<!-- modal para turnos docentes -->
<!-- Trigger the modal with a button -->
<div  id="mymodaldoc" class="modal fade" role="dialog" >
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
	      <div class="row"  id="tituloturnosdoc">
        
	      </div>
	      </h4>
      </div>
       
      <div class="modal-body" id="turnosdoc">


        <div class="modal-footer" id="fotermodaldoc">
          <button type="submit" class="btn btn-primary">Guardar cambios</button>

       
        </div>
      </div>

    </div>
  </div>

</div>

