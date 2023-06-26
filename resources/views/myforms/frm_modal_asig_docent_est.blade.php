@component('components.modal')
	
	@slot('trigger')
		myModal_asig_docente_estudiante
	@endslot

	@slot('title')
		Asignando  Docente:
	@endslot

 
	@slot('body')

{!!Form::open([ 'id'=>'myform_asig_doc_est'])!!}
	<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
	<input type="hidden"  id="idact2">


@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')
<div class="row">
	<div class="col-md-12">
		    <div class="form-group">
			<div class="box-body table-responsive no-padding">
		    <table class="table">
			<thead>
				
			</thead>
			<tbody>   
			<tr>
				<td>
					Estudiante:
				</td>
				<td>
					<label id="label_idnumber_estToAsig"></label>
					<input type="hidden" id="est_idnumber" name="est_idnumber">
				</td>
			</tr>
					<tr>
						<td>
							Seleccione el docente 
						</td>
						<td>
							<select name="docente_idnumber" id="docente_to_asig_id" class="form-control selectpicker" data-live-search='true'>
								<option value="">Seleccione...</option>
								@foreach($horarios_docente as $horario)
							<option value="{{$horario->docente->idnumber}}"> {{ $horario->docente->name }} {{ $horario->docente->lastname }} </option>
								@endforeach
							</select>
						</td>			
				 
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
			{!! link_to('#', 'Asignar', $attributes = array('id'=>'btn_asig_est_doc', 'type'=>'button', 'class'=>'btn btn-primary'), $secure=null)!!}
		</div>
	</div>
</div>


	

	

	{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










