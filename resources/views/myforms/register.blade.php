@extends('layouts.app')

@section('content')
<div class="login-box">
  
  
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card" style="margin-bottom: 25px;">
          <div class="card-header"><b>Registro solo para estudiantes matriculados.</b></div>
            <div class="card-body">


    
    @include('msg.danger')
    

    <form action="webservice" method="post" autocomplete="nope" id="myform">
      {!! csrf_field() !!}

      @if(count($sedes)>=2) 
<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      {!!Form::label('Seleccione una sede') !!}
      <select name="sede_id" id="sede_id" class="form-control required" required>
        <option value="">Seleccione...</option>
        @foreach($sedes as $key => $sede)
        <option value="{{$sede->id_sede}}">{{$sede->nombre}}</option>
        @endforeach
      </select>
    </div>
  
  </div>  
</div>
@elseif(count($sedes)==1)
<input type="hidden" name="sede_id" value="{{$sedes[0]->id_sede}}">
@endif


      <div class="row">
        <div class="col-md-12">
          <div class="form-group has-feedback {{ $errors->has('codigo') ? ' has-error' : '' }}">
        <input id='codigo' name='codigo' type="text" autocomplete="off" class="form-control" placeholder="Código estudiantil" value="{{ old('codigo') }}" required>
        <span class="nav-icon fa fa-id-badge form-control-feedback"></span>
      </div>
      @if ($errors->has('codigo'))
             <span class="help-block">
              <strong>{{ $errors->first('codigo') }}</strong>
                </span>
                @endif
        </div>
      </div> 
    
      <div class="row">
        <div class="col-md-12">
          <div class="form-group{{ $errors->has('idnumber') ? ' has-error' : '' }} has-feedback ">
        <input id='idnumber' name='idnumber' type="text" autocomplete="off" class="form-control" placeholder="Número de cédula" value="{{ old('idnumber') }}" required>
        <span class="nav-icon fa fa-id-card form-control-feedback"></span>
      </div>
                  @if ($errors->has('idnumber'))
             <span class="help-block">
              <strong>{{ $errors->first('idnumber') }}</strong>
                </span>
                @endif
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} has-feedback ">
        <input id='email' name='email' type="email" class="form-control" placeholder="Email" required value="{{ old('email') }}">
        <span class="nav-icon fa fa-envelope form-control-feedback"></span>
        @if ($errors->has('email'))
             <span class="help-block">
              <strong>{{ $errors->first('email') }}</strong>
                </span>
      @endif
      </div>
      
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback ">
        <input id='password' name='password' type="password" class="form-control" placeholder="Contraseña" required >
        <span class="nav-icon fa fa-unlock-alt form-control-feedback"></span>
        @if ($errors->has('password'))
             <span class="help-block">
              <strong>{{ $errors->first('password') }}</strong>
              </span>
      @endif
      </div>
      
        </div>
        
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="form-group has-feedback">
        <input id="password-confirm" placeholder="Confirmar contraseña" type="password" class="form-control" name="password_confirmation" required>
        <span class="nav-icon fa fa-unlock-alt form-control-feedback"></span>
      </div>

        </div>
        
      </div>

      <div class="row">
        <div class="col-md-6 offset-md-3">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Registrar</button>
        </div>
      </div>
  
    <hr>
        <div class="row">
          <div class="col-md-12">
            <a href="/password/reset">Olvide mi contraseña...</a>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <a href="/login">Iniciar sesión...</a>
          </div>
        </div>


    </form>
    <br>
   
    </div>

  </div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection