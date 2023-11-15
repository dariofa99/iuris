<form id="myFormBuscarEstudiante">
    <div class="row">
        <div class="col-md-5">
            {!! Form::text('data', null, [
                'class' => 'form-control form-control-sm select_data_users',
                'required' => 'required',
                'id' => 'select_data_users',
                'data-width' => '100%',
                'title' => 'Ingrese el nombre de un estudiante',
                'placeholder' => 'Buscar por nombre de estudiante',
            ]) !!}

        </div>
        {{-- <div class="col-md-1">
        <button type="submit" class="btn btn-success btn-sm">Buscar</button>
    </div> --}}
    </div>
</form>
<div class="row">
    <div class="col-md-12">
       @include('layouts.loaderindiv')
        <table class="table" id="tableEstAsistencia">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Curso</th>
                    <th>Asistencias</th>
                    <th>Faltas</th>
                    <th>Reposiciones</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
