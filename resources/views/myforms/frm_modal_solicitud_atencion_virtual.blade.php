@component('components.b4.modal_extra_large')
    @slot('trigger')
        mymodalSolicitudAtencionVirtual
    @endslot
    @slot('size')
        modal-dialog modal-lg
    @endslot

    @slot('title')
        <h3>Atención Virtual</h3>
    @endslot
    @push('styles')
        <!-- aqui van los estilos de cada vista -->
        <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
        <style>

        </style>
    @endpush

    @slot('body')
        @section('msg-contenido')
            Registrado
        @endsection
        @include('msg.ajax.success')
        <div>
            <div class="container">
                <div class="row justify-content-center">
                  {{--   <div class="col-md-5">
                        <div class="card">
                            <h4 class="card-header bg-white" style="text-align: center">
                                Consultorios Jurídicos
                            </h4>
                            <div class="card-body">
                                 <div class="card-login-body">
                                    <img src="{{ asset('dist/img/online-justice.png') }}" alt=""><br>
                                    Solicite asesoría jurídica de manera virtual.
                                </div>

                                <div class="card-footer bg-white"
                                    style="text-align: center;border-top:1px solid rgb(235, 235, 235)">
                                    <a href="/solicitudes/expedientes/recepcion/?paso=1" class="btn btn-warning">
                                        CONTINUAR
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div> --}}
                    <div class="col-md-5">
                        <div class="card">
                            <h4 class="card-header bg-white" style="text-align: center">
                                Centro de conciliación
                            </h4>
                            <div class="card-body">
                                <div class="card-login-body">
                                    <img src="{{ asset('dist/img/collaboration.png') }}" alt=""><br>
                                    Solicite y gestione de manera eficiente, rápida y segura un proceso conciliatorio.
                                    <h3>En periodo de pruebas, solicite la atención de manera presencial.</h3>

                                </div>

                                <div class="card-footer bg-white"
                                    style="text-align: center;border-top:1px solid rgb(235, 235, 235)">
                                    <a href="#" class="btn btn-warning" id="btn_solicitar_conciliacion">
                                        CONTINUAR (En periodo de pruebas)
                                    </a>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>


                <div class="row justify-content-center">
                   <div class="col-md-5">
                        <div class="card">
                            <h4 class="card-header bg-white" style="text-align: center">
                                Encuestas de satisfacción - Consultorios Jurídicos
                            </h4>
                            <div class="card-body d-flex justify-content-center">
                                <p style="text-align: center; font-size: 16px;">
                                    Escanea el código QR para responder la encuesta de satisfacción del servicio
                                    recibido.
                                </p>
                              <div id="qr-code-exp"></div>

                            </div>

                        </div>
                    </div> 
                    <div class="col-md-5">
                        <div class="card ">
                            <h4 class="card-header bg-white" style="text-align: center">
                                Encuestas de satisfacción - Centro de conciliación
                            </h4>
                            <div class="card-body d-flex justify-content-center">
                                <p style="text-align: center; font-size: 16px;">
                                    Escanea el código QR para responder la encuesta de satisfacción del servicio
                                    recibido.
                                </p>
                                <div id="qr-code-conciliacion"></div>
                            </div>

                        </div>
                    </div>
                </div>







                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <a target="_blank" style="border-bottom:1px solid gray;color: black;font-size:14px" href="/videos">
                            Ver videos de ayuda
                        </a>
                    </div>
                </div>
            </div>


        </div>
    @endslot

    @slot('footer')
        <div id="contentNotButtonDis">

        </div>
    @endslot
@endcomponent
<!-- /modal -->
