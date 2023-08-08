@extends('layouts.dashboard')



@section('titulo_area')
    Nueva Defensa de Oficio
@endsection
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

    {!! Form::open(['route' => 'oficio.store', 'method' => 'post', 'id' => 'myFormDefOfiStore']) !!}
    @if ($periodo != null)
        {!! Form::hidden('periodo_id', $periodo->id) !!}
    @endif
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Código Expediente: ') !!}
                {!! Form::text('expid', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '30']) !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Entidad:') !!}
                {!! Form::text('expjuzoent', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '60']) !!}
            </div>
        </div>


        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Tipo Proceso: ') !!}
                {!! Form::select('expramaderecho_id', $rama_derecho, null, [
                    'placeholder' => 'Selecciona...',
                    'class' => 'form-control required',
                    'required' => 'required',
                    'id' => 'expramaderecho_id',
                ]) !!}
            </div>
        </div>

    </div>


    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Estudiante</label>
                <select data-live-search="true" name="expidnumberest" required id="expidnumberest"
                    class="required form-control selectpicker">
                    @foreach ($estudiantes as $key => $estudiante)
                        <option value="{{ $estudiante['idnumber'] }}">
                            {{ $estudiante['full_name'] }}
                        </option>
                    @endforeach
                </select>
                {{--  <select class="form-control selectpicker" data-live-search="true" id="expidnumberest" name="expidnumberest">
                    @foreach ($users as $key => $user)
                        <option value="{{ $key }}" data-content="{{ $user }}">
                            {{ $user }}</option>
                    @endforeach
                </select> --}}
            </div>
        </div>

        <div class="col-md-4">
            {!! Form::label('Fecha límite de posesión: ') !!}
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fa fa-calendar"></i>
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

            <!-- /.input group -->
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group" align="right">
                @if ($periodo != null)
                    <button class="btn btn-primary">Enviar</button>
                @else
                    <button disabled type="button" class="btn btn-primary">No hay un periodo activo</button>
                @endif
                <br>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

@stop
@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>
    <script type="module"   src={{asset("js/admin_defensas_oficio.js")}}></script>
@endpush
