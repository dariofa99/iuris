@extends('layouts.dashboard')
@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection
@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
  
@endpush
@section('area_forms')

    @include('msg.alerts')

    <div class="row">
        <div class="col-md-4">
            <div class="btn-group" role="group" aria-label="...">

                {!! link_to('users/create', 'Nuevo', $attributes = ['type' => 'button', 'class' => 'btn btn-default']) !!}
            </div>
        </div>

        <div class="col-md-8">
            <form method='GET' id="myFormSearchUsers" style="width: 100%; float:r">

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control" name="criterio" id="criterio">
                                <option>Seleccione...</option>
                                <option @if ($criterio == 'idnumber') selected @endif value="idnumber">No de Documento
                                </option>
                                <option @if ($criterio == 'name') selected @endif value="name">Nombres</option>
                                <option @if ($criterio == 'rol') selected @endif value="rol">Rol</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                      <input type="hidden" value="{{$roles}}" id="rolesapi">
                        <div class="form-group">
                            {!! Form::select('data_search',[], null, [
                                'class' => 'form-control selectpicker select_data_users',
                                'data-live-search' => 'true',
                                'required' => 'required',
                                'id' => 'select_data_users',
                                'title'=>"Esperando busqueda",
                                "data-no-results-text"=>"No hay resultados coincidentes"
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Buscar</button>


                        <a href="/users" id="btn_seeall" class="btn btn-default">Ver Todo</a>
                    </div>

                </div>
            </form>
        </div>

    </div>







    <br>


    <div id='divc'>
        <div class="row">
            <div class="col-sm-12">
                <div id="table_list_model">
                    @include('myforms.frm_myusers_list_ajax')
                </div>

            </div>
        </div>

        <div>
        @stop
        @push('scripts')
            <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>
            <script type="module" src={{ asset('js/admin_users.js') }}></script>
        @endpush
