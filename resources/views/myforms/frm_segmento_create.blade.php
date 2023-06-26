@component('components.modal')


	
	
	@slot('trigger')
		myModal_create_segmento
	@endslot 

	@slot('title')
	Creando Segmentos:

  @if($periodo)
  <br>
  <label> Periodo Activo: {{ $periodo->prddes_periodo }}</label>
  @else
  <div class="alert alert-warning"><i class="fa fa-info"> </i> No hay un periodo activo!</div>
  @endif

	@endslot

  
	@slot('body') 

@section('msg-contenido')
Registrado
@endsection 
@include('msg.ajax.success')

{!!Form::open([ 'id'=>'myform_create_segmento','files' => true])!!}
 @if($periodo)<input type="hidden" name="perid" value="{{ $periodo->periodo_id }}">@endif
		 <div class="form-group">
    <label for="segnombre">Nombre</label>
    <input type="text" class="form-control required" id="segnombre" name="segnombre" placeholder="Primer Corte">
    
  </div>
<div class="form-group">
    <label for="fecha_inicio">Fecha de Inicio</label>
    <input type="date" class="form-control required" name="fecha_inicio" id="fecha_inicio" placeholder="Email">
  </div>
  <div class="form-group">
    <label for="fecha_fin">Fecha Final</label>
    <input type="date" class="form-control required" name="fecha_fin" id="fecha_fin" >
  </div>
 @if($periodo)
  <button type="submit"  class="btn btn-primary">Enviar</button>
    @endif 
		
{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










