  <div class="container">
        <div class="row">
            {{-- <div class="divider d-flex align-items-center my-4 d-block d-sm-block d-md-none">
            <p class="text-center fw-bold mx-3 mb-0"></p>
          </div> --}}
            <div class="col-md-4">
                <form>
                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                        <p class="lead fw-normal mb-0 me-3">Quieres solicitar una audiencia de conciliación?</p>

                    </div>

                    <div class="divider d-flex align-items-center my-4">
                        <p class="text-center fw-bold mx-3 mb-0"></p>
                    </div>

                    <p class="small fw-normal mb-0 me-3">Encuentra apoyo, en unos sencillos pasos.</p>

                    <div class="text-center text-lg-start mt-4 pt-2">
                        <a href="/solicitudes/conciliacion/recepcion?paso=1" class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Solicitar</a>

                    </div>

                </form>
            </div>
            {{-- <div class="divider d-flex align-items-center my-4 d-block d-sm-block d-md-none">
            <p class="text-center fw-bold mx-3 mb-0"></p>
          </div> --}}
            <div class="text-center col-md-4 ">
                <img src="{{ asset('dist/img/conciliapp logp piezas-01.png') }}" class="img-fluid" alt="Sample image"
                    width="90%">
            </div>
            <div class="col-md-4">
                <form method="POST" action="{{ route('login') }}" id="myLoginForm">
                    {{ csrf_field() }}
                    @include('msg.alerts')
                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                        <p class="lead fw-normal mb-0 me-3">Seguimiento a una conciliación</p>

                    </div>
                    <div class="divider d-flex align-items-center my-4">
                        <p class="text-center fw-bold mx-3 mb-0"></p>
                    </div>
                    <p class="small fw-normal mb-0 me-3">Si ya tienes una conciliación con nosotros entonces inicia sesión.
                    </p>
                    <br>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row">
                       {{--  <label for="email" class="col-md-3 col-form-label text-md-right">
                            {{ __('Usuario') }}</label> --}}
                        <div class="col-md-12">
                            <input id="email" type="text" class="form-control" name="user_name"
                                value="{{ old('email') }}" required placeholder="Correo o número de cédula" autofocus>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} row">
                      {{--   <label for="password" class="col-md-3 col-form-label text-md-right">
                            {{ __('Contraseña') }}</label> --}}
                        <div class="col-md-12">
                            <input id="password" type="password" class="form-control" name="password" required
                                autocomplete="current-password" placeholder="Contraseña">

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row" style="margin-top: 10px">
                        <div class="col-md-7 offset-md-3">
                            <button type="submit" class="btn btn-primary btn-block">
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