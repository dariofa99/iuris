@component('components.b4.modal_large')
    @slot('trigger')
        myModal_registrar_horas_pendientes_docente
    @endslot

    @slot('title')
        <label id="titulo_modal"></label>
    @endslot

    @slot('footer')
    @endslot
    @slot('body')
        @include('msg.ajax.success')



        <div id="content-data" class="row">
            <div class="col-md-12">
                <div class="iuris-turno-docente mb-3">
                    <div class="iuris-docente-avatar">
                        <img src="http://iuris.amatai.local/thumbnails/default.jpg" alt="Andrés Alfonso Benítez Caicedo"
                            id="avatar_docente_pendiente" class="iuris-avatar">
                    </div>

                    <div class="iuris-turno-docente-info">
                        <span class="iuris-label-form">Docente</span>
                        <div class="iuris-turno-docente-name" id="nombre_docente_pendiente">
                            Andrés Alfonso Benítez Caicedo
                        </div>
                    </div>
                </div>

                <div class="iuris-form-card">
                    <div class="iuris-form-header">
                        <div class="iuris-form-title">
                            <span class="iuris-form-icon">
                                <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                            </span>
                            <div>
                                <h5>Horas por reponer</h5>
                                <small>Listado de minutos (horas) pendientes de reposición</small>
                            </div>
                        </div>


                    </div>

                    <div class="iuris-table-wrapper">
                        <table class="table iuris-detail-table">
                            <thead>
                                <tr>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Hora inicio</th>
                                    <th scope="col">Hora fin</th>
                                    <th scope="col">Minutos</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-turnos-body"></tbody>
                        </table>
                    </div>
                    <hr>
                    <form id="form_reposicion_horas_docente">

                        <input type="hidden" name="idnumber_docente" id="idnumber_docente">
                       


                         <div id="conten-inputs">

                         </div>

                     

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button class="btn btn-sm btn-iuris-primary" type="submit" id="btn_reponer_horas_docente">
                                    Registrar reposición
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    @endslot
@endcomponent
<!-- /modal -->
