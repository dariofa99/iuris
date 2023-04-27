
@if(count($expediente->notas) >= 1 and ($expediente->expestado_id == 2 or $expediente->notas->first()->docidnumber == Auth::user()->idnumber))
	
  {!!Form::model($expediente, ['id'=>'myform_califica_create'])!!}	
  <div class="box-body table-responsive no-padding">
	<table id="tbl_cierre_caso" class="table table-bordered table-striped dataTable dataTable" role="grid">
	@foreach($expediente->notas as $nota)
	<tr>
		<td>
			Docente Evaluador
		</td>
		<td>
			{{ ( $nota->docente_eva->name) }}
		</td>
	</tr>
		@break
	@endforeach 	
	@foreach($expediente->notas as $nota) 
	<tr>
		<td>
			{{ ( $nota->segmento->segnombre) }}
		</td>
		<td>
			{{ ( $nota->tipo_nota->tpntnombre) }}
		</td>
	</tr>
		@break
	@endforeach 

	@foreach($expediente->notas as $nota)	
		<tr>
			<td width="35%">
				{{ $nota->concepto->cpntnombre }} 
			</td>
			<td width="20%">
				<input type="hidden" id="id_nota" value="{{ $nota->id }}">
				<input type="text" class="form-control" id="val_nota{{$nota->id}}" value="{{ $nota->nota }}" style="display: none">
				<label id="labelNota{{$nota->id}}">
					{{ $nota->nota }}
				</label>
				
			</td>
			<td>
				@if(count($expediente->notas)>0)
 @if(($expediente->notas->first()->docidnumber == Auth::user()->idnumber and $expediente->expestado_id !=2 ) or ((currentUser()->hasRole('amatai') or currentUser()->hasRole('diradmin')) and $expediente->expestado_id ==2))
             <a class="btn btn-warning" id="btnShowEditNota{{ $nota->id }}" onclick="showEditNota({{ $nota->id }})">Editar</a>
				<a class="btn btn-primary" id="btnActNota{{ $nota->id }}" onclick="updateNota({{$nota->id}})" style="display: none">Actualizar</a>
				<a class="btn btn-danger" id="btnCancelNota{{$nota->id}}" onclick="hideEditNota({{$nota->id}})" style="display: none" >Cancelar</a>
   @endif
  @endif
				
			</td> 
		</tr>
	@endforeach

	<tr><td>Final</td><td> <label id="labelNotaFinal">{{ $nota->getNotaFinal($expediente->notas) }}</label></td></tr>
	</table>
	</div>
{!!Form::close()!!}
@else
	<div class="box-body table-responsive no-padding">
	<table id="tbl_cierre_caso" class="table table-bordered table-striped dataTable dataTable" role="grid">
		

		<tr>
			<td>
				Sin asignar
			</td>
			<td>
				Sin asignar
			</td>
		</tr>


	<tr><td>Final</td><td>0.00</td></tr>
	</table>
	</div>
@endif












