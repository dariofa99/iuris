@extends('layouts.app')
@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
  
@endpush
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="margin-bottom: 25px;">
                    <div class="card-header">
                        <b>Atención!</b>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                {{-- <img style="width: 450px;height:410px" src="{{asset("/dist/img/revisando.jpg")}}" alt="">
                 --}} </div>
                            <div class="col-md-10">
                                
                                    <div class="alert alert-warning">
                                    <h2>
                                        Se ha enviado un correo electrónico a <i>{{ Request::get('email') }} </i>
                                        para veríficar y activar la cuenta.
                                        </h2>    <br>
                                    </div>

                                    <hr>
                                    Si no tiene acceso al correo deberá cambiarlo por uno activo.






                            
                                <form id="myFormChangeEmailAccount">
                                    <input type="hidden" name="oldemail" id="oldemail" value="{{Request::get("email")}}">
                                    <input type="hidden" name="token" id="token" value="{{Request::get("token")}}">
                                    <div class="form-group">
                                        <label for="email">Ingrese un nuevo correo electrónico</label>
                                        <input type="email" required class="form-control" name="email" id="email">
                                    </div>
                                    <div class="form-group">
                                      <button class="btn btn-success btn-sm">
                                        Cambiar correo electrónico
                                      </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>
         
        <script type="module" src={{ asset('js/admin_users.js') }}></script>
@endpush