@component('components.modal')
	
	@slot('trigger')
		mymodalNuevaCitacion
	@endslot

	@slot('title')
		Enviando citación:
	@endslot

 
	@slot('body')


@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')

<div class="row">
    <div class="col-md-10 col-md-offset-1" id="ct_forcitaest">
        <form id="myformCitarEstudiante">
        <input type="hidden" class="form-control" id="id" name="id">
        <div class="form-row">
            <div class=" col-md-12">
                <label for="fecha">Estudiante:</label>
                <span id="lbl_nombre_estudiante">{{$expediente->estudiante->name}} {{$expediente->estudiante->lastname}} </span>
            </div>
             <div class=" col-md-12">
                <label for="fecha">No Expediente:</label>
                <span id="lbl_expid">{{$expediente->expid}}</span>
            </div>
        </div>
<div class="form-row">
  <div class="form-group col-md-6">
    <label for="fecha">Fecha</label>
    <input type="date" min="{{date('Y-m-d')}}" class="form-control" id="fecha" name="fecha">
  </div>
  <div class="form-grou col-md-6">
    <label for="hora">Hora</label>
    <div class="bootstrap-timepicker">
            			   
								<div class="input-group">
									<input type="text" id="hora" name="hora" class="form-control timepicker" value="">
									<div class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</div>
								</div>
    
   </div> 


   </div>
  </div>
  <div class="form-row">
    <div class="form-group col-md-12">
      <label for="motivo">Motivo</label>
      <textarea name="motivo" id="motivo" class="form-control" rows="4"></textarea>
    </div>
  </div> 

  <div class="form-row">
    <div class="form-group col-md-12">
       <button type="submit" class="btn btn-primary btn-block btn-flat">Citar</button>
    </div>
  </div> 
  
 
 
</form>
 </div> 
</div>
<div class="row">
  <div class="col-md-12">
  <table class="table" style="display:none" id="menu_details_citas">
<thead>
<th>
Hora
</th>
<th>
Motivo
</th>
<th>
Estudiante
</th>
<th>
No Expediente
</th>
</thead>
<tbody>
</tbody>
  </table>
  </div>
</div>
	@endslot
@endcomponent
<!-- /modal -->










