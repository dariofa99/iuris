@extends('layouts.dashboard')
@section('titulo_area')
   <strong>
Nuevo usuario    
</strong>
@endsection
@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
    <style>

    </style>
@endpush
@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

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

    <div class="row">

        <div class="col-md-12 align-items-center" id="content_user_gen_form">
            @include('myforms.frm_myusers_gen_form')
        </div>

    </div>

@stop
@push('scripts')
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>
    <script type="module"   src={{asset("js/admin_users.js")}}></script>
@endpush
