@component('components.modal')
	
	@slot('trigger')
		myModal_act_edit_doc
	@endslot

	@slot('title')
		Actualizando Biblioteca
	@endslot

 
	@slot('body')

<div class="row">
		<div class="col-md-10 col-md-offset-1">
		<div id="contenedo">
      {!!Form::open(['route'=>['biblioteca.update'], 'method'=>'POST', 'id'=>'myformUpdateBiblioteca' , 'files' => true])!!}
          <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
          <input type="hidden" name="biblioteca_id" value="" id="biblioteca_id">

        <div class="row"> 
          <div class="col-md-4">
            <div class="form-group">
                  <label for="biblinombre">Nombre archivo</label>
                  {!!Form::text('biblinombre',null, ['id' => 'biblinombre', 'class' => 'form-control required','placeholder'=>'Nombre','required' ]) !!}
                  
                </div>
          </div>


          <div class="col-md-4">
            <div class="form-group">
                  <label for="bibliid_ramaderecho">Rama del Derecho</label>
                  {!!Form::select('bibliid_ramaderecho',$rama_derecho,null,['id' => 'bibliid_ramaderecho_id', 'class' => 'form-control required','required' ]) !!}
                </div>
          </div>


          <div class="col-md-4">
            <div class="form-group">
                  <label for="bibliid_tipoarchivo">Tipo de Archivo</label>
                  {!!Form::select('bibliid_tipoarchivo',$tipo_archivo,null,['id' => 'bibliid_tipoarchivo_id', 'class' => 'form-control required','required' ]) !!}
                </div>
          </div>
          </div>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                  <label for="biblidescrip">Descripción</label>
                {!!Form::textarea('biblidescrip',null, ['id' => 'biblidescrip', 'class' => 'form-control required','placeholder'=>'Descripción...','rows'=>4,'required' ]) !!}
                 
                </div>
              
            </div>
          </div>

           <div class="row">
            <div class="col-md-8">
              <div class="form-group"> 
              <div class="con-btn">

                {!!Form::file('doc_file',['class'=>'inputfile','id'=>'doc_file']) !!}
                
                <label for="doc_file"> <i class="fa fa-upload"> </i> <span  id="label-upload"> Subir Archivo </span></label>
               <a href="#" id="link_doc" target="_blank"> <label for="" id="lab_doc_file"><i>Hola</i></label> </a>


              </div>                 
                <label class="label-alert bg-red" id="label-alert-doc-biblioteca">Debe subir un archivo.!</label>
                 
                </div>
              
            </div>
          </div>           
                
                
                <input type="button" class="btn btn-success" value="Actualizar" onclick="updateBiblioteca()">
{!!Form::close()!!}
 

        </div>
	</div>
	</div>
	
<div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
      </div>


	@endslot
@endcomponent
<!-- /modal -->










