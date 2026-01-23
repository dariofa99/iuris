@extends('layouts.dashboard')
@push('styles')
    <!-- aqui van los estilos de cada vista -->


@endpush
@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('titulo_area')
    <div class="row">
        <div class="col-md-10">              
            
        </div>      
    </div>


@endsection
@section('area_buttons')
 
@endsection

@section('area_forms')

    @include('msg.success')

    <div class="row">
        <div class="col-md-12">

            Hello world
        </div>
    </div>



@stop

@push('scripts')
@endpush
