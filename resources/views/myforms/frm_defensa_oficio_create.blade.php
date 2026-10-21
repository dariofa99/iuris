@extends('layouts.dashboard')


@push('styles')
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
@endpush


@section('navbar')

    @include('content.navbar')

@endsection


@section('titulo_area')


@endsection


@section('area_forms')

    @include('msg.success')


    @if ($errors->any())

        <div class="alert alert-danger">

            <ul>

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    @include('myforms.frm_expediente_user_create')


    <form action="{{ route('oficio.store') }}" method="POST" id="myFormDefOfiStore">
        @csrf


        @if ($periodo != null)
            <input type="hidden" name="periodo_id" value="{{ $periodo->id }}">
        @endif


        <div class="iuris-form-card mb-4">


            {{-- ==================================================
             HEADER
        =================================================== --}}

            <div class="iuris-form-header">

                <div class="iuris-form-title">

                    <span class="iuris-form-icon">

                        <i class="fa fa-balance-scale" aria-hidden="true"></i>

                    </span>


                    <div>

                        <h5>
                            Nueva defensa de oficio
                        </h5>

                        <small>
                            Registro de información del caso
                        </small>

                    </div>

                </div>


                @if ($periodo != null)
                    <span class="iuris-periodo">

                        <i class="fa fa-check-circle"></i>

                        {{ $periodo->prddes_periodo }} activo

                    </span>
                @endif

            </div>


            {{-- ==================================================
             BODY
        =================================================== --}}

            <div class="iuris-form-body">

                <div class="row">


                    {{-- SOLICITANTE --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="expidnumber">Identificación del solicitante</label>


                            <div class="input-group">

                                <div class="input-group-prepend">

                                    <button type="button" class="btn btn-iuris-success" data-toggle="modal"
                                        data-target="#myModal_exp_user_edit" id="btn_exp_user_carga_create"
                                        aria-label="Buscar solicitante">
                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                    </button>

                                </div>


                                <input type="text" name="expidnumber" id="expidnumber" class="form-control required"
                                    value="{{ old('expidnumber') }}" placeholder="Seleccione..." required readonly>

                            </div>

                        </div>

                    </div>


                    {{-- EXPEDIENTE --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="expid">Código expediente</label>


                            <input type="text" name="expid" id="expid" class="form-control"
                                value="{{ old('expid') }}" maxlength="30" placeholder="Código del expediente" required>

                        </div>

                    </div>


                    {{-- ENTIDAD --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="expjuzoent">Entidad</label>


                            <input type="text" name="expjuzoent" id="expjuzoent" class="form-control"
                                value="{{ old('expjuzoent') }}" maxlength="60" placeholder="Nombre de la entidad" required>

                        </div>

                    </div>


                    {{-- TIPO PROCESO --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="expramaderecho_id">Tipo de proceso</label>


                            <select name="expramaderecho_id" id="expramaderecho_id" class="form-control required" required>
                                <option value="">Selecciona...</option>
                                @foreach ($rama_derecho as $id => $rama)
                                    <option value="{{ $id }}"
                                        {{ (string) old('expramaderecho_id') === (string) $id ? 'selected' : '' }}>
                                        {{ $rama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- ESTUDIANTE --}}

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="expidnumberest">Estudiante</label>
                            <select data-live-search="true" name="expidnumberest" id="expidnumberest"
                                class="form-control required selectpicker" required>
                                <option value="">Seleccione estudiante...</option>
                                @foreach ($estudiantes as $estudiante)
                                    <option value="{{ $estudiante['idnumber'] }}"
                                        {{ (string) old('expidnumberest') === (string) $estudiante['idnumber'] ? 'selected' : '' }}>
                                        {{ $estudiante['full_name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- FECHA --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="expfechalimite">Fecha límite de posesión</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-calendar text-primary"></i>
                                    </span>
                                </div>
                                <input type="date" name="expfechalimite" id="expfechalimite"
                                    class="form-control required" value="{{ old('expfechalimite', fechaActual()) }}"
                                    data-inputmask="'alias': 'yyyy/mm/dd'" data-mask required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- ==================================================
             FOOTER
        =================================================== --}}

            <div class="iuris-form-footer">
                @if ($periodo != null)
                    <span class="iuris-required">
                        <i class="fa fa-info-circle"></i>
                        Complete los campos requeridos
                    </span>
                    <button type="submit" class="btn btn-iuris-primary">
                        <i class="fa fa-save"> &ensp;
                        </i>
                        Registrar defensa
                    </button>
                @else
                    <span class="iuris-required warning">
                        <i class="fa fa-exclamation-circle"> </i>
                        No hay un periodo activo
                    </span>
                    <button disabled type="button" class="btn btn-secondary">

                        <i class="fa fa-lock"> </i>
                        No hay un periodo activo

                    </button>
                @endif


            </div>


        </div>


    </form>


@stop


@push('scripts')
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>

    <script type="module" src="{{ asset('js/admin_defensas_oficio.js?v=' . config('app_config.asset_version')) }}">
    </script>
@endpush
