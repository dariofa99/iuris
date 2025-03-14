       <div class="col-md-12">
            <h4>Información Identitaria</h4>
        </div>
       <div class="col-md-{{isset($col)?$col:'6'}}">
        <div class="form-group"> 
            
			<label for="genero_id">Sexo<span class="ast_required">*</span></label>
			<select {{isset($disabled) ? $disabled : ''}} name="genero_id" id="genero_id" class="form-control form-control-sm required" required>
				<option value="">Seleccione...</option>
				@foreach($genero as $key => $tipo)
				<option {{(isset($user) and $user->genero_id == $key) ? "selected":"" }} value="{{$key}}">{{$tipo}}</option>
				@endforeach
			</select>
 
			
		</div>
       </div>
        @include('myforms.components_user.aditional_data',
        [
            "data"=>getReferencesDataBySection("enfoque_diferencial",'users')
        ])
         
