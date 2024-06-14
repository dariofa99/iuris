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
<div class="row">
    <div class="col-md-12 table-responsive no-padding"  id="content_categories_list">
        @include('myforms.categorias.partials.ajax.index')
    </div>
</div>
@include('myforms.categorias.partials.modals.create')

@stop
@push('scripts')
<script type="module" src={{ asset('js/admin_categorias.js') }}></script>
  
@endpush