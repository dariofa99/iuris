@extends('layouts.dashboard')

@section('titulo_general')
    Categorias

@endsection
@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection


@section('titulo_area')
    <div class="pull-left" style="float: left !important;">
        <button class="btn btn-primary btn-sm" id="btn_new_category">Nueva categoría</button>
    </div>
@endsection

@section('area_buttons')

@endsection


@section('area_forms')

    @include('msg.success')
    <form action="{{ url('categorias') }}" method="GET">
        <div class="row">

            <div class="col-md-12">
                <label for="search_category">Buscar categoría</label>
            </div>
            <div class="col-md-4">
                <div class="form-group">

                    <input type="text" name="search_category" id="search_category" class="form-control form-control-sm"
                        placeholder="Escriba el nombre de la categoría">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <button class="btn btn-primary btn-sm" id="btn_search_category">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>

        </div>
    </form>
    <div class="row">
        <div class="col-md-12 table-responsive no-padding" id="content_categories_list">
            @include('myforms.categorias.partials.ajax.index')
        </div>
    </div>
    @include('myforms.categorias.partials.modals.create')

@stop
@push('scripts')
    <script type="module" src={{ asset('js/admin_categorias.js') }}></script>
@endpush
