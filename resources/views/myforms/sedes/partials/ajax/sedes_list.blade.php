@foreach($sedes as $key => $sede)
<tr id="row_sede-{{$sede->id}}">
    <td>
        {{$sede->nombre}}
    </td>
    <td>
        {{$sede->ubicacion}}
    </td>
    <td>
        <button class="btn btn-primary btn_edit_sede" data-id="{{$sede->id}}">
            Editar
        </button>

        <button class="btn btn-danger btn_delete_sede" data-id="{{$sede->id}}">
            Eliminar
        </button>
    </td>
</tr>
@endforeach