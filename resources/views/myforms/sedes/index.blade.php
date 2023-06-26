@extends('layouts.dashboard')

@section('titulo_general')
Sedes

@endsection

@section('titulo_area')
<button class="btn btn-primary" id="btn_new_sede">Nueva sede</button>
@endsection

@section('area_buttons')

@endsection
 

@section('area_forms') 
@include('msg.success') 

<div class="row">
    <div class="col-md-12" id="content_table_sedes_list">
        @include('myforms.sedes.partials.ajax.index')
    </div>
</div>

@include('myforms.sedes.partials.modals.create_sede')
@stop
@push('scripts')

<script src="{{asset('js/sedes.js')}}"></script>
@endpush