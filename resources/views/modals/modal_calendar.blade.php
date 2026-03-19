@component('components.b4.modal_large')
    @slot('trigger')
        mymodal
    @endslot


    @slot('title')
        <h3>Horarios estudiante</h3>
    @endslot



    @slot('body')
        {!! Form::open(['id' => 'myFormCalendar', 'url' => '/horarios', 'method' => 'post']) !!}


        <div class="row">
            <div class="col-md-12 table-responsive">
                <table style="min-width: 990px !important" id="tbl_turnos_list" class="table" role="grid">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nombre</th>
                            <th>Curso</th>
                            @if (currentUser()->hasRole('coordprac') or
                                    currentUser()->hasRole('diradmin') or
                                    currentUser()->hasRole('dirgral') or
                                    currentUser()->hasRole('amatai') or
                                    currentUser()->can('admin_turnos_estudiantes'))
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

        </div>
        <input type="hidden" id="fechaestasis" name="fechaestasis" value="">

        <div class="modal-footer">
            @if (currentUser()->hasRole('coordprac') or
                    currentUser()->hasRole('diradmin') or
                    currentUser()->hasRole('dirgral') or
                    currentUser()->hasRole('amatai') or
                     currentUser()->can('admin_turnos_estudiantes'))
                <button type="button" class="btn btn-success" id="addest">Añadir estudiante</button>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            @endif

            {!! Form::close() !!}
        @endslot
    @endcomponent
    <!-- /modal -->

    @component('components.b4.modal_medium')
        @slot('trigger')
            mymodaldoc
        @endslot


        @slot('title')
            <h3>Horarios docente</h3>
        @endslot

 

        @slot('body')
        <div class="body" id="turnosdoc">


            <div class="modal-footer" id="fotermodaldoc">
                <button type="submit" class="btn btn-primary">Guardar cambios</button>


            </div>
        </div>
        @endslot
    @endcomponent
    <!-- /modal -->
