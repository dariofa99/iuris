@if (!$readonly and 
$expediente->getDocenteAsig()->idnumber == currentUser()->idnumber
or (currentUser()->hasRole("amatai") or currentUser()->hasRole("diradmin")
or currentUser()->hasRole("dirgral"))
)
    <div class="row">
        <div class="col-md-2">
            <input type="button" id="btn_nueva_cita" value="Nueva cita" class="btn-block btn btn-primary btn-sm">
        </div>
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <table class="table" id="table_list_citaciones">
            <thead>
                <th width="20%">Docente</th>
                <th>Motivo</th>
                <th width="20%">Fecha</th>
                <th width="10%">Hora</th>
                <th width="10%"> Acciones </th>
            </thead>
            <tbody>
                @foreach ($expediente->getCitas() as $cita)
                    <tr>
                        <td>{{ $cita->docente_fullname }}</td>
                        <td>{{ $cita->motivo }}</td>
                        <td>{{ $cita->fecha }}</td>
                        <td>{{ $cita->hora }}</td>
                        <td>
                          @if(!$readonly and currentUser()->idnumber == $cita->user_created_id)
                          <button id="{{ $cita->id }}" type="button" class="btn-sm btn btn-warning btn_edit_citacion">
                            Cambiar
                        </button>
                          @endif
                         
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
