@component('components.b4.modal_extra_large')
    @slot('trigger')
        myModal_registrar_horas_pendientes_docente
    @endslot

    @slot('title')
        <span id="titulo_modal"></span>
    @endslot

    @slot('footer')
    @endslot

    @slot('body')
        @include('msg.ajax.success')

        <div class="iuris-turnos-page iuris-pending-hours">
            <div class="row">
                <div class="col-md-12">
                    <section class="iuris-pending-teacher" aria-label="Docente seleccionado">
                        <div class="iuris-docente-avatar">
                            <img src="{{ asset('thumbnails/default.jpg') }}" alt="" id="avatar_docente_pendiente">
                        </div>
                        <div class="iuris-pending-teacher-info">
                            <span class="iuris-turnos-eyebrow">DOCENTE</span>
                            <h2 class="iuris-turnos-page-header-title" id="nombre_docente_pendiente">
                                Andrés Alfonso Benítez Caicedo
                            </h2>
                            <p>Consulta sus horas pendientes y reposiciones registradas.</p>
                        </div>
                    </section>
                </div>
            </div>

            <section class="card card-tabs iuris-turnos-panel" aria-label="Horas pendientes del docente">
                <div class="card-header iuris-turnos-tabs-header">
                    <ul class="nav nav-tabs iuris-turnos-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="horas-asistencia-tab" href="#horas-asistencia"
                                data-toggle="tab" role="tab" aria-controls="horas-asistencia" aria-selected="true">
                                <i class="far fa-clock" aria-hidden="true"></i>
                                Registro de horas pendientes
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="asistencias-reponer-tab" data-toggle="tab"
                                href="#asistencias-reponer" role="tab" aria-controls="asistencias-reponer"
                                aria-selected="false">
                                <i class="fas fa-calendar-check" aria-hidden="true"></i>
                                Turnos registrados para reposición
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body iuris-turnos-panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="horas-asistencia" role="tabpanel"
                            aria-labelledby="horas-asistencia-tab">
                            @include('myforms.turnos.horas_pendientes_component')
                        </div>

                        <div class="tab-pane fade" id="asistencias-reponer" role="tabpanel"
                            aria-labelledby="asistencias-reponer-tab">
                            @include('myforms.turnos.reposiciones_component')
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endslot
@endcomponent
<!-- /modal -->
