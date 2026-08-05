@extends('layouts.dashboard')
@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection
@section('area_forms')
@include('msg.success')
        <form id="myformSearchAutorizaciones" action="/autorizaciones">
            <div class="row">
                <div class="col-md-4">
                    <select name="tipo_busqueda" id="tipo_busqueda" class="form-control" placeholder="Seleccione..."
                        required="required">
                        <option value="">Seleccione...</option>
                        <option {{Request::get('tipo_busqueda')!='num_radicado'?:'selected'}} value="num_radicado">Número de radicado</option>
                        <option {{Request::get('tipo_busqueda')!='num_identificacion'?:'selected'}} value="num_identificacion">Número de documento estudiante</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input value="{{Request::get('data')==''?'':Request::get('data')}}" class="form-control input-search" required="required" id="input_data"
                        placeholder="No de documento a consultar" name="data" type="text">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success"><i class="fa fa-search"> </i> Buscar </button>
                    <a href="/autorizaciones" class="btn btn-default"> Ver todo </a>
                </div>
            </div>
        </form>
 
    <hr>
    <div class="row">
        <div class="col-md-12 table-responsive no-padding" id="content_list_autorizaciones">
            @include('myforms.frm_autorizaciones_list_ajax')
        </div>
    </div>
 @include('myforms.components_exp.frm_modal_create_autorizacion')
 
@stop
@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <script type="module"   src={{asset("js/admin_autorizaciones.js")}}></script>
@endpush
