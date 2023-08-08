@extends('layouts.dashboard')


@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('titulo_area')
    Nuevo documento general
@endsection

@section('area_forms')

    @include('msg.success')

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success" role="alert" id="msg-success" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                Biblioteca creada con éxito...
                <span class="sr-only">Error:</span>
            </div>
        </div>

        <div class="col-md-10 col-md-offset-1">
            <div id="contenedo">
                {!! Form::open([
                    'route' => 'bibliotecas.store',
                    'method' => 'POST',
                    'id' => 'myformCreateBiblioteca',
                    'files' => true,
                ]) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="biblinombre">Nombre</label>
                            {!! Form::text('biblinombre', null, [
                                'id' => 'biblinombre',
                                'class' => 'form-control required',
                                'placeholder' => 'Nombre',
                                'required',
                            ]) !!}

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bibliid_ramaderecho">Rama del Derecho</label>
                            {!! Form::select('bibliid_ramaderecho', $rama_derecho, null, [
                                'id' => 'bibliid_ramaderecho',
                                'class' => 'form-control required',
                                'placeholder' => 'Seleccione...',
                                'required',
                            ]) !!}
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bibliid_tipoarchivo">Tipo de Archivo</label>
                            {!! Form::select('bibliid_tipoarchivo', $tipo_archivo, null, [
                                'id' => 'bibliid_tipoarchivo',
                                'class' => 'form-control required',
                                'placeholder' => 'Seleccione...',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="bibliid_tipoarchivo">Descripción</label>
                            {!! Form::textarea('biblidescrip', null, [
                                'id' => 'biblidescrip',
                                'class' => 'form-control required',
                                'placeholder' => 'Descripción...',
                                'rows' => 4,
                                'required',
                            ]) !!}
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <div class="con-btn">

                                {!! Form::file('doc_file', ['id' => 'doc_file']) !!}

                                {{-- <label for="doc_file"> <i class="fa fa-upload"> </i> <span id="label-upload"> Subir Archivo
                                    </span></label>
                                <label for="" id="lab_doc_file"><i></i></label> --}}


                            </div>
                           <label class="label-alert bg-red" id="label-alert-doc-biblioteca">
                                Debe subir un archivo.!</label> 

                        </div>

                    </div>
                </div>
                <input type="submit" class="btn btn-success" value="Guardar">
                {!! Form::close() !!}


            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script type="module" src={{ asset('js/admin_bibliotecas.js') }}></script>
@endpush
