@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/plugins/dropzone59/dropzone.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="card">
                    <div class="card-header">
                        <div class="content_message">
                            <h3>
                                Se encontraron las siguientes solicitudes
                            </h3>
                        </div>

                    </div>
                    <div class="card-body">

                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                Datos del Usuario<br>

                                Nombre: {{ $user->name }} {{ $user->lastname }}
                            </div>
                            <div class="col-md-8">
                                @foreach ($user->conciliaciones as $key => $conciliacion)
                                @if(!$conciliacion->encuesta)
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{$conciliacion->num_conciliacion}}</h3>
                                            <p>{{$user->tipo_conciliacion()->where('conciliacion_id',$conciliacion->id)->first()->ref_nombre}}</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-chart-pie"></i>
                                        </div>
                                        <a data-conciliacion="{{$conciliacion->id}}" data-usertype="{{$conciliacion->pivot->tipo_usuario_id}}" href="{{url('conciliacion/evaluar/encuesta?page=0')}}" class="small-box-footer btn_start_test">
                                            Realizar encuesta <i class="fas fa-arrow-circle-right"></i>
                                        </a>
                                    </div>
                                    @endif
                                @endforeach




                            </div>
                        </div>


                    </div>
                    <div class="card-footer">
                        <div class="row">

                            <div class="col-md-12">



                                <a href="/login" class="btn btn-default">
                                    Cancelar
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="module" src={{ asset('js/admin_encuestas.js') }}></script>
@endpush
