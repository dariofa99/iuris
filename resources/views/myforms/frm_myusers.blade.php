@extends('layouts.dashboard')
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

	<div class="col-md-10 col-md-offset-1" id="content_user_gen_form">
		@include('myforms.frm_myusers_gen_form')
	</div>

</div>

@stop
@push('scripts')
	<script type="module"   src={{asset("js/admin_users.js")}}></script>
@endpush
