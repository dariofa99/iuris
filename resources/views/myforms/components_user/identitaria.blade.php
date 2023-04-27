<div id="content_sin_secc_component">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h5>Información Identitaria</h5>
        </div>
       <div class="col-md-6">
        <div class="form-group"> 
			<label for="genero_id">Sexo*</label>
			<select {{isset($disabled) ? $disabled : ''}} name="genero_id" id="genero_id" class="form-control required" required>
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
    </div>       
</div>