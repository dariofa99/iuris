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


    {!! Form::open([
        'route' => 'oficio.store',
        'method' => 'post',
        'id' => 'myFormDefOfiStore',
    ]) !!}


    @if ($periodo != null)

        {!! Form::hidden('periodo_id', $periodo->id) !!}

    @endif


    <div class="iuris-form-card">


        {{-- ==================================================
             HEADER
        =================================================== --}}

        <div class="iuris-form-header">

            <div class="iuris-form-title">

                <span class="iuris-form-icon">

                    <i class="fa fa-balance-scale"></i>

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

                        {!! Form::label(
                            'expidnumber',
                            'Identificación del solicitante'
                        ) !!}


                        <div class="input-group">

                            <div class="input-group-prepend">

                                {!! Form::button('<i class="fa fa-user-plus"></i>', [

                                    'class' => 'btn btn-iuris-success',

                                    'data-toggle' => 'modal',

                                    'data-target' =>
                                        '#myModal_exp_user_edit',

                                    'id' =>
                                        'btn_exp_user_carga_create',

                                ]) !!}

                            </div>


                            {!! Form::text('expidnumber', null, [

                                'class' =>
                                    'form-control required',

                                'required' =>
                                    'required',

                                'readonly',

                                'id' =>
                                    'expidnumber',

                                'placeholder' =>
                                    'Seleccione...',

                            ]) !!}

                        </div>

                    </div>

                </div>


                {{-- EXPEDIENTE --}}

                <div class="col-md-4">

                    <div class="form-group">

                        {!! Form::label(
                            'expid',
                            'Código expediente'
                        ) !!}


                        {!! Form::text('expid', null, [

                            'class' =>
                                'form-control',

                            'required' =>
                                'required',

                            'maxlength' =>
                                '30',

                            'placeholder' =>
                                'Código del expediente',

                        ]) !!}

                    </div>

                </div>


                {{-- ENTIDAD --}}

                <div class="col-md-4">

                    <div class="form-group">

                        {!! Form::label(
                            'expjuzoent',
                            'Entidad'
                        ) !!}


                        {!! Form::text('expjuzoent', null, [

                            'class' =>
                                'form-control',

                            'required' =>
                                'required',

                            'maxlength' =>
                                '60',

                            'placeholder' =>
                                'Nombre de la entidad',

                        ]) !!}

                    </div>

                </div>


                {{-- TIPO PROCESO --}}

                <div class="col-md-4">

                    <div class="form-group">

                        {!! Form::label(
                            'expramaderecho_id',
                            'Tipo de proceso'
                        ) !!}


                        {!! Form::select(
                            'expramaderecho_id',
                            $rama_derecho,
                            null,
                            [

                                'placeholder' =>
                                    'Selecciona...',

                                'class' =>
                                    'form-control required',

                                'required' =>
                                    'required',

                                'id' =>
                                    'expramaderecho_id',

                            ]
                        ) !!}

                    </div>

                </div>


                {{-- ESTUDIANTE --}}

                <div class="col-md-4">

                    <div class="form-group">

                        <label for="expidnumberest">

                            Estudiante

                        </label>


                        <select

                            data-live-search="true"

                            name="expidnumberest"

                            required

                            id="expidnumberest"

                            class="required form-control selectpicker"

                        >

                            <option value="">

                                Seleccione estudiante...

                            </option>


                            @foreach (
                                $estudiantes as $key => $estudiante
                            )

                                <option
                                    value="{{ $estudiante['idnumber'] }}"
                                >

                                    {{ $estudiante['full_name'] }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- FECHA --}}

                <div class="col-md-4">

                    <div class="form-group">

                        {!! Form::label(
                            'expfechalimite',
                            'Fecha límite de posesión'
                        ) !!}


                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text">

                                    <i class="fa fa-calendar text-primary"></i>

                                </span>

                            </div>


                            {!! Form::text(
                                'expfechalimite',
                                fechaActual(),
                                [

                                    'class' =>
                                        'form-control required',

                                    'id' =>
                                        'expfechalimite',

                                    'required' =>
                                        'required',

                                    'data-inputmask' =>
                                        "'alias': 'yyyy/mm/dd'",

                                    'data-mask',

                                ]
                            ) !!}

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


                <button
                    type="submit"
                    class="btn btn-iuris-primary"
                >

                    <i class="fa fa-save"></i>

                    Registrar defensa

                </button>

            @else

                <span class="iuris-required warning">

                    <i class="fa fa-exclamation-circle"></i>

                    No hay un periodo activo

                </span>


                <button
                    disabled
                    type="button"
                    class="btn btn-secondary"
                >

                    <i class="fa fa-lock"></i>

                    No hay un periodo activo

                </button>

            @endif


        </div>


    </div>


    {!! Form::close() !!}


@stop


@push('scripts')

    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>

    <script
        type="module"
        src="{{ asset('js/admin_defensas_oficio.js?v=' . config('app_config.asset_version')) }}"></script>

@endpush 