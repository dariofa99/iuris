@extends('layouts.app')

@section('content')

@if (config('app.name') == 'ConciliApp')
@include('myforms.login_conciliapp')
@else
@include('myforms.login_iuris')
@endif

@endsection

