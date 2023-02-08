@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card" style="margin-bottom: 25px;">
                <div class="card-header"><b>Ingresar, solo si ya tienes una cuenta.</b></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" id="myLoginForm">
                        {{ csrf_field() }}
                        @include('msg.alerts')
 
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row">
                            <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('Usuario') }}</label>

                            <div class="col-md-7">
                                <input id="email" type="text" class="form-control" name="user_name" value="{{ old('email') }}" required placeholder="Correo o número de cédula" autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} row">
                            <label for="password" class="col-md-3 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                            <div class="col-md-7">
                                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-7 offset-md-3">
                                <button type="submit" class="btn btn-warning">
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
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><b>¿No tienes cuenta?</b></div>

                <div class="card-body">
                    

                        <div class="form-group row">
                                <label class="desc" id="title1" for="Field1" style="text-align: justify; font-size: 12pt;font-weight: normal; margin: 0px 15px 0px 20px;">

Sigue estos pasos:<br>
1. Llene el Formulario con sus datos.<br>
2. Espere su turno para la revisión de su caso.<br>
3. Registre un correo y una contraseña para poder acceder al sistema y continuar con la atención.<br>

</label>
                        </div>




                        <div class="form-group row mb-0">
                              <div class="col-md-6" style="text-aling:center" align="center">
                                <a class="btn btn-warning" href="/solicitudes/conciliacion/recepcion">
                                  Solicitar conciliación
                                </a>

                                {{-- <a class="btn btn-warning" href="/recepcion">
                                    Solicitar atención
                                  </a> --}}

                            </div>
                            <div class="col-md-6" style="text-aling:center" align="center">
                                <a class="btn btn-warning" href="/videos" target="_blank">
                                  <i class="fa fa-play-circle-o" aria-hidden="true"></i> Vídeo tutorial
                                </a>
                            </div>

                        </div>
    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
