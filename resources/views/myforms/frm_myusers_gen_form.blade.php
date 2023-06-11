<form id="{{isset($user) ? 'myFormUserEdit': 'myFormUserCreate'}}" method="POST">
			@include('myforms.users.formulario_registro',[
                'disabled'=>isset ($user) ? 'disabled' : '',
                'col'=>4
            ])
			
			<div class="col-md-4">
				<div class="form-group">
					{!!Form::label('Contraseña: ') !!} 
                    <i style="font-size: 12px" class="fa fa-question-circle is_tooltip" data-toggle="tooltip"  title="" data-original-title="En blanco tomará el número de documento"></i>
					 <div class="input-group">
				  <div class="input-group-addon" style="cursor: pointer;" onmousedown="showPassword('password')" onmouseup="showPassword('password')">
					<i class="fa fa-eye"></i>
				  </div>
				  {!!Form::password('password', ['class' => 'form-control','id'=>'password',isset($user) ? 'disabled' : '']); !!}
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
				  {!!Form::text('fechanacimien', null, ['class' => 'form-control', 'required' => 'required','data-inputmask'=>"'alias': 'yyyy/mm/dd'" , 'data-mask',isset($user) ? 'disabled' : ''] ); !!}
				</div>
				<!-- /.input group -->
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					{!!Form::label('Rol de Usuario: *') !!}					
					<select {{isset($user) ? 'disabled' : ''}} name="idrol" id="idrol" required class="form-control  required">
					<option value="">Seleccione...</option>
						@foreach ($roles as $key => $rol )
							<option value="{{$key}}">{{$rol}}</option> 
						@endforeach
					</select>		
				</div>
			</div>
			@include('myforms.components_user.identitaria',[
                'disabled'=>isset($user) ? 'disabled' : '',
				'col'=>6
            ])
			@include('myforms.components_user.socioeconomica',[
                'disabled'=>isset($user) ? 'disabled' : '',
				'col'=>4
            ])
            @if(isset($user) and isset($sin_sede) and ($sin_sede))
            <div class="col-md-12">
                <div class="alert alert-info">
                    <h2><i class="fa fa-exclamation-circle"> Atención </i><br>
                        Se encontró al usuario pero no pertenece a esta sede.
                    </h2>       
                    <h3>¿Qué desea hacer?</h3>            
                    <button type="button" data-action="add" id="{{isset($user) ? 'add_sede_usuario': ''}}" class="btn btn-success add_or_change_sede_usuario"> Agregar a esta sede </button>
                    <button type="button" data-action="change" id="{{isset($user) ? 'change_sede_usuario': ''}}" class="btn btn-danger add_or_change_sede_usuario"> Cambiar a esta sede </button>
                    
                    <button type="button" onclick="window.location.reload(true)" class="btn btn-default"> Cancelar </button>
		
                </div>
            </div>
            @else
            <div class="col-md-3">
                @if(isset($user))
				<a href="/users/{{$user->id}}/edit" id="{{isset($user) ? 'actualizar_gen_us': 'registrar_gen_us'}}" class="btn btn-primary btn-block"> Actualizar usuario </a>
                @else
                <button type="button" id="{{isset($user) ? 'actualizar_gen_us': 'registrar_gen_us'}}" class="btn btn-primary btn-block"> Registrar usuario </button>
               
                @endif
            </div>
            <div class="col-md-4">
                @if(isset($user))
                <button type="button" onclick="window.location.reload(true)" class="btn btn-default"> Volver a buscar </button>
		        @endif
            </div>
            @endif
		
           
		</form> 