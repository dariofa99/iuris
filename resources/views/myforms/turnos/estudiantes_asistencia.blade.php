

<div class="asistencia-container iuris-turnos-page">
    <div class="container-fluid">
        <div class="iuris-schedule-toolbar iuris-attendance-toolbar">
            <form id="myFormBuscarEstudiante" class="iuris-schedule-filter iuris-attendance-filter">
                <div class="iuris-schedule-filter-icon">
                    <i class="fas fa-search" aria-hidden="true"></i>
                </div>
                <div class="iuris-schedule-filter-content">
                    <label for="select_data_users">Buscar estudiante</label>
                    {!! Form::text('data', null, [
                        'class' => 'form-control select_data_users',
                        'required' => 'required',
                        'id' => 'select_data_users',
                        'data-width' => '100%',
                        'title' => 'Ingrese el nombre de un estudiante',
                        'placeholder' => 'Ingrese nombre o cédula del estudiante',
                    ]) !!}
                </div>
            </form>
        </div>

        <section class="iuris-schedule-editor" aria-label="Asistencia de estudiantes">
            <div class="iuris-schedule-editor-heading">
                <div>
                    <span class="iuris-schedule-kicker">ASISTENCIA ESTUDIANTIL</span>
                    <h3>Registro de asistencias</h3>
                </div>
            </div>

            <div class="table-responsive iuris-schedule-table-wrap">
                <table class="table iuris-schedule-editor-table iuris-attendance-table" id="tableEstAsistencia">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Cédula</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Curso</th>
                            <th scope="col">Asistencias</th>
                            <th scope="col">Faltas</th>
                            <th scope="col">Reposiciones</th>
                            <th scope="col">Nota</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Las filas se cargarán dinámicamente aquí -->
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>


