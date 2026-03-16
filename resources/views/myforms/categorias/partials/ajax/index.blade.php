<table class="table" style="table-layout: fixed; word-wrap: break-word;">
    <thead>
        <th width="35%">
            Nombre
        </th>
        <th width="35%">
            Nombre en BD
        </th>
        <th width="20%">
            Uso en
        </th>

    </thead>
    <tbody>
        @foreach ($categories as $category)
            <tr>
                <td>
                    {{ $category->name }}
                </td>
                <td style="word-wrap: break-word; overflow-wrap: break-word;">
                    {{ $category->short_name }}
                </td>
                <td>
                    {{ $category->getCategory() }}
                </td>
                <td>
                    <button class="btn btn-primary btn-sm btn_edit_category btn-block" data-id="{{ $category->id }}">
                        Editar
                    </button>
                    <button class="btn btn-danger btn-sm btn_delete_category btn-block" data-id="{{ $category->id }}">
                        Eliminar
                    </button>


                </td>
            </tr>
        @endforeach


    </tbody>
</table>

{{ $categories->appends(request()->query())->links() }}
