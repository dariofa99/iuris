@extends('layouts.dashboard')




@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection
@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
    <style>

    </style>
@endpush

@section('titulo_area')
    Defensas de Oficio
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

    <div class="row">
        <div class="col-md-12">

            <div class="card shadow-sm border-0">

                {{-- HEADER --}}
                <div class="card-header bg-white border-0 py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h4 class="mb-1 font-weight-bold text-dark">
                                <i class="fa fa-file-text-o text-primary mr-2"></i>
                                Nueva Defensa de Oficio
                            </h4>

                            <small class="text-muted">
                                Complete la información para registrar el expediente
                            </small>
                        </div>

                        @if ($periodo != null)
                            <span class="badge badge-success p-2">
                                <i class="fa fa-check-circle"></i>
                                Periodo activo
                            </span>
                        @endif

                    </div>

                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- SOLICITANTE --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                {!! Form::label('expidnumber', 'Identificación del solicitante') !!}

                                <div class="input-group">

                                    <div class="input-group-btn">
                                        {!! Form::button('Agregar', [
                                            'class' => 'btn btn-success',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#myModal_exp_user_edit',
                                            'id' => 'btn_exp_user_carga_create',
                                        ]) !!}
                                    </div>

                                    {!! Form::text('expidnumber', null, [
                                        'class' => 'form-control required',
                                        'required' => 'required',
                                        'readonly',
                                        'id' => 'expidnumber',
                                        'placeholder' => 'Seleccione un solicitante',
                                    ]) !!}

                                </div>

                            </div>

                        </div>


                        {{-- EXPEDIENTE --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                {!! Form::label('expid', 'Código Expediente') !!}

                                {!! Form::text('expid', null, [
                                    'class' => 'form-control',
                                    'required' => 'required',
                                    'maxlength' => '30',
                                    'placeholder' => 'Código del expediente',
                                ]) !!}

                            </div>

                        </div>


                        {{-- ENTIDAD --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                {!! Form::label('expjuzoent', 'Entidad') !!}

                                {!! Form::text('expjuzoent', null, [
                                    'class' => 'form-control',
                                    'required' => 'required',
                                    'maxlength' => '60',
                                    'placeholder' => 'Nombre de la entidad',
                                ]) !!}

                            </div>

                        </div>


                        {{-- TIPO PROCESO --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                {!! Form::label('expramaderecho_id', 'Tipo Proceso') !!}

                                {!! Form::select('expramaderecho_id', $rama_derecho, null, [
                                    'placeholder' => 'Selecciona...',
                                    'class' => 'form-control required',
                                    'required' => 'required',
                                    'id' => 'expramaderecho_id',
                                ]) !!}

                            </div>

                        </div>


                        {{-- ESTUDIANTE --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="expidnumberest">
                                    Estudiante
                                </label>

                                <select data-live-search="true" name="expidnumberest" required id="expidnumberest"
                                    class="required form-control selectpicker">

                                    <option value="">
                                        Seleccione estudiante...
                                    </option>

                                    @foreach ($estudiantes as $key => $estudiante)
                                        <option value="{{ $estudiante['idnumber'] }}">
                                            {{ $estudiante['full_name'] }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{-- FECHA --}}
                        <div class="col-md-4">

                            {!! Form::label('expfechalimite', 'Fecha límite de posesión') !!}

                            <div class="input-group">

                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-calendar text-primary"></i>
                                    </span>
                                </div>

                                {!! Form::text('expfechalimite', fechaActual(), [
                                    'class' => 'form-control required',
                                    'id' => 'expfechalimite',
                                    'required' => 'required',
                                    'data-inputmask' => "'alias': 'yyyy/mm/dd'",
                                    'data-mask',
                                ]) !!}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="card-footer bg-light border-0 text-right">

                    @if ($periodo != null)
                        <button type="submit" class="btn btn-primary px-4">

                            <i class="fa fa-save mr-1"></i>
                            Registrar

                        </button>
                    @else
                        <button disabled type="button" class="btn btn-secondary">

                            <i class="fa fa-lock mr-1"></i>
                            No hay un periodo activo

                        </button>
                    @endif

                </div>

            </div>

        </div>
    </div>

    {!! Form::close() !!}

@stop
@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>
    <script type="module" src={{ asset('js/admin_defensas_oficio.js') }}></script>
@endpush
