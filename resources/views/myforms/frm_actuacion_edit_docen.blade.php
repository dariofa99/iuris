@component('components.modal_dynamic')


	@slot('size')
		modal-dialog modal-lg
	@endslot
	
	@slot('trigger')
		myModal_act_edit_docen
	@endslot 

	@slot('title')
		Editar docente
	@endslot

 
	@slot('body')
	@section('msg-contenido')
	Registrado
	@endsection 
	@include('msg.ajax.success')

{!!Form::open([ 'id'=>'myform_act_edit_docente','files' => true])!!}
	<input type="hidden" name="_token" value="{{csrf_token()}}" id="token"> 
	<input type="hidden" name="idact" id="idact">
	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Código expediente') !!}
			{!!Form::text('actexpid', $expediente->expid, ['id' => 'actexpid', 'class' => 'form-control', 'readonly' ]); !!}
		</div>
	</div>  
	<div class="col-sm-6">
		{!! Form::label('Fecha creación: ') !!}		  
		<div class="input-group">
		<div class="input-group-addon">
		    <i class="fa fa-calendar"></i>
		</div>
		{!!Form::text('actfecha', fechaActual(), ['id'=>'actfecha', 'class' => 'form-control', 'required' => 'required','data-inputmask'=>"'alias': 'yyyy/mm/dd'" , 'data-mask', 'readonly' ] ); !!}
		</div>
		 <!-- /.input group -->
	</div>




	<div class="col-md-12">
		<div class="form-group">
			{!!Form::label('Actuación') !!}
			{!!Form::text('actnombre',  null , ['id'=>'actnombre_cr', 'class' => 'form-control required','maxlength'=>'225',  'readonly' ]); !!}
		</div>
	</div>



	<div class="col-md-12">
		<div class="form-group">
			{!!Form::label('Descripción: ') !!}
			{!!Form::textarea('actdescrip',  null , ['id'=>'actdescrip','class' => 'form-control required','maxlength'=>'225', 'rows' => 4,  'readonly' ]); !!}
		</div>
	</div>



	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Estado de la actuación') !!}
			{!!Form::select('actestado_id', [	
										
										'102' => 'Realizar correcciones',
										
										'104' => 'Aprobar',

										'234' => 'Anular',
									],

			 null, ['id'=>'actestado','placeholder' => 'Selecciona...', 'class' => 'form-control required', 'required' => 'required' ]); !!}
		</div>
	</div>
<div class="col-md-6">
	 <div class="form-group"> 
              <div class="con-btn">

                {!!Form::file('actdocnomgen',['class'=>'inputfile','id'=>'actdocnomgen']) !!}
                
                <label for="actdocnomgen"> <i class="fa fa-upload"> </i> <span  id="label-upload"> Subir Archivo </span></label>
                <label for="" id="lab_doc_file"><i></i></label> 


              </div>                 
                <label class="label-alert bg-red" id="label-alert-doc-biblioteca">Debe subir un archivo.!</label>
                 
                </div>
</div>

<div class="col-md-12">
		<div class="form-group">
			{!!Form::label('Fecha límite de entrega:') !!}
			{!!Form::date('fecha_limit_doc',  null , ['min'=>date('Y-m-d'),'id'=>'fecha_limit_doc', 'class' => 'form-control required','maxlength'=>'225','disabled' ]); !!}
		</div>
	</div>


















	<div class="col-md-12">
		<div class="form-group">
			{!!Form::label('Recomendación: ') !!}
			{!!Form::textarea('actdocenrecomendac',  null , ['id'=>'actdocenrecomendac','class' => 'form-control required','maxlength'=>'10000', 'rows' => 4 ]); !!}
		</div>
	</div>





	 <div id="formAddNotas" class="addNotasAct" style="display: none">
	
	 <div class="col-md-4">
	{!!Form::hidden('orgntsid',2, ['class' => 'form-control required','id'=>'orgntsid' ]); !!}
	{!!Form::hidden('tpntid',1, ['class' => 'form-control required','id'=>'tpntid' ]); !!}
	@if($periodo and $segmento)
	{!!Form::hidden('segid',$segmento->id, ['class' => 'form-control required','id'=>'segid' ]); !!}
	{!!Form::hidden('perid',$periodo->id, ['class' => 'form-control required','id'=>'perid' ]); !!}
	@endif		
						<div class="form-group">
							{!!Form::label('Nota conocimiento') !!}
							{!!Form::text('ntaconocimiento',  null , ['class' => 'form-control required','id'=>'ntaconocimiento',  'data-inputmask'=>"'mask': ['9.9']", 'data-mask'=>"" ]); !!}
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
							{!!Form::label('Nota Ética.') !!}
							{!!Form::text('ntaetica',  null , ['class' => 'form-control required','id'=>'ntaetica',  'data-inputmask'=>"'mask': '9.9'", 'data-mask'=>"" ]); !!}
						</div> 
					</div>


	<div class="col-md-12">
						<div class="form-group">
							{!!Form::label('Concepto nota: ') !!}
							{!!Form::textarea('ntaconcepto',  null , ['class' => 'form-control required','maxlength'=>'100000','id'=>'ntaconcepto' ]); !!}
						</div>
	</div> 



</div>










@if($periodo and $segmento)
	<div class="col-md-12" align="right">
		<div class="form-group">
			<br>
			{!! link_to('#', 'Actualizar', $attributes = array('id'=>'btn_act_edit_docen', 'type'=>'button', 'class'=>'btn btn-primary'), $secure=null)!!}
		</div>
	</div>
	@else
	No existe un periodo o un segmento activo
@endif
	{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










