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
                            <label>
                                Estimada persona usuaria, para el Consultorio Jurídico es muy importante su opinión sobre el
                                acceso y la atención brindada para satisfacer su necesidad jurídica. Por ello, a
                                continuación encontrará algunos criterios que nos ayudaran a establecer la evaluación y
                                mejora continua del servicio. Recuerde que su participación es voluntaria y muy valiosa.
                                <br>
                                Por favor marque de la manera más objetiva posible la respuesta que mejor represente su
                                opinión.



                            </label>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <label>
                                    <br>
                                    Persona usuaria: {{ auth()->user()->name }} {{ auth()->user()->lastname }}<br>
                                    Correo electrónico: {{ auth()->user()->email }}<br>
                                    Fecha: {{ date('Y-m-d') }}<br>
                                </label>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <form id="myEvaNivSatForm">
                                    <input type="hidden" name="encuesta_id" value="{{ $encuesta->id }}">
                                    <div class="row" id="renderQuestion">
                                        @include('myforms.categorias.refs_aditional_data', [
                                            'data' => getReferencesDataBySection(
                                                'personalizado',
                                                'exp_encuesta_satisf'),
                                            'col' => 12,
                                            'model' => $encuesta,
                                            'design' => 'card_question',
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
