@component('components.b4.modal_medium')

    @slot('size')
        modal-dialog modal-dialog
    @endslot

    @slot('trigger')
        myModal_iniciar_sesion
    @endslot

    @slot('title')
        Ingresando a IURIS
    @endslot


    @slot('body')
        <div class="row">
            <div class="col-md-12 pt-3">

                <div class="card card-success">
                   
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}" id="myLoginForm">
                            {{ csrf_field() }}
                            @include('msg.alerts')
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row">
                                <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('Usuario') }}</label>
                                <div class="col-md-7">
                                    <input id="email" type="text" class="form-control form-control-sm" name="user_name"
                                        value="{{ old('email') }}" required placeholder="Correo o número de cédula" autofocus>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} row">
                                <label for="password"
                                    class="col-md-3  col-form-label text-md-right">{{ __('Contraseña') }}</label>
                                <div class="col-md-7">
                                    <input id="password" type="password" class="form-control form-control-sm" name="password"
                                        required autocomplete="current-password">

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row" style="margin-top: 10px">
                                <div class="col-md-7 offset-md-3">
                                    <button type="submit" class="btn btn-warning btn-block btn-sm">
                                        {{ __('Ingresar') }}
                                    </button>
                                    @if (Route::has('password.request'))
                                        <hr>
                                        <a href="/password/reset">
                                            Olvide mi contraseña...</a>
                                    @endif
                                </div>
                            </div>
                     {{--        <div class="form-group">
                                <a target="_blank" style="border-bottom:1px solid gray;color: black;font-size:14px"
                                    href="/videos">
                                    Ver videos de ayuda
                                </a>
                                <br>

                                <a target="_blank" style="border-bottom:1px solid gray;color: black;font-size:14px"
                                    href="{{ url('conciliacion/encuestas/start') }}">
                                    Encuesta (desarrollo)
                                </a>
                            </div> --}}
                        </form>
                    </div>
                </div>

            </div>
        </div>
    @endslot
@endcomponent
