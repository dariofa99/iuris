@component('components.b4.modal_large')
    @slot('trigger')
        mymodal
    @endslot


    @slot('title')
        Detalles del caso
    @endslot


    @slot('body')
        {!! Form::open(['id' => 'myFormCalendar', 'url' => '/horarios', 'method' => 'post']) !!}
        <div class="row">
            <div class="col-md-12">
                <table id="tbl_turnos_list" class="table table-bordered table-striped dataTable" role="grid">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nombre</th>
                            <th>Curso</th>
                            @if (currentUser()->hasRole('coordprac') or
                                    currentUser()->hasRole('diradmin') or
                                    currentUser()->hasRole('dirgral') or
                                    currentUser()->hasRole('amatai'))
                                <th>Asistencia</th>
                                <th>Lugar</th>
                                <th>Motivo</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="contencalendarid">
                    </tbody>
                </table>
            </div>
        </div>
        <input type="hidden" id="fechaestasis" name="fechaestasis" value="">
        {!! Form::close() !!}
    @endslot
    @slot('footer')
        @if (currentUser()->hasRole('coordprac') or
                currentUser()->hasRole('diradmin') or
                currentUser()->hasRole('dirgral') or
                currentUser()->hasRole('amatai'))
            <button type="button" class="btn btn-success" id="addest">Añadir estudiante</button>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        @endif
    @endslot
@endcomponent
<!-- /modal -->
