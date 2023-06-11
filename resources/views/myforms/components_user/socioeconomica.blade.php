<div id="content_sin_secc_component">
    <div class="row">
        <div class="col-md-12">
            <h4>Información socio-económica</h4>
        </div>
       
        <div class="col-md-{{isset($col)?$col:'6'}}">
            <div class="form-group">
                <label for="estadocivil_id">Estado civil*</label>
                <select {{isset($disabled) ? $disabled : ''}} name="estadocivil_id" id="estadocivil_id" class="form-control required" required>
                    <option value="">Seleccione...</option>
                    @foreach($estcivil as $key => $tipo)
                    <option {{(isset($user) and $user->estadocivil_id == $key) ? "selected":"" }} value="{{$key}}">
                        {{$tipo}}
                    </option>
                    @endforeach
                </select> 
            </div>
        </div>

        <div class="col-md-{{isset($col)?$col:'6'}}">
            <div class="form-group">
                <label for="estrato_id">Estrato*</label>
                <select {{isset($disabled) ? $disabled : ''}} name="estrato_id" id="estrato_id" class="form-control required" required>
                    <option value="">Seleccione...</option>
                    @foreach($estrato as $key => $tipo) 
                    <option {{(isset($user) and $user->estrato_id == $key) ? "selected":"" }} value="{{$key}}">{{$tipo}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @include('myforms.components_user.aditional_data',
        [
            "data"=>getReferencesDataBySection("socio_economica",'users')
        ])
    </div>       
</div>