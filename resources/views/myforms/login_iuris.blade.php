<div class="container">
        <div class="row">
            <div class="col-md-5 pt-3">

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
                                    <input id="email" type="text" class="form-control form-control-sm" name="user_name"
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
                                <label for="password" class="col-md-3  col-form-label text-md-right">{{ __('Contraseña') }}</label>
                                <div class="col-md-7">
                                    <input id="password" type="password" class="form-control form-control-sm" name="password" required
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
                            <div class="form-group">
                                <a target="_blank" style="border-bottom:1px solid gray;color: black;font-size:14px" href="/videos">
                                    Ver videos de ayuda
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="col-md-7">

                <div class="card card-success">
                    <div class="card-header">
                        <b>Solicitudes de conciliación.</b>
                    </div>
                    <div class="card-body">                     
                       <strong> ¿Qué es la conciliación?</strong><br>
                        <small> 
                            Según el Programa Nacional de Conciliación 
                            impulsado por el Ministerio de Justicia y
                             Derecho, la conciliación “es un mecanismo 
                             de resolución de conflictos a través del 
                             cual, dos o más personas gestionan por sí
                              mismas la solución de sus diferencias, con 
                              la ayuda de un tercero neutral y calificado
                               denominado conciliador”.
                            <br>
                            Así pues, la conciliación es un acto jurídico 
                            mediante el cual se realiza el acercamiento de 
                            los distintos intereses de las personas 
                            intervinientes con el fin de llegar a un 
                            acuerdo que beneficie a ambas partes,
                             teniendo de presente que la diligencia cuenta
                              con el consentimiento y la voluntad de 
                              solucionar un conflicto. <br>
                            </small>
                      
                        <div class="text-center my-4">
                           <a id="btn_solicitar_conciliacion" href="/solicitudes/conciliacion/recepcion?paso=1" class="btn btn-primary">
                                Solicitar conciliación <i>(en periodo de pruebas)</i>
                            </a> 
                           
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>