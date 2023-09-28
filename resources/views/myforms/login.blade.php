@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">

                <div class="card card-success">
                    <div class="card-header">
                        <b>Ingresar, solo si ya tienes una cuenta.</b>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}" id="myLoginForm">
                            {{ csrf_field() }}
                            @include('msg.alerts')
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row">
                                <label for="email"    class="col-md-3 col-form-label text-md-right">{{ __('Usuario') }}</label>
                                <div class="col-md-7">
                                    <input id="email" type="text" class="form-control" name="user_name"
                                        value="{{ old('email') }}" required placeholder="Correo o número de cédula"
                                        autofocus>

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
                                    <input id="password" type="password" class="form-control" name="password" required
                                        autocomplete="current-password">

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row" style="margin-top: 10px">
                                <div class="col-md-7 offset-md-3">
                                    <button type="submit" class="btn btn-warning btn-block">
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

                <div class="card card-success">
                    <div class="card-header">
                        <b>Solicitudes de conciliación.</b>
                    </div>
                    <div class="card-body">                     
                        <h5>  El centro de conciliación le ofrece la facilidad de solicitar
                            conciliaciones de manera virtual. <br>
                        </h5>
                        <div class="text-center my-4">
                         {{--   <a href="/solicitudes/conciliacion/recepcion?paso=1" class="btn btn-warning">
                                Solicitar (en desarrollo)
                            </a> --}}
                           
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

