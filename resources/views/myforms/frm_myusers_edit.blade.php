@extends('layouts.dashboard')
@section('titulo_area')
Rol de Usuario: <strong> {{$user->roles[0]->display_name}} </strong>
@endsection
@section('area_forms')

@include('msg.alerts')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#home">Datos generales</a></li>
	<li><a data-toggle="tab" href="#menu1">Información Identitaria</a></li>
	<li><a data-toggle="tab" href="#menu2">Información socio-económica</a></li>
  </ul>
  <form id="myFormUserEdit">


  <div class="tab-content">
	<div id="home" class="tab-pane fade in active">
		<div class="row">
			<div class="col-md-10">
				@if((currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin')) AND $user->hasRole('docente') )
				<div class="form-group" align="right">			
					{!! Form::hidden('active_asignacion', '0') !!}		
					<input value="1" type="checkbox" {{($user->active_asignacion=='1') ? 'checked':''}} name="active_asignacion" id="active_asignacion">
					
					{!!Form::label('Asignación casos ') !!}                 
				</div> 
				@endif
			 </div> 
			 <div class="col-md-2">
				@if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral')  )
				<div class="form-group" align="right">			
					{!! Form::hidden('active', '0') !!}		
					
					<input value="1" type="checkbox" {{($user->active=='1') ? 'checked':''}} name="active" id="active">
					{!!Form::label('Usuario Activo ') !!}                 
				</div> 
				@endif
			 </div>  

			 <div class="col-md-4" >
				<div align="center" class="pict_container_profile">
					<div id="loader-container">
						<div class="progress">
							<div id="progress-bar" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 00%;">
							  00%
							</div>
						  </div>
					</div>
					<img  class='img-circle img_profile' id="img_profile" src="{{ is_file(public_path('thumbnails/'.$user->image)) ? asset('thumbnails/'.$user->image ) : asset('thumbnails/default.jpg' )}}" alt="User">
				</div>
				<div align="center">
					<input accept="image/*" style="display: none" id="file_picture" type="file"/>
					<i id="update_profile_picture" style="font-size:18px;cursor:pointer;margin:2px" class="fa fa-camera"></i>
				</div>
				
			</div>


			@include('myforms.users.formulario_registro',[
				'disabled'=>isset ($user) ? '' : '',
				'col'=>4
			])
			
			<div class="col-md-4">
				<div class="form-group">
					{!!Form::label('Contraseña: ') !!} 
					<i style="font-size: 12px" class="fa fa-question-circle is_tooltip" data-toggle="tooltip"  title="Nueva contraseña" data-original-title="Nueva contraseña"></i>
					 <div class="input-group">
				  <div class="input-group-addon" style="cursor: pointer;" onmousedown="showPassword('password')" onmouseup="showPassword('password')">
					<i class="fa fa-eye"></i>
				  </div>
				  {!!Form::password('password', ['class' => 'form-control','id'=>'password',isset($user) ? '' : '']); !!}
				  </div>
		
				  
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					{!!Form::label('Fecha Nacimiento: ') !!}
				 <div class="input-group">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
				  {!!Form::text('fechanacimien', isset($user) ? $user->fechanacimien : '', ['class' => 'form-control', 'required' => 'required','data-inputmask'=>"'alias': 'yyyy/mm/dd'" , 'data-mask',isset($user) ? '' : ''] ); !!}
				</div>
				<!-- /.input group -->
				</div>
			</div>
			@if ((currentUser()->hasRole('amatai') || currentUser()->hasRole('coordprac') || currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral')))
			<div class="col-md-4">
				<div class="form-group">
					{!!Form::label('Rol de Usuario: *') !!}					
					<select {{isset($user) ? '' : ''}} name="id_rol" id="id_rol" required class="form-control  required">
					<option value="">Seleccione...</option>
						@foreach ($roles as $key => $rol )
							<option {{(isset($user) and $user->roles[0]->id == $key) ? 'selected':''}} value="{{$key}}">{{$rol}}</option> 
						@endforeach
					</select>		
				</div>
			</div>
			@endif
			@if ((currentUser()->hasRole('estudiante') || ($user->hasRole('estudiante') and currentUser()->hasRole('amatai'))) and !$user->turno)
			
			<div class="col-md-4">
					<div class="form-group">
						{!!Form::label('Año Cursando ') !!}
						{!!Form::select('cursando_id',$cursando,$user->cursando_id,['placeholder' => 'Selecciona...', 'class' => 'form-control', 'required' => 'required' ]); !!}  
					</div>
				</div>
			@else
			@if (currentUser()->hasRole('estudiante') || currentUser()->hasRole('amatai'))
				<div class="col-md-4">
					<div class="form-group">
						{!!Form::label('Año Cursando') !!}
						{!!Form::select('cursando_id',$cursando,$user->cursando_id,['placeholder' => 'Selecciona...', 'class' => 'form-control', 'disabled' => 'disabled' ]); !!}  
					</div>
				</div>
			@endif	
			@endif
		
			@if(currentUser()->can('cambiar_sede'))
			<div class="col-md-4">
				<div class="form-group">
					{!!Form::label('Sede') !!}		
					<select class="form-control select2_ramas" multiple name="sede_id[]">
						@foreach($sedes as $key => $sede)		
						@php
						$selected = in_array($sede->id_sede, $user->sedes->pluck('pivot.sede_id')->toArray()) ? 'selected' : '';
						@endphp		
							<option  {{$selected}}  value="{{$sede->id_sede}}">{{$sede->nombre}}</option>
						@endforeach
					</select> 
			</div>
			</div>
			@endif
			@if($user->hasRole('docente') || (currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral')))
			<div class="col-md-4">				
					   <div class="form-group">	
						{!! form::label('ramaderecho_id','Ramas de derecho')!!}	
						   <select class="form-control select2_ramas" multiple name="ramaderecho_id[]" id="ramaderecho_id"> 
								 @foreach ($ramas_derecho as $id => $rama  )
								 @php
								 $selected = in_array($id, $user->ramas_derecho->pluck('pivot.ramaderecho_id')->toArray()) ? 'selected' : '';
								 @endphp								 
								 <option {{ $selected}} value="{{$id}}">{{$rama}}</option>	                    
								 @endforeach			   
						   </select>					   
					   </div>
			</div>	
			@endif
		


		</div>
	
	</div>
	<div id="menu1" class="tab-pane fade">
		@include('myforms.components_user.identitaria',[
			'disabled'=>isset($user) ? '' : ''
		])
		
	</div>
	<div id="menu2" class="tab-pane fade">
		@include('myforms.components_user.socioeconomica',[
			'disabled'=>isset($user) ? '' : ''
		])
	</div>
  </div>
  <div class="row">
	<div class="col-md-4">
		<button type="button" id="btn_actualizar_usuario" class="btn btn-primary btn-block">Actualizar</button>
	</div>
  </div>
 
</form>
@stop
@push('scripts')
	<script type="module"   src={{asset("js/admin_users.js")}}></script>
@endpush