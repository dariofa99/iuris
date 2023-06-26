 <!-- modal --> 
 	           
 @if($expediente->expestado_id <>'2' AND  $expediente->expestado_id <>'4' )

 	<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#mymodal" id="btn_modal_req" >Nueva cita/req.</button>
@endif

<div id="mymodal" class="modal fade" role="dialog" >
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Agregar</h4>
      </div>
      <div class="modal-body">                        

		
     <div>
			
			<div class="row">
				<div class="col-md-12">









{!!Form::open(['route'=>'requerimientos.store', 'method'=>'post', 'id'=>'myform_req'])!!}


	<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">


	<div class="form-group">
		{!!Form::hidden('id_control_list_req',   $expediente->expid , ['id' => 'id_control_list_req', 'class' => 'form-control', 'readonly' ]); !!}
	</div> 



	{{-- <div class="form-group">
		{!!Form::hidden('reqid',   '.' , ['id' => 'id_control_list', 'class' => 'form-control', 'readonly' ]); !!}
	</div> --}}


	<div class="form-group">
		{!!Form::hidden('reqidest',  $expediente->estudiante->idnumber , ['id' => 'id_control_list', 'class' => 'form-control', 'readonly' ]); !!}
	</div>



	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Código expediente') !!}
			{!!Form::text('reqexpid',   $expediente->expid , ['id' => 'actexpid', 'class' => 'form-control', 'readonly' ]); !!}
		</div>
	</div>



	<div class="col-sm-6">
		{!!Form::label('Fecha: ') !!}

		<div class="input-group">
	  		<div class="input-group-addon">
	    		<i class="fa fa-calendar"></i>
	  		</div>
	  		{!!Form::text('reqfecha', fechaActual(), ['class' => 'form-control', 'required' => 'required','data-inputmask'=>"'alias': 'yyyy/mm/dd'" , 'data-mask', 'readonly' ] ); !!}
		</div>
	<!-- /.input group -->
	</div>



	<div class="col-md-12">
		<div class="form-group">
			{!!Form::label('Cédula: ') !!}
			{!!Form::text('reqidsolicitan', $expediente->solicitante->idnumber, ['class' => 'form-control', 'required' => 'required' , 'readonly' ] ); !!}
		</div>
	</div>



	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Nombres: ') !!}
			{!!Form::text('name',   $expediente->solicitante->name  , ['class' => 'form-control', 'readonly' ]); !!}
		</div>
	</div>




	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Apellidos: ') !!}
			{!!Form::text('lastname',  $expediente->solicitante->lastname , ['class' => 'form-control' , 'readonly']); !!}
		</div>
	</div>



	<div class="col-md-6">
		{!!Form::label('Fecha citación: ') !!}
	  
	     <div class="input-group">
	      <div class="input-group-addon">
	        <i class="fa fa-calendar"></i>
	      </div>
	      {!!Form::text('reqfecha', fechaActual(), ['class' => 'form-control required', 'id' => 'reqfecha', 'required' => 'required','data-inputmask'=>"'alias': 'yyyy/mm/dd'" , 'data-mask' ] ); !!}
	    </div>
	    <!-- /.input group -->
	</div>	



	<div class="col-md-6">
		<div class="bootstrap-timepicker"><div class="bootstrap-timepicker-widget dropdown-menu"><table><tbody><tr><td><a href="#" data-action="incrementHour"><i class="glyphicon glyphicon-chevron-up"></i></a></td><td class="separator">&nbsp;</td><td><a href="#" data-action="incrementMinute"><i class="glyphicon glyphicon-chevron-up"></i></a></td><td class="separator">&nbsp;</td><td class="meridian-column"><a href="#" data-action="toggleMeridian"><i class="glyphicon glyphicon-chevron-up"></i></a></td></tr><tr><td><span class="bootstrap-timepicker-hour">04</span></td> <td class="separator">:</td><td><span class="bootstrap-timepicker-minute">15</span></td> <td class="separator">&nbsp;</td><td><span class="bootstrap-timepicker-meridian">PM</span></td></tr><tr><td><a href="#" data-action="decrementHour"><i class="glyphicon glyphicon-chevron-down"></i></a></td><td class="separator"></td><td><a href="#" data-action="decrementMinute"><i class="glyphicon glyphicon-chevron-down"></i></a></td><td class="separator">&nbsp;</td><td><a href="#" data-action="toggleMeridian"><i class="glyphicon glyphicon-chevron-down"></i></a></td></tr></tbody></table></div>
	        <div class="form-group">
	          <label>Hora citación:</label>

	          <div class="input-group" >
	            <input type="text" class="form-control timepicker" id="reqhora" name="reqhora">								                     

	            <div class="input-group-addon">
	              <i class="fa fa-clock-o"></i>
	            </div>
	          </div>
	          <!-- /.input group -->
	        </div>
	        <!-- /.form group -->
	      </div>
	</div>


	

	<!--  <input type="time" value="" id="reqfechahoracomp"> -->



	<div class="col-md-12">
		<div class="form-group">
			{!!Form::label('Motivo') !!}
			{!!Form::text('reqmotivo',  null , ['class' => 'form-control required','maxlength'=>'95' ]); !!}
		</div>
	</div>




	<div class="col-md-12">
		<div class="form-group">
			{!!Form::label('Descripción: ') !!}
			{!!Form::textarea('reqdescrip',  
			"Por medio de la presente me permito requerirlo para seguir	con el asunto radicado bajo el código: {$expediente->expid} que Usted presentó ante Consultorios Jurídicos de la Universidad Nariño. Por lo anterior, le solicito asistir a las instalaciones de Consultorios Jurídicos ubicados en la Calle 19 con Carrera 22 esquina, en la fecha indicada. En caso de no presentarse a esta citación se procederá al cierre y archivo del caso, advirtiéndole que Usted podrá acercarse nuevamente a presentar su asunto y será designado a un nuevo estudiante.	"

			, ['class' => 'form-control required','maxlength'=>'700' ]); !!}
		</div>
	</div>



	<div class="col-md-12">
		<div class="form-group">
			<br>
			{!! link_to('#', 'Nuevo', $attributes = array('id'=>'btn_enviar_req', 'type'=>'button', 'class'=>'btn btn-primary'), $secure=null)!!}
		</div>
	</div>




{!!Form::close()!!}














        </div>
			</div>

      </div>


      <div class="modal-footer">
       {{--  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> --}}
      </div>
    </div>

  </div>
</div>

</div>










<!-- /modal -->
@include('myforms.frm_requerimiento_edit')
@include('myforms.frm_requerimiento_asist') 
@include('myforms.frm_requerimiento_details')  
 








<div class="col-md-12">
	<br>
</div>

<table id="tbl_ajax_req" class="table table-bordered table-striped dataTable" role="grid">
  <thead>
    <tr>
      <th>Fecha creación</th>
      <th>Motivo</th>
      <th>Fecha Cita</th>
      <th>Hora Cita</th>
      <th>Asistencia</th>
			<th>Estado</th>
			<th>Evaluado</th> 
      <th>Acción</th>	                      
    </tr>
  </thead>
 <tbody id="datos_req">
 </tbody>
</table>
