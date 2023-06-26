@component('components.modal')
	
	@slot('trigger')
		myModal_req_edit
	@endslot

	@slot('title')
		Editar
	@endslot

 
	@slot('body')

<div class="form-group">
		{!!Form::hidden('reqid', '.' , ['id' => 'reqid', 'class' => 'form-control', 'readonly' ]); !!}
	</div>
{!!Form::open(['route'=>'requerimientos.store', 'method'=>'post', 'id'=>'myform_req_edit'])!!}


	<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">


	<div class="form-group">
		{!!Form::hidden('id_control_list_req',   $expediente->expid , ['id' => 'id_control_list_req', 'class' => 'form-control', 'readonly' ]); !!}
	</div>



	


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
	  		{!!Form::text('reqfecha1', fechaActual(), ['class' => 'form-control', 'required' => 'required','data-inputmask'=>"'alias': 'yyyy/mm/dd'" , 'data-mask', 'readonly','id'=>'reqfecha1' ] ); !!}
		</div>
	<!-- /.input group -->
	</div>



	<div class="col-md-12">
		<div class="form-group">
			{!!Form::label('Cédula: ') !!}
			{!!Form::text('reqidsolicitan', $expediente->solicitante->idnumber, ['class' => 'form-control', 'required' => 'required' , 'readonly','id'=>'cedula' ] ); !!}
		</div>
	</div>



	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Nombres: ') !!}
			{!!Form::text('name',   $expediente->solicitante->name  , ['class' => 'form-control', 'readonly','id'=>'name' ]); !!}
		</div>
	</div>




	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Apellidos: ') !!}
			{!!Form::text('lastname',  $expediente->solicitante->lastname , ['class' => 'form-control' , 'readonly','id'=>'lastname']); !!}
		</div>
	</div>



	<div class="col-md-6">
		{!!Form::label('Fecha citación: ') !!}
	  
	     <div class="input-group">
	      <div class="input-group-addon">
	        <i class="fa fa-calendar"></i>
	      </div>
	     {{--  {!!Form::text('reqfecha',null, ['class' => 'form-control', 'id' => 'reqfecha', 'required' => 'required','data-inputma'=>"'alias': 'yyyy/mm/dd'" , 'data-mas' ] ); !!} --}}
	      {!!Form::text('reqfecha',null, ['class' => 'form-control', 'id' => 'reqfecha_ed', 'required' => 'required','data-inputmask'=>"'alias': 'yyyy/mm/dd'" , 'data-mask'] ); !!}
	    </div>
	    <!-- /.input group -->
	</div>	


 
	<div class="col-md-6">
		<div class="bootstrap-timepicker"><div class="bootstrap-timepicker-widget dropdown-menu"><table><tbody><tr><td><a href="#" data-action="incrementHour"><i class="glyphicon glyphicon-chevron-up"></i></a></td><td class="separator">&nbsp;</td><td><a href="#" data-action="incrementMinute"><i class="glyphicon glyphicon-chevron-up"></i></a></td><td class="separator">&nbsp;</td><td class="meridian-column"><a href="#" data-action="toggleMeridian"><i class="glyphicon glyphicon-chevron-up"></i></a></td></tr><tr><td><span class="bootstrap-timepicker-hour">04</span></td> <td class="separator">:</td><td><span class="bootstrap-timepicker-minute">15</span></td> <td class="separator">&nbsp;</td><td><span class="bootstrap-timepicker-meridian">PM</span></td></tr><tr><td><a href="#" data-action="decrementHour"><i class="glyphicon glyphicon-chevron-down"></i></a></td><td class="separator"></td><td><a href="#" data-action="decrementMinute"><i class="glyphicon glyphicon-chevron-down"></i></a></td><td class="separator">&nbsp;</td><td><a href="#" data-action="toggleMeridian"><i class="glyphicon glyphicon-chevron-down"></i></a></td></tr></tbody></table></div>
	        <div class="form-group">
	          <label>Hora citación:</label>

	          <div class="input-group" >
	            <input type="text" class="form-control timepicker" id="reqhora_ed" name="reqhora">								                     
 
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
			{!!Form::text('reqmotivo',  null , ['class' => 'form-control required','id'=>'reqmotivo','maxlength'=>'95' ]); !!}
		</div>
	</div>




	<div class="col-md-12">
		<div class="form-group">
			{!!Form::label('Descripción: ') !!}
			{!!Form::textarea('reqdescrip',null,['class' => 'form-control required','id'=>'reqdescrip','maxlength'=>'700' ]); !!}
		</div>
	</div>





	<div class="col-md-12">
		<div class="form-group">
			<br>
			{!! link_to('#', 'Actualizar', $attributes = array('id'=>'btn_act_req', 'type'=>'button', 'class'=>'btn btn-warning','onclick'=>'actReq()'), $secure=null)!!}
		</div>
	</div>




{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










