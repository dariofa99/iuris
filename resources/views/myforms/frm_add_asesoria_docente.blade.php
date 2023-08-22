@component('components.b4.modal_medium')
    @slot('trigger')
        myModal_add_asesoria_docente
    @endslot

    @slot('title')
        <small>
            Agregando Asesoría <br>
            Docente: {{ Auth::user()->name }} <br>
            Fecha: {{ date('d-m-Y') }}
        </small>
    @endslot
    @slot('body')
        @section('msg-contenido')
            Registrado
        @endsection
        @include('msg.ajax.success')

        {!! Form::open(['id' => 'myform_add_asesoria_docente']) !!}

        <input type="hidden" value="{{ $expediente->id }}" id="idact2">


        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                    {!! Form::label('Compartir con estudiante: ') !!}
                    <input type="checkbox" id="apl_shared" hidden="hidden" name="apl_shared" checked="true" value="1">
                    <i class="fa fa-toggle-on switch-on" id="switch_shared_asesoria_caso"></i>
                </div>
                <div class="form-group">
                    {!! Form::label('Asesoría docente: ') !!}
                    {!! Form::textarea('asesoria_docente', null, [
                        'class' => 'form-control required',
                        'maxlength' => '1000',
                        'id' => 'asesoria_docente',
                        'rows' => '7',
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <input type="submit" class="btn btn-primary" value="Enviar">
            </div>
        </div>


        {!! Form::close() !!}
    @endslot
@endcomponent
<!-- /modal -->
