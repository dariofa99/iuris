@extends('layouts.dashboard')


@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">

    <style>
        .estselect1 {
            background: #e90b0b !important;
            color: #000 !important;
        }
    </style>
@endpush

@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('titulo_area')
    Nuevo expediente
@endsection


@section('area_buttons')

@endsection


@section('area_forms')
    @include('msg.alerts')
    <form action="{{ route('expedientes.store') }}" method='POST' id='myFormExpsStore'>
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('Código Expediente: ') !!}
                    {!! Form::text('expid', $id, [
                        'class' => 'form-control required',
                        'required' => 'required',
                        'maxlength' => '12',
                        'readonly',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-4">
                {!! Form::label('Identidicación Solicitante: ') !!}
                <div class="input-group">
                    <div class="input-group-btn">
                        {!! Form::button('Agregar', [
                            'class' => 'btn btn-success',
                            'data-toggle' => 'modal',
                            'data-target' => '#myModal_exp_user_edit',
                            'id' => 'btn_exp_user_carga_create',
                        ]) !!}
                    </div>
                    <!-- /btn-group -->
                    {!! Form::text('expidnumber', null, [
                        'class' => 'form-control required',
                        'required' => 'required',
                        'readonly',
                        'id' => 'expidnumber',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('Rama del Derecho: ') !!}
                    {!! Form::select('expramaderecho_id', $rama_derecho, null, [
                        'placeholder' => 'Selecciona...',
                        'class' => 'form-control required',
                        'required' => 'required',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('Tipo procedimiento: ') !!}
                    <select name="exptipoproce_id" id="exptipoproce_id2" class="form-control required" required="">
                        <option value="">Selecciona...</option>
                        @foreach ($tipo_proceso as $tipo_pro)
                          @if($tipo_pro->id!=3)  <option value="{{ $tipo_pro->id }}">{{ $tipo_pro->ref_tipproceso }}</option>@endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label>Estudiante</label>
                    <select data-style="btn-default" class="form-control required selectpicker estselect1"
                        data-live-search="true" id="expidnumberest" name="expidnumberest">
                        <option value="0000">Primero seleccione el Tipo Procedimiento...</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <br>
                    <input type="button" value="Detalles asignación" class="btn btn-info mt-1" id="btn_detalles_estudiante">
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group" align="right">
                <br>
                @if ($periodo)
                    <input type="hidden" name="periodo_id" id="periodo_id" value="{{ $periodo->id }}">
                    <button class="btn btn-primary">Enviar</button>
                @else
                    <button class="btn btn-primary" disabled>No hay un periodo activo</button>
                @endif
            </div>
        </div>
    </form>
    @include('myforms.frm_expediente_user_create')
    @include('myforms.frm_modal_dinamyc_js')
@stop
@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>
    <script type="module" src={{ asset('js/admin_expedientes.js?v='. config('app_config.asset_version')) }}></script>
@endpush
