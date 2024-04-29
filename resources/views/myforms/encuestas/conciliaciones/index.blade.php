@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/plugins/dropzone59/dropzone.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="card">
                    <div class="card-header">
                        <div class="content_message">
                            Estimada persona usuaria, para el Centro de Conciliación "Eduardo Alvarado Hurtado" es muy
                            importante su
                            opinión sobre el acceso y la atención brindados. Por ello, en este documento podrá encontrar
                            algunos
                            criterios que nos ayudarán a establecer la evaluación y mejora continua del servicio. Recuerde
                            que su
                            participación es voluntaria y muy valiosa.
                        </div>

                    </div>
                    <div class="card-body">
                       
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('login') }}" id="myFormBuscarConciliacion">
                                            {{ csrf_field() }}
                                            @include('msg.alerts')
                                            <div class="form-group row justify-content-center" style="margin-top: 10px">
                                                <div class="col-md-12">
                                                    <label for="email" class="col-md-12">
                                                        Ingrese su correo o número de documento
                                                        con el que se registró a la conciliación.
                                                    </label>
                                                </div>
                                            </div>
                                            <div
                                                class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row">

                                                <div class="col-md-12">
                                                    <input id="email" type="text"
                                                        class="form-control form-control-sm" name="user_name"
                                                        value="{{ old('email') }}" required
                                                        placeholder="Correo o número de cédula" autofocus>

                                                    @if ($errors->has('email'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row justify-content-center" style="margin-top: 10px">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-warning btn-block btn-sm">
                                                        {{ __('Ingresar') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="card-footer">
                        <div class="row">

                            <div class="col-md-12">



                                <a href="/login" class="btn btn-default">
                                    Cancelar
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

    <script type="module" src={{asset("js/admin_encuestas.js")}}></script>
    
@endpush
