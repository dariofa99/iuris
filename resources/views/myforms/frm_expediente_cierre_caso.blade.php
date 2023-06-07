@component('components.modal_dynamic')


	@slot('size')
		modal-dialog modal-dialog
	@endslot
	
	@slot('trigger')
		myModal_exp_edit_cierre_caso
	@endslot

	@slot('title')
		Actualizando Estado
	@endslot

 
	@slot('body')
	
            {!!Form::model($expediente, ['route'=>['expedientes.update', $expediente->id], 'method'=>'PUT', 'id'=>'myform_exp_edit_cierre_caso'])!!}


	<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
	
					<div class="col-md-3">
						<div class="form-group">
							
							{!!Form::hidden('expid',  null , ['class' => 'form-control'  , 'readonly' , 'id'=>'expid' ]); !!}
						</div>
					</div>

@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.danger')   





@if(currentUser()->hasRole("estudiante"))


					<div class="col-md-12">
						<div class="form-group">
							{!!Form::label('Estado del caso') !!}
							
							<select name="new_expestado" placeholder="Seleccione..." id="new_expestado" class="form-control required">
									<option value="">Seleccione...</option>
									@foreach($estados as $estado)
									@if($estado->categoria=='estudiante')
											<option value="{{ $estado->id }}">Solicitar cierre de caso</option>
									@endif		
									@endforeach

							</select>

							{{-- {!!Form::select('new_expestado',$estados,null, ['placeholder' => 'Selecciona...', 'class' => 'form-control required', 'required' => 'required', 'id'=>'new_expestado' ]); !!} --}}
						</div>
					</div>
@endif

@if(currentUser()->hasRole("docente") or currentUser()->hasRole("amatai") or currentUser()->hasRole("diradmin") || currentUser()->hasRole('dirgral'))


					<div class="col-md-12">
						<div class="form-group">
							{!!Form::label('Estado del caso') !!}
							
						<select name="new_expestado" placeholder="Seleccione..." id="new_expestado" class="form-control required">
									<option value="">Seleccione...</option>
									@foreach($estados as $estado)
									@if(currentUser()->hasRole("diradmin") || currentUser()->hasRole("amatai"))
										<option value="{{ $estado->id }}">{{ $estado->nombre_estado }}</option>
									@else
										@if($estado->categoria=='docente')
											<option value="{{ $estado->id }}">{{ $estado->nombre_estado }}</option>
										@endif
									@endif	
									@endforeach
						</select>
<small id="lbl_msj_nf" style="display:none;border-bottom: 1px solid #F1948A;padding:3px;margin-top:2px"> </small>
							{{-- {!!Form::select('new_expestado',$estados,null, ['placeholder' => 'Selecciona...', 'class' => 'form-control required', 'required' => 'required', 'id'=>'new_expestado' ]); !!} --}}
						</div>
					</div>
@endif

@if(currentUser()->hasRole("estudiante"))
					<div class="col-md-12">
						<div class="form-group">
							{!!Form::label('Motivo') !!}
							<select name="motivo_estado" placeholder="Seleccione..." id="motivo_estado" class="form-control required">

									<option value="">Seleccione...</option>
									@foreach($motivos_cierre as $motivo)
									@if($motivo->categoria=='estudiante') 
											<option value="{{ $motivo->id }}">{{ $motivo->nombre_motivo }}</option>
									@endif		
									@endforeach

							</select>
							
						</div>
					</div>

@endif

