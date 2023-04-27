@component('components.modal')
	
	@slot('trigger')
		myModal_change_docente_exp
	@endslot

	@slot('title')
	<label id="titulo_modal">Cambiando Docente:</label>	
	@endslot

 
	@slot('body')
@include('msg.ajax.success')

{!!Form::open([ 'id'=>'myform_change_docente_exp'])!!}
<input type="hidden" id="tipo_cambio" name="tipo_cambio" value="0">
	<div class="row">
        <div class="col-md-8 col-md-offset-2">
     <label>Seleccione el docente</label>
                {!!Form::select('new_docente_id',[],null,[
                       'class' =>'form-control',
                         'required' => 'required',
                        'id'=>'new_docente_id',
                        'data-width'=>'500px',
                        'placeholder'=>'Seleccione...'] ); !!}                      
       
       
        <hr>
       
        
                   <input type="submit" value="Solicitar cambio" class="btn btn-primary btn-block">
        
        </div>

    </div>
{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










