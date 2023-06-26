@extends('layouts.dashboard')
@section('titulo_area')
<button class="btn btn-primary btn-sm" id="btn_create_oficina">Nueva oficina</button>
@endsection

@section('area_forms')

@include('msg.success')
<div class="row">




</div>
<div class="row">
<div class="col-md-12 table-responsive no-padding" id="content_list_oficinas">

<table class="table" id="content_list_oficinas_table">	
				<thead>	
					<th>Nombre</th>
					<th>Ubicación</th>
					
					<th>Acciones</th>
				</thead>
				<tbody>	
                @include('myforms.frm_oficinas_list_ajax')				
						

				</tbody>

						

			</table>
			

</div>
</div>

@include('myforms.frm_modal_asig_user_oficina')
@include('myforms.frm_modal_list_user_oficina')
@include('myforms.frm_modal_oficina_create')

              @stop