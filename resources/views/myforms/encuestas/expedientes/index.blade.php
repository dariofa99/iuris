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
                            Estimada persona usuaria, para Consultorios Jurídicos es muy
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
                                                        con el que se registró a Consultorios Jurídicos.
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group{{ $errors->has('tipodoc_id') ? ' has-error' : '' }} row">

                                                <div class="col-md-12">
                                                    <select {{ isset($disabled) ? $disabled : '' }} name="tipodoc_id"
                                                        id="tipodoc_id" class="form-control form-control-sm required"
                                                        required>
                                                        <option value="">Seleccione tipo de documento</option>
                                                        @foreach ($tipodoc as $key => $doc)
                                                         @if($key!=0)   <option  value="{{ $doc->id }}">{{ $doc->ref_nombre }}</option>@endif
                                                        @endforeach
                                                    </select>

                                                    @if ($errors->has('tipodoc_id'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('tipodoc_id') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>


                                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row">

                                                <div class="col-md-12">
                                                    <input id="idnumber" type="text"
                                                        class="form-control form-control-sm" name="idnumber"
                                                        value="{{ old('idnumber') }}" required
                                                        placeholder="Número de documento" autofocus>

                                                    @if ($errors->has('idnumber'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('idnumber') }}</strong>
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
    <script type="module" src={{ asset('js/admin_encuestas_exp.js?v=' . config('app_config.asset_version')) }}></script>
@endpush
