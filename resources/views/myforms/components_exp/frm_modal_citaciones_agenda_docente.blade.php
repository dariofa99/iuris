@component('components.b4.modal_medium')
    @slot('trigger')
        mymodalAgendaCitacionWithDocente
    @endslot

    @slot('title')
        Turnos docente - Detalle de la cita
    @endslot


    @slot('body')
        <div class="row justify-content-center">
            <div class="col-md-12" id="ct_forcitaest">
                <div class="card-body">
                    <form id="myFormAgendarTurnoDocente">
                        @csrf


                        <input type="hidden" id="turnoFecha" name="fecha">
                        <input type="hidden" id="turnoHoraInicio" name="hora_inicio">
                        <input type="hidden" id="turnoHoraFin" name="hora_fin">
                        <input type="hidden" id="turnoDocenteId" name="docente_id">

                        <label for="">Docente: <span id="docente_nombre">Juan Pérez - EXP001</span></label>
                        <!-- Título del evento -->
                        <h5 class="event-title mb-3" id="title">Juan Pérez - EXP001</h5>

                        <label>Motivo</label>

                            <textarea class="form-control form-control-sm" name="motivo" id="motivo" cols="3" rows="10" placeholder="Motivo: Solicitar cierre de caso 2023A-2541 y revisión de derecho de petición de expediente 2025A-302, 2025A-303"></textarea>

                        <label>Fecha</label>
                        <p class="event-date" id="fecha_larga">Fecha: 30 de noviembre de 2024, 10:00 AM</p>


                        <div class="alert alert-info" id="info_adicional_turno" role="alert">
                            <i class="fas fa-info-circle"></i>
                            Tenga en cuenta que este turno ha sido asignado de manera adicional.
                            La atención estará sujeta a confirmación y disponibilidad.
                            Por favor, manténgase atento a la notificación de confirmación por correo electrónico. En caso de
                            ser aceptado, acérquese unos minutos antes del
                            horario programado.

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="text-end" id="ct_forcitaest_btn">

                                </div>
                            </div>
                        </div>


                        <!-- Botón opcional -->
                    </form>
                </div>
            </div>
        </div>
    @endslot
@endcomponent
<!-- /modal -->
