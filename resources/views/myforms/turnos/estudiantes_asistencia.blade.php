

<div class="asistencia-container">
    <div class="container-fluid">
        {{-- Formulario de búsqueda moderno --}}
        <div class="search-form-modern">
            <h5>
                <i class="fas fa-search"></i>
                Buscar Estudiante
            </h5>
            <form id="myFormBuscarEstudiante">
                <div class="search-input-modern">
                    <i class="fas fa-user search-icon"></i>
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

        {{-- Tabla moderna --}}
        <div class="table-responsive-modern table-responsive no-padding">
            
            <table class="table table-modern" id="tableEstAsistencia">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Curso</th>
                        <th>Asistencias</th>
                        <th>Faltas</th>
                        <th>Reposiciones</th>
                        <th>Nota</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Las filas se cargarán dinámicamente aquí -->
                </tbody>
            </table>
        </div>
    </div>
</div>


