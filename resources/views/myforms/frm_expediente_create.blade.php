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
  
@endsection


@section('area_buttons')

@endsection


@section('area_forms')

    @include('msg.alerts')

    <div class="iuris-form-card">

        {{-- HEADER --}}
        <div class="iuris-form-header">

            <div class="iuris-form-title">
                <span class="iuris-form-icon">
                    <i class="fa fa-folder-open"></i>
                </span>

                <div>
                    <h5>Nuevo expediente</h5>
                    <small>Registro de información del caso</small>
                </div>
            </div>

            @if ($periodo)
                <span class="iuris-periodo">
                    <i class="fa fa-check-circle"></i>
                   {{$periodo->prddes_periodo}} activo
                </span>
            @endif

        </div>


        <form action="{{ route('expedientes.store') }}" method="POST" id="myFormExpsStore">

            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

            <div class="iuris-form-body">

                <div class="row">

                    {{-- Código --}}
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


                    {{-- Solicitante --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            {!! Form::label('Identificación Solicitante: ') !!}

                            <div class="input-group">

                                <div class="input-group-prepend">

                                    {!! Form::button('<i class="fa fa-user-plus"></i>', [
                                        'class' => 'btn btn-iuris-success',
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
                                    'placeholder' => 'Seleccione...',
                                ]) !!}

                            </div>

                        </div>

                    </div>


                    {{-- Rama --}}
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


                    {{-- Tipo procedimiento --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            {!! Form::label('Tipo procedimiento: ') !!}

                            <select name="exptipoproce_id" id="exptipoproce_id2" class="form-control required" required>

                                <option value="">
                                    Selecciona...
                                </option>

                                @foreach ($tipo_proceso as $tipo_pro)
                                    @if ($tipo_pro->id != 3)
                                        <option value="{{ $tipo_pro->id }}">
                                            {{ $tipo_pro->ref_tipproceso }}
                                        </option>
                                    @endif
                                @endforeach

                            </select>

                        </div>

                    </div>


                    {{-- Estudiante --}}
                    <div class="col-md-5">

                        <div class="form-group">

                            <label>Estudiante</label>

                            <select data-style="btn-default" class="form-control required selectpicker estselect1"
                                data-live-search="true" id="expidnumberest" name="expidnumberest">

                                <option value="0000">
                                    Primero seleccione el Tipo Procedimiento...
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- Detalles --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>&nbsp;</label>

                            <button type="button" class="btn btn-iuris-info btn-block" id="btn_detalles_estudiante">

                                <i class="fa fa-info-circle"></i>
                                Detalles asignación

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="iuris-form-footer">

                @if ($periodo)
                    <input type="hidden" name="periodo_id" id="periodo_id" value="{{ $periodo->id }}">

                    <span class="iuris-required">
                        <i class="fa fa-info-circle"></i>
                        Complete los campos requeridos
                    </span>

                    <button type="submit" class="btn btn-iuris-primary">

                        <i class="fa fa-save"></i>
                        Registrar expediente

                    </button>
                @else
                    <span class="iuris-required warning">
                        <i class="fa fa-exclamation-circle"></i>
                        No hay un periodo activo
                    </span>

                    <button type="button" class="btn btn-secondary" disabled>

                        No hay un periodo activo

                    </button>
                @endif

            </div>

        </form>

    </div>


    @include('myforms.frm_expediente_user_create')
    @include('myforms.frm_modal_dinamyc_js')

@stop
@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>
    <script type="module" src={{ asset('js/admin_expedientes.js?v=' . config('app_config.asset_version')) }}></script>
@endpush
