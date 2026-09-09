@component('components.b4.modal_large')
    @slot('trigger')
        myModal_reporasistencia
    @endslot

    @slot('title')
        
    @endslot


    @slot('body')
        <form class="iuris-form-card" id="turnosdoc">

            <!-- ============================================
                     HEADER
                ============================================= -->

            <div class="iuris-form-header">

                <div class="iuris-form-title">

                    <div class="iuris-form-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>

                    <div>
                        <h5>Registro de turno docente</h5>
                        <small>Registro de asistencia y novedades</small>
                    </div>

                </div>

            </div>


            <!-- ============================================
                     BODY
                ============================================= -->

            <div class="iuris-form-body">

                <!-- DOCENTE -->

                <div class="iuris-turno-docente">

                    <div class="iuris-docente-avatar">
                        <img src="/img.jpg" alt="Doris Arteaga de Maya" id="avatar_docente" class="iuris-avatar">
                    </div>

                    <div class="iuris-turno-docente-info">

                        <span class="iuris-label-form">
                            Docente
                        </span>

                        <div class="iuris-turno-docente-name" id="nombre_docente">
                            Doris Arteaga de Maya
                        </div>

                    </div>

                </div>


                <div class="iuris-divider"></div>


                <!-- HORARIO -->

                <div class="iuris-section-title">

                    <i class="far fa-clock"></i>

                    <span>Horario del turno</span>

                </div>


                <div class="row">

                    <!-- HORA INICIO -->

                    <div class="col-md-6">

                        <div class="form-group">

                            <label class="iuris-form-label">
                                Hora inicio
                            </label>

                            <div class="iuris-input-icon">

                                <i class="far fa-clock"></i>

                                <input type="time" class="form-control" id="hora_inicio" value="10:30">

                            </div>

                        </div>

                    </div>


                    <!-- HORA FIN -->

                    <div class="col-md-6">

                        <div class="form-group">

                            <label class="iuris-form-label">
                                Hora fin
                            </label>

                            <div class="iuris-input-icon">

                                <i class="far fa-clock"></i>

                                <input type="time" class="form-control" id="hora_fin" value="12:00">

                            </div>

                        </div>

                    </div>

                </div>


                <div class="iuris-divider"></div>


                <!-- NOVEDAD -->

                <div class="iuris-section-title">

                    <i class="fas fa-clipboard-list"></i>

                    <span>Novedad del turno</span>

                </div>


                <div class="iuris-novedades">


                    <!-- ASISTENCIA -->

                    <label class="iuris-option">

                        <input type="radio" name="regisdocasis" id="regisdocasis1" value="149" checked>

                        <span class="iuris-option-content">

                            <span class="iuris-option-icon">
                                <i class="fas fa-user-check"></i>
                            </span>

                            <span class="iuris-option-text">

                                <strong>Registrar asistencia</strong>

                                <small>
                                    El docente cumplió con su turno.
                                </small>

                            </span>

                        </span>

                    </label>


                    <!-- PERMISO -->

                    <label class="iuris-option">

                        <input type="radio" name="regisdocasis" id="regisdocasis2" value="150">

                        <span class="iuris-option-content">

                            <span class="iuris-option-icon permiso">
                                <i class="fas fa-file-alt"></i>
                            </span>

                            <span class="iuris-option-text">

                                <strong>Registrar permiso</strong>

                                <small>
                                    Registrar una novedad o permiso.
                                </small>

                            </span>

                        </span>

                    </label>


                </div>


                <!-- ANOTACIÓN -->

                <div class="form-group iuris-anotacion">

                    <label class="iuris-form-label">
                        Anotación
                    </label>

                    <textarea class="form-control" name="descripregisdocasis" id="descripregisdocasis" rows="3"
                        placeholder="Ingrese una anotación relacionada con el turno..."></textarea>

                    <small class="iuris-help-text">
                        <i class="far fa-info-circle"></i>
                        La anotación es opcional.
                    </small>

                </div>

            </div>


            <!-- ============================================
                     FOOTER
                ============================================= -->

            <div class="iuris-form-footer">

                <div class="iuris-required">

                    <i class="fas fa-info-circle"></i>

                    Verifique la información antes de guardar.

                </div>


                <button type="button" id="btnasisenciadocmodal" class="btn btn-iuris-primary">

                    <i class="fas fa-save mr-1"></i>

                    Guardar cambios

                </button>

            </div>


        </form>
    @endslot
@endcomponent
<!-- /modal -->
