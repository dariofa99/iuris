
@component('components.modal')
	
	@slot('trigger')
		myModal_add_nota_final_expedientes
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
{!!Form::open([ 'id'=>'myform_add_nota_final_expedientes'])!!}
	<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
	



<div>
	<div class="row">
	<div class="col-md-4">

	{!!Form::hidden('orgntsid',1, ['class' => 'form-control required','id'=>'orgntsid' ]); !!}
	{!!Form::hidden('tpntid',1, ['class' => 'form-control required','id'=>'tpntid' ]); !!} 
	{!!Form::hidden('expid',$expediente->expid, ['class' => 'form-control required','id'=>'expid' ]); !!} 
	@if($periodo and $segmento)
	{!!Form::hidden('segid',$segmento->segmento_id, ['class' => 'form-control required','id'=>'segid' ]); !!}
	{!!Form::hidden('perid',$periodo->periodo_id, ['class' => 'form-control required','id'=>'perid' ]); !!}	
	@endif
						<div class="form-group">
							{!!Form::label('Nota conocimiento') !!}
							{!!Form::text('ntaconocimiento',  null , ['class' => 'form-control required','id'=>'ntaconocimiento', 'data-inputmask'=>"'mask': ['9.9']", 'data-mask'=>"" ]); !!}
						</div>
					</div>


					<div class="col-md-4">
						<div class="form-group">
							{!!Form::label('Nota aplicación') !!}
							{!!Form::text('ntaaplicacion',  null , ['class' => 'form-control required','id'=>'ntaaplicacion', 'data-inputmask'=>"'mask': ['9.9']", 'data-mask'=>""]); !!}
						</div>
					</div>


					<div class="col-md-4">
						<div class="form-group">
							{!!Form::label('Nota Ética') !!}
							{!!Form::text('ntaetica',  null , ['class' => 'form-control required','id'=>'ntaetica', 'data-inputmask'=>"'mask': ['9.9']", 'data-mask'=>"" ]); !!}
						</div> 
					</div>
</div>
<div class="row">
	<div class="col-md-12">
						<div class="form-group">
							{!!Form::label('Concepto nota: ') !!}
							{!!Form::textarea('ntaconcepto',  null , ['class' => 'form-control required','maxlength'=>'100000','id'=>'ntaconcepto' ]); !!}
						</div>
	</div>
</div>
@if($periodo and $segmento)
<div class="row">
	<div class="col-md-6">
		<input type="button" class="btn btn-primary " id="btn_add_nota"	value="Enviar">
	</div>
</div>
@endif
</div>

	

	{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










