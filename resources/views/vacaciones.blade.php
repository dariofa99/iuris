@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                  Suspensión del servicio
                </div>

                <div class="card-body" id="content_solicitud_espera">

                  <div style="padding: 30px; font-size: 20px; text-align: center;">Debido a las disposiciones de la Universidad no se contará con el servicio de atención virtual en las fechas desde el 12 de Julio de 2021 hasta 17 de agosto de 2021.
                       <br> Agradecemos su comprensión.
                  </div>


                </div>
        </div> 
    </div>

 
@endsection

@push('scripts')

<!-- jQuery 2.2.3 -->
<script src="{{asset('plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
{{-- <!-- Bootstrap 3.3.6 -->
<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
 --}}<!-- iCheck -->


@endpush