@extends('layouts.app')

@section('content')
<div class="container">

  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="panel panel-success" style="margin-bottom: 25px;">
        <div class="panel-heading">
          @include('msg.alerts')
            <b>Recuperar contraseña.</b>
        </div>

        <div class="panel-body">
          <form action="{{ route('password.email') }}" method="post" id="myFormPasswordEmail">
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
                <a href="/login">Iniciar sesión...</a>
              </div>
              <!-- /.col -->
            </div>
              
          </form>
        </div>
      </div>
    </div>
  </div>
  </div>

@endsection

@push('scripts')
   <script>
    $(function () {
      $("#myFormPasswordEmail").on("submit",function() {
        $("#wait").show()
      })
    })
   </script>

@endpush
