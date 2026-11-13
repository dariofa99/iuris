@extends('layouts.app')

@section('content')
<div class="container">
    @include('myforms.recepcion.terminos_conciliacion_message')
</div>



@endsection

@push('scripts')

<!-- FontAwesome para los íconos -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
@if (config('app.name') != 'ConciliApp')
<script type="module" src={{ asset('js/admin_login.js?v='. config('app_config.asset_version')) }}></script>
@endif   

@endpush