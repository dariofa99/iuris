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
                        <div class="row justify-content-center ">
                            
                            <div class="col-md-8 d-none d-md-block">
                                <table style="font-family:arial;font-size:13.333px;width:100%">
                                    <tr>
                                        <td align="center" style="border:1px solid black">
                                            <img src="{{ asset('/img/logoudenar_2.png') }}" width="100"
                                                height="100" />
                                        </td>

                                        </td>
                                        <td align="center" style="border:1px solid black;font-style:bold">
                                            UNIVERSIDAD DE NARIÑO<br>
                                            CONSULTORIOS JURIDICOS - CENTRO DE CONCILIACIÓN <br>
                                            “EDUARDO ALVARADO HURTADO”<br>
                                            Facultad de Derecho y Ciencias Políticas<br>
                                            <br>
                                            ENCUESTA DE SATISFACCIÓN DE USUARIOS <br>
                                            CONSULTORIOS JURÍDICOS

                                        </td>
                                        <td style="border:1px solid black">
                                            <span
                                                style="padding:2px;display:block;text-align:left;border-bottom:1px solid black">
                                                Código:
                                                {{$encuesta->encuesta->codigo}}</span>
                                            <span
                                                style="padding:2px;display:block;text-align:left;border-bottom:1px solid black">
                                                Página:
                                                1 de 1</span>
                                            <span
                                                style="padding:2px;display:block;text-align:left;border-bottom:1px solid black">
                                                Versión:
                                                {{$encuesta->encuesta->version}}
                                            </span>
                                            <span
                                                style="padding:2px;display:block;text-align:left;border-bottom:1px solid black">Vigente
                                                a Partir de:<br>
                                                {{ date($encuesta->encuesta->fecha_vigencia) }}
                                            </span>

                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                         
                            <div class="col-md-8">
                                <form id="myEvaNivSatForm">
                                    <input type="hidden" name="expencuesta_id" value="{{ $encuesta->id }}">
                                    <div class="row" id="renderQuestion">
                                        @include('myforms.categorias.refs_aditional_data', [
                                            'data' => $encuesta->encuesta->preguntas,
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
