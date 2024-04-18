@component('components.b4.modal_medium')




    @slot('trigger')
        myModal_form_evnivelsatisfaccion
    @endslot

    @slot('title')
        <h4>
            Satisfacción - PARTES
        </h4>
    @endslot


    @slot('body')
        @section('msg-contenido')
            Registrado
        @endsection
        @include('msg.ajax.success')
        <input type="hidden" id="tipo_usuario_id" name="tipo_usuario_id">
        <input type="hidden" id="section" name="section">
        <div id="evsatisfconciliacion_form">
            <div class="row">
                <div class="col-md-12">
                    <p>
                        Estimada persona usuaria, para el Centro de Conciliación "Eduardo Alvarado Hurtado" es muy importante su
                        opinión sobre el acceso y la atención brindados. Por ello, en este documento podrá encontrar algunos
                        criterios que nos ayudarán a establecer la evaluación y mejora continua del servicio. Recuerde que su
                        participación es voluntaria y muy valiosa.
                    </p>
                </div>
                <div class="col-md-6">
                    Nombres: {{ auth()->user()->name }} {{ auth()->user()->lastname }}
                </div>
                <div class="col-md-6">
                    Fecha de registro : {{ getSmallDateWithHour(date('Y-m-d')) }}
                </div>
            </div>
            <hr>
            <form id="myEvaNivSatForm">
                <div class="row">
                    @include('myforms.conciliaciones.componentes.nivel_satisfaccion_form')

                    @if (!currentUser()->hasRole('visitante_conciliacion'))
                        <div class="col-md-12">
                            <input type="submit" id="btn_llenarForm" value="Enviar encuesta" class="btn btn-primary btn-block">
                        </div>
                    @endif
                </div>
            </form>
        </div>
    @endslot
@endcomponent
<!-- /modal -->
