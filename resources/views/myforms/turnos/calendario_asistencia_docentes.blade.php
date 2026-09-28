    <div class="row">
        <div class="col-md-6">

            <div class="iuris-horario-filters">

                <!-- Tipo de horario -->
         {{--       <div class="iuris-horario-filter">

                    <div class="iuris-horario-filter-icon">
                        <i class="far fa-calendar-alt"></i>
                    </div>

                     <div class="iuris-horario-filter-content">
                        <label for="horariourl">
                            Tipo de horario
                        </label>

                        <select class="form-control" id="horariourl">
                            <option value="estudiantes" @if ($tipo == 'estudiantes') selected @endif>
                                Horario estudiantes
                            </option>

                            <option value="docentes" @if ($tipo == 'docentes') selected @endif>
                                Horario docentes
                            </option>
                        </select>
                    </div> 

                </div>
--}}

                <!-- Docente -->
                <div class="iuris-horario-filter iuris-docente-filter">

                    <div class="iuris-horario-filter-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>

                    <div class="iuris-horario-filter-content">
                        <label for="docente_id">
                            Docente
                        </label>

                        <select class="form-control" name="docente_id" id="docente_id">
                            <option value="">
                                Seleccione un docente
                            </option>

                            @foreach ($docentes as $docente)
                                <option value="{{ $docente->idnumber }}"
                                    @if ($docente->idnumber == request()->get('docente_id')) selected @endif>
                                    {{ $docente->full_name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                </div>

            </div>

        </div>
    </div>
    <div class="row">

        <!-- /.col -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body no-padding" style="margin: 5px 5px 5px 5px;">
                    <!-- THE CALENDAR -->
                    <div id="calendar"></div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <div id="calendaridlist"></div>

    <input type="hidden" id="idestlistcal" value="">




    @include('myforms.turnos.frm_modal_registrar_turno_docente')
