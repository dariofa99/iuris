@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card" style="margin-bottom: 25px;">
        <div class="card-header"><b>Recuperar contraseña</b></div>
          <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
              @endif
              <form action="{{ route('password.email') }}" method="post">
                {!! csrf_field() !!}
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                      <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="Correo Electrónico">
                      @if ($errors->has('email'))
                      <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                          </span>
                          @endif
                  </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 offset-md-3">
                      <button type="submit" class="btn btn-primary btn-block btn-flat">Enviar link de recuperación</button>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <hr>
                  <a href="/login">Iniciar Sesión...</a>
                  </div>
                  <!-- /.col -->
                </div>
                  
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
