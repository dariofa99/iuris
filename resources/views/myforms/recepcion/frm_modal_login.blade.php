@component('components.b4.modal_medium')

	@slot('trigger')
		myModal_login
	@endslot

	@slot('title')
		<h6><label id="lbl_modal_title">Iniciar sesión</label></h6>
     <span style="margin-left:15px;margin-top: -3px;"></span>
	@endslot


	@slot('body')


 	<div class="row">
		<div class="col-md-12 " id="content_form_cl">
@include('msg.danger')		       
        
  <form method="POST" action="{{ route('login') }}" id="myLoginForm">
                        {{ csrf_field() }}
      <input type="hidden" name="solicitud_id">                  

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row">
                            <label for="email_" class="col-md-3 col-form-label text-md-right">{{ __('Usuario') }}</label>

                            <div class="col-md-7">
                                 <input id="email" type="text" class="form-control" name="user_name" value="{{ old('email') }}" required placeholder="Correo o número de cédula">
                                     @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
 
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} row">
                            <label for="password_" class="col-md-3 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                            <div class="col-md-7">
                                <input id="password_" type="password" class="form-control" name="password" required autocomplete="current-password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-7 offset-md-3">
                                <button type="submit" class="btn btn-danger">
                                    {{ __('Ingresar') }}
                                </button>

                                @if (Route::has('password.request'))
                                <hr>
                                    <a href="/password/reset">Olvide mi contraseña...</a>
                                
                                    </a>
                                @endif
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

