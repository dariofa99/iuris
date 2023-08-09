@extends('layouts.app')

@push('styles')
    <style>

    </style>
@endpush

@section('content')



    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Registro de estudiantes matrículados
                </div>
                <div class="card-body">
                    <form action="webservice" method="post" autocomplete="off" id="myFormRegisterStudent">
                        {!! csrf_field() !!}
                        @if (count($sedes) >= 2)
                            {!! Form::label('Seleccione una sede*') !!}
                            <div class="form-group">
                                <select name="sede_id" id="sede_id" class="form-control required" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($sedes as $key => $sede)
                                        <option value="{{ $sede->id_sede }}">{{ $sede->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @elseif(count($sedes) == 1)
                            <input type="hidden" name="sede_id" value="{{ $sedes[0]->id_sede }}">
                        @endif

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <span class="nav-icon fa fa-id-badge"></span>
                                </span>
                            </div>
                            <input id='codigo_estudiantil' name='codigo_estudiantil' type="number" autocomplete="off" class="form-control"
                                placeholder="Código estudiantil" value="{{ old('codigo') }}" required>

                        </div>
                        @if ($errors->has('codigo_estudiantil'))
                            <span class="help-block">
                                <strong>{{ $errors->first('codigo_estudiantil') }}</strong>
                            </span>
                        @endif


                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <span class="nav-icon fa fa-id-card"></span>
                                </span>
                            </div>
                            <input id='idnumber' name='idnumber' type="number" autocomplete="off" class="form-control"
                                placeholder="Número de cédula" value="{{ old('idnumber') }}" required>

                        </div>
                        @if ($errors->has('idnumber'))
                            <span class="hel-block">
                                <strong>{{ $errors->first('idnumber') }}</strong>
                            </span>
                        @endif


                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <span class="nav-icon fa fa-envelope"></span>
                                </span>
                            </div>
                            <input id='email' name='email' type="email" class="form-control" placeholder="Email"
                                required value="{{ old('email') }}">
                        </div>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif

                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <button type="submit" class="btn btn-primary btn-block btn-flat">Registrar</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>


@endsection
@push('scripts')
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>
    <script type="module" src={{ asset('js/admin_users.js') }}></script>
@endpush
