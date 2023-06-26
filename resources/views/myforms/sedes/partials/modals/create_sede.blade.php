@component('components.b4.modal_medium')

	@slot('trigger')
		myModal_sede_create
	@endslot

	@slot('title')
		<h6><label id="lbl_modal_title">Sede</label></h6>
     <span style="margin-left:15px;margin-top: -3px;"></span>
	@endslot


	@slot('body')


 	<div class="row">
		<div class="col-md-12 " id="content_form_cl">
@include('msg.danger')		       
        
  <form method="POST" id="myFormCreateSede">
                        {{ csrf_field() }}
      <input type="hidden" name="id">                  

                        <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }} row">
                            <label for="nombre" class="col-md-3 col-form-label text-md-right">Nombre</label>
                            <div class="col-md-7">
                                 <input id="nombre" type="text" class="form-control" name="nombre" value="{{ old('nombre') }}" required placeholder="Nombre de la sede">
                                     @if ($errors->has('nombre'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('nombre') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
 
                        <div class="form-group{{ $errors->has('ubicacion') ? ' has-error' : '' }} row">
                            <label for="password_" class="col-md-3 col-form-label text-md-right">Ubicación</label>

                            <div class="col-md-7">
                                <input id="ubicacion" type="text" class="form-control" name="ubicacion" required>

                                @if ($errors->has('ubicacion'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('ubicacion') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                    {{--     <div class="form-group{{ $errors->has('horario') ? ' has-error' : '' }} row">
                            <label for="password_" class="col-md-3 col-form-label text-md-right">Ubicación</label>

                            <div class="col-md-7">
                                <input id="ubicacion" type="text" class="form-control" name="ubicacion" required>

                                @if ($errors->has('ubicacion'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('ubicacion') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}

                        <div class="form-group row mb-0">
                            <div class="col-md-7 col-md-offset-3">
                                <button type="submit" class="btn btn-primary btn-block">
                                  Crear
                                </button>

                               
                            </div>
                        </div>
                    </form>


         
		</div>
	</div>


	@endslot

  	@slot('footer')  
       <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>       
	@endslot
  
@endcomponent
<!-- /modal -->

