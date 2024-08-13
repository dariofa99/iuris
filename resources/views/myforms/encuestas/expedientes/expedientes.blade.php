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
                                Se encontraron los siguientes procesos.
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
                                @foreach ($user->casosRevision as $key => $casoRevision)
                                @if(!$casoRevision->encuesta)
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{$casoRevision->expid}}</h3>
                                            <p>{{$user->roles[0]->name?:"Sin rol"}}</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-chart-pie"></i>
                                        </div>
                                        <a data-expediente="{{$casoRevision->id}}"  href="{{url('expediente/evaluar/encuesta?page=0')}}" class="small-box-footer btn_start_test">
                                            Realizar encuesta
                                             <i class="fas fa-arrow-circle-right">

                                             </i>
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
    <script type="module" src={{ asset('js/admin_encuestas_exp.js') }}></script>
@endpush
