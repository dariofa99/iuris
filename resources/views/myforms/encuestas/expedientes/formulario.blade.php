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
                                Formulario de registro de encuesta de satisfacción<br>
                                Fecha: {{date('Y-m-d')}}<br>
                                Número de radicado:


                            </h3>
                        </div>

                    </div>
                    <div class="card-body">

                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <form id="myEvaNivSatForm">
                                    <input type="hidden" name="encuesta_id" value="{{$encuesta->id}}">
                                    <div class="row" id="renderQuestion">
                                        @include('myforms.categorias.refs_aditional_data', [
                                            'data' => getReferencesDataBySection(
                                                'personalizado',
                                                'exp_encuesta_satisf'),
                                            'col' => 12,
                                            'model' => $encuesta,
                                            'design'=>'card_question'
                                                                                                                                  
                                        ])
                                        @if (!currentUser()->hasRole('visitante_conciliacion'))
                                     
                                            <div class="col-md-12 mt-3">
                                                <input type="submit" id="btn_llenarForm" value="Enviar encuesta"
                                                    class="btn btn-primary btn-block">
                                            </div>
                                        @endif
                                    </div>
                                </form>
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
