<div class="row">
<div class="col-md-4">
				    <label for="Identidicación Solicitante: ">Identidicación Solicitante: </label>
				    	<div class="input-group">
				                <div class="input-group-btn">		                  
				                  <button class="btn btn-success" type="button">Cambiar</button>
				                </div>
                			<!-- /btn-group -->
                			<input class="form-control required" required="required" value="{{$solicitud->idnumber}}" readonly="" id="idnumber" name="idnumber" type="text">
              			</div>
              		</div> <!-- /.md4-->  
<div class="col-md-4">
	<div class="form-group">
    <label>Rama del derecho</label>
    <select name="" id="" class="form-control">
    <option value="">Seleccione...</option>
    </select>	
    </div>
</div>

<div class="col-md-4">
	<div class="form-group">
    <label>Tipo de procedimiento</label>
    <select name="" id="" class="form-control">
    <option value="">Seleccione...</option>
    </select>	
    </div>
</div>

<div class="col-md-4">
	<div class="form-group">
    <label>Estudiante</label>
    <select name="" id="" class="form-control">
    <option value="">Seleccione...</option>
    </select>	
    </div>
</div>
{{-- 
<div class="col-md-3">
	<div class="form-group">
    <br>
    <button class="btn btn-primary btn-sm btn-block">Asignar expediente</button>
    </div>
</div> --}}


</div>