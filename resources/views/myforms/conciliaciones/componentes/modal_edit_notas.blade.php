
@component('components.modal')
	
@slot('trigger')
	myModal_edit_nota_conciliaciones
@endslot

@slot('title')
	Agregando Nota:: <h5>
		@if($periodo and $segmento)
		<label class="label bg-blue" style="font-size: 16px;">{{ $periodo->prddes_periodo }}</label>

		<label class="label bg-blue" style="font-size: 16px;">{{ $segmento->segnombre }}</label>
		@else
		<div class="alert alert-warning">
			<i class="fa fa-info"> </i> Asegurese que esten activos el periodo y el segmento de corte!
		</div>
		@endif
	</h5>	
@endslot


@slot('body')
@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')
{!!Form::open([ 'id'=>'myform_edit_nota_conciliacion'])!!}
{!!Form::hidden('orgntsid',5, ['class' => 'form-control required','id'=>'orgntsid' ]); !!}
{!!Form::hidden('tpntid',1, ['class' => 'form-control required','id'=>'tpntid' ]); !!} 
{!!Form::hidden('oficina_id',$conciliacion->id, ['class' => 'form-control required']); !!} 
{!!Form::hidden('typesub','store', ['class' => 'form-control']); !!} 
{!!Form::hidden('estidnumber',null, ['class' => 'form-control']); !!} 
@if($periodo and $segmento)
{!!Form::hidden('segid',$segmento->segmento_id, ['class' => 'form-control required','id'=>'segid' ]); !!}
{!!Form::hidden('perid',$periodo->periodo_id, ['class' => 'form-control required','id'=>'perid' ]); !!}	
@endif

<div class="row">

	<div id="content_nota_conciliador_edit">
		<div class="col-md-10">
			<div class="form-group">
				{!!Form::label('Nota: Manejo de la audiencia') !!}
				{!!Form::text('ntamanaudiencia',  null , ['class' => 'form-control required val_nota','id'=>'ntamanaudiencia', 'data-inputmask'=>"'mask': ['9.9']", 'data-mask'=>"" ]); !!}
			</div>
		</div>
	<div class="col-md-10">
		<div class="form-group">
			{!!Form::label('Nota: Análisis del caso y fórmulas de arreglo') !!}
			{!!Form::text('ntaanalisisformulas',  null , ['class' => 'form-control required val_nota','id'=>'ntaanalisisformulas', 'data-inputmask'=>"'mask': ['9.9']", 'data-mask'=>"" ]); !!}
		</div>
	</div>
	</div>
			
	<div id="content_nota_auxiliar_edit">
		<div class="col-md-10">
			<div class="form-group">
				{!!Form::label('Nota: Plantillas de conciliación') !!}
				{!!Form::text('ntaplanconciliacion',  null , ['class' => 'form-control required val_nota','id'=>'ntaplanconciliacion', 'data-inputmask'=>"'mask': ['9.9']", 'data-mask'=>"" ]); !!}
			</div>
		</div>

		<div class="col-md-10">
			<div class="form-group">
				{!!Form::label('Nota: Redacción del acta, constancia o informe') !!}
				{!!Form::text('ntaredaccacta',  null , ['class' => 'form-control required val_nota','id'=>'ntaredaccacta', 'data-inputmask'=>"'mask': ['9.9']", 'data-mask'=>"" ]); !!}
			</div>
		</div>
	</div>

				

				<div class="col-md-10">
					<div class="form-group">
						{!!Form::label('Nota: Puntualidad') !!}
						{!!Form::text('ntapuntualidad',  null , ['class' => 'form-control required val_nota','id'=>'ntapuntualidad', 'data-inputmask'=>"'mask': ['9.9']", 'data-mask'=>""]); !!}
					</div>
				</div>
				<div class="col-md-10">
					<div class="form-group">
						{!!Form::label('Nota: Presentación personal') !!}
						{!!Form::text('ntaprespersonal',  null , ['class' => 'form-control required val_nota','id'=>'ntaprespersonal', 'data-inputmask'=>"'mask': ['9.9']", 'data-mask'=>"" ]); !!}
					</div> 
				</div>

			
</div>



@if($periodo and $segmento)
<div class="row">
<div class="col-md-6">
	@if(((currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai'))))
    
	{{-- <input type="submit" class="btn btn-primary "value="Actualizar"> --}}
	@endif
</div>
</div>
@endif
</div>



{!!Form::close()!!}


@endslot
@endcomponent
<!-- /modal -->










