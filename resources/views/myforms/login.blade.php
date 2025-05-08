@extends('layouts.app')

@section('content')

@if (config('app.name') == 'ConciliApp')
@include('myforms.login_conciliapp')
@else
@include('myforms.login_iuris')
@include('myforms.frm_modal_detalles_login_conciliacion')
@endif

@endsection

@push('scripts')

@if (config('app.name') != 'ConciliApp')
<script type="module" src={{ asset('js/admin_login.js?v='. config('app_config.asset_version')) }}></script>
@endif   
@endpush
