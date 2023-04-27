
<form method="POST" action="{{ route('login') }}" id="myLoginForm">
    {{ csrf_field() }}
    <input type="hidden" name="solicitud_id" value="{{$solicitud->id}}">           

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row">
        <label for="email_" class="col-form-label text-md-right">{{ __('Usuario') }}</label>

             <input id="email" type="text" class="form-control" name="user_name" value="{{ old('email') }}" required placeholder="Correo o número de cédula">
                 @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
      
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} row">
        <label for="password_" class="text-md-right">{{ __('Contraseña') }}</label>

        
            <input id="password_" type="password" class="form-control" name="password" required autocomplete="current-password">

            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        
    </div>


    <div class="form-group mb-0">
        
            <button type="submit" class="btn btn-danger">
                {{ __('Ingresar') }}
            </button>

            @if (Route::has('password.request'))
            <hr>
            <a href="/password/reset">Olvide mi contraseña...</a>
            
            </a>
            @endif
       
    </div>
</form>