@if(currentUser()->hasRole("docente") or currentUser()->hasRole("amatai") or currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral'))
					<div class="col-md-12">
						<div class="form-group">
							{!!Form::label('Motivo') !!}
							<select name="motivo_estado" placeholder="Seleccione..." id="motivo_estado" class="form-control required">
									<option value="">Seleccione...</option>
									@foreach($motivos_cierre as $motivo)
									@if(currentUser()->hasRole('diradmin') or currentUser()->hasRole("amatai"))
										<option value="{{ $motivo->id }}">{{ $motivo->nombre_motivo }}</option>
									@else
										@if($motivo->categoria=='docente')
											<option value="{{ $motivo->id }}">{{ $motivo->nombre_motivo }}</option>
										@endif	
									@endif	
									@endforeach

							</select>
							
						</div>
					</div>

@endif

						<div class="col-md-12">
							<div class="form-group">
								{!!Form::label('Realice su Comentario: ') !!}
								{!!Form::textarea('comentario',  null , ['id'=>'comentario','class' => 'form-control required','maxlength'=>'4000', 'rows' => 2 ]); !!}
							</div>
						</div>


	<div class="col-md-12" align="right">
		<div class="form-group">
			<br>
			{!! link_to('#', 'Enviar', $attributes = array('id'=>'btn_exp_edit_cierre_caso', 'type'=>'button', 'class'=>'btn btn-primary','onclick'=>'storeEstadoCaso()'), $secure=null)!!}
		</div>
	</div>







{!!Form::close()!!}
	@endslot
@endcomponent



@if($expediente->getDocenteAsig()->idnumber == currentUser()->idnumber or currentUser()->hasRole("estudiante") or currentUser()->hasRole("amatai") or currentUser()->hasRole("diradmin") or currentUser()->hasRole('dirgral'))

<div class="col-md-12" align="right">
@if((currentUser()->hasRole("estudiante") and ($expediente->getDaysOrColorForClose('dias')>=10 || $expediente->exptipoproce_id !=1 )and ($expediente->estado->id == 1 || $expediente->estado->id == 3)) || 
(currentUser()->hasRole("docente") and $expediente->estado->id == 4) 
|| (currentUser()->hasRole("amatai")  or currentUser()->hasRole("diradmin") 
or currentUser()->hasRole('dirgral')))
		<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal_exp_edit_cierre_caso" id="btn_trigger_exp_edit_cierre_caso">
			Actualizar Solicitud de cierre 
		</button>
@endif
@if($expediente->exptipoproce_id ==1 and ($expediente->getDocenteAsig()->idnumber == currentUser()->idnumber and
 $expediente->expestado_id==5 and $expediente->isValidOpen()) || (currentUser()->hasRole("diradmin") || currentUser()->hasRole("amatai")) )
<button type="button" class="btn btn-warning btn-sm" id="btn_reabrir_caso">
	Volver a evaluar caso
</button>
@endif

@if($expediente->expestado_id==1 and (currentUser()->hasRole("dirgral") || currentUser()->hasRole("amatai") ))

<button type="button" class="btn btn-warning btn-sm" id="btn_cerrar_dr_caso">
	Cerrar caso
</button>

@endif


</div>
@endif

<div class="col-md-12">
	@if(count($expediente->estados)>0)
	<div class="box-body table-responsive no-padding">
	<table id="tbl_cierre_caso" class="table table-bordered table-striped dataTable dataTable" role="grid">

		<tr>
		<td width="15%">
			<label>Estado del Caso</label>
		</td>
		<td>
			<label>{{ $expediente->estado->nombre_estado }}</label>
		</td>
	</tr>

	<tr>
		<td>
			<label>Motivo</label>
		</td>
		<td>
			<label>{{ ($expediente->estados()->orderBy('created_at','desc')->first()->motivo->nombre_motivo) }}</label>
		</td>
	</tr>

	<tr>
		<td>
			<label>Último Comentario</label> 
		</td>
		<td>
			<textarea name="" class="textareaLastComentario" readonly="readonly">{{ ($expediente->estados()->orderBy('created_at','desc')->first()->comentario) }}</textarea>
		</td>
	</tr> 
</table>
</div>
@else
	<div class="box-body table-responsive no-padding">
<table id="tbl_cierre_caso" class="table table-bordered table-striped dataTable dataTable" role="grid">

		<tr>
		<td width="15%">
			<label>Estado del Caso</label>
		</td>
		<td>
			<label>{{ $expediente->estado->nombre_estado }}</label>
		</td>
	</tr>

	<tr>
		<td>
			<label>Motivo</label>
		</td>
		<td>
			<label>Apertura</label>
		</td>
	</tr>

	<tr>
		<td>
			<label>Último Comentario</label> 
		</td>
		<td>
			<textarea name="" class="textareaLastComentario" readonly="readonly">Sin comentarios aún.</textarea>
		</td>
	</tr> 
</table>
</div>
@endif
</div>

                 <div class="row">
                 	<hr>
                 	<h5>Ultimos estados</h5>
<hr>
					<div class="col-md-12">
						<div class="box-body table-responsive no-padding">
						<table class="table">
							<thead>
								<th>
									Usuario
								</th>
								<th>
									Rol
								</th>
								<th>
									Estado
								</th>
								<th>
									Motivo
								</th>
								<th>
									Fecha
								</th>
								<th>
									Acciones
								</th>
							</thead>
							<tbody>


								@foreach($expediente->estados()->orderBy('created_at','desc')->get() as $estado)
									<tr>
										<td>
											{{ $estado->user->name }}
										</td>
										<td>
											{{ $estado->user->role()->first()->display_name }}
										</td>
										<td>


											 @if  ($estado->estado->id =='1')
						                            <span class="pull-center badge bg-green dis-block">{{ $estado->estado->nombre_estado }}</span>

						                         @elseif ($estado->estado->id =='4')                            
						                             <span class="pull-center badge bg-yellow dis-block">{{ $estado->estado->nombre_estado }}</span>

						                         @elseif ($estado->estado->id =='2')
						                             <span class="pull-center badge bg-blue dis-block">{{ $estado->estado->nombre_estado }}</span>

						                         @elseif ($estado->estado->id =='3')
						                             <span class="pull-center badge bg-red dis-block">{{ $estado->estado->nombre_estado }}</span>
						                       @endif 

											
										</td>
										<td>
											{{ $estado->motivo->nombre_motivo }}
										</td>
										<td>
										<span title="Fecha en la que se envió la solicitud">{{ $estado->created_at }}</span> -- <span title="Números de días después de la asignación"> {{ $expediente->difDays($expediente->asignaciones[0]->fecha_asig,$estado->created_at) }} días </span>
										</td>
										<td>
											<button onclick="searchEstadoCaso({{ $estado->id }})" class="btn btn-success"></i> Detalles</button>
										</td>

									</tr>
									
								@endforeach
							</tbody>
						</table>
						</div>
					</div> <!-- /.md12-->              
               </div> <!-- /.row --> 

	

@component('components.modal_dynamic')


	@slot('size')
		modal-dialog modal-dialog
	@endslot
	
	@slot('trigger')
		myModal_show_details_searchEstadoCaso
	@endslot

	@slot('title')
		Detalles
	@endslot

 
	@slot('body')
 <div class="box-body table-responsive no-padding">
<table class="table">
	<tr>
		<td width="20%">
			<label>Usuario</label>
		</td>
		<td>
			<label id="nombre_usuario_details"></label>
		</td>
	</tr>
	<tr>
		<td>
			<label>Estado del Caso</label>
		</td>
		<td>
			<label id="nombre_estado_details"></label>
		</td>
	</tr>

	<tr>
		<td>
			<label>Motivo</label>
		</td>
		<td>
			<label id="nombre_motivo_details"></label>
		</td>
	</tr>
	<tr>
		<td>
			<label>Comentario</label>
		</td>
		<td>
			<textarea name="comen_details" id="comen_details" class="textareaLastComentario"></textarea>
		</td>
	</tr>
</table>

</div>





	@endslot
@endcomponent











