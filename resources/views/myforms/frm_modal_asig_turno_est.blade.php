@component('components.modal')
	
	@slot('trigger')
		myModal_asig_turno_estudiante
	@endslot

	@slot('title')
		Asignando  Turno:
	@endslot

 
	@slot('body')

{!!Form::open([ 'id'=>'myform_asig_turno_est'])!!}
	

@section('msg-contenido') 
Registrado
@endsection
@include('msg.ajax.success')
<div class="row">
	<div class="row">
		<div class="col-md-12">
			<table class="table">
			<tr>
				<td>
					Estudiante:
				</td>
				<td>
					<label id="label_idnumber_estToAsig"></label>
					<input type="hidden" id="est_idnumber" name="est_idnumber">
				</td>
			</tr>
		</table>
		</div>		
	</div>
	<div class="col-md-12">
		    <div class="form-group">
			<div class="box-body table-responsive no-padding">
		    <table class="table">
			<thead>
				<th>
					Color
				</th>
				<th>
					Curso
				</th>
				<th>
					Horario
				</th>
				<th>
					Oficina
				</th>
				<th>
					Día
				</th>
			</thead>
			<tbody>
			
					<tr> 

					<td>
						{!!Form::select('color_id',$ref_color,null,['class' => 'form-control  input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>"color_id",'style'=>'display:block'] ); !!}
					</td>
					<td>
						{!!Form::select('cursando_id',$cursando,null,['class' => 'form-control input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>"cursando_id",'style'=>'display:block'] ); !!}

						
					</td>
					<td>
						{!!Form::select('horario_id',$ref_horarios,null,['class' => 'form-control input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>"horario_id",'style'=>'display:block'] ); !!}
						
					</td>
					<td>
					{!!Form::select('trnid_oficina',$oficinas,null,['class' => 'form-control input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>"trnid_oficina",'style'=>'display:block'] ); !!}
							
					</td>
					<td>
					{!!Form::select('trnid_dia',$dias,null,['class' => 'form-control input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>"trnid_dia",'style'=>'display:block'] ); !!}
						
					</td>
					</tr>			
				
			</tbody>
		</table>
		</div>
		<div class="box-body table-responsive no-padding">
		<table class="table" id="table_show_docente_toAsig">
			<tbody>
				
			</tbody>			
		</table>
		</div>
		    </div>
	    </div>
</div>
<div class="row">
	<div class="col-md-3 col-md-offset-9">
		<div class="form-group">
			<br>
			{!! link_to('#', 'Asignar', $attributes = array('id'=>'btn_asig_turno_est', 'type'=>'button', 'class'=>'btn btn-primary'), $secure=null)!!}
		</div>
	</div>
</div>


	

	

	{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










