<div class="row">
    <div class="col-md-3">
        <button type="button" id="btn_create_document_" data-category="233"
            class="mb-2 btn btn-primary btn-sm  btn_create_document">
            Agregar documento
        </button>
    </div>
    <div class="col-md-12">
        <table class="table">
            <thead>
                <th>
                    Concepto
                </th>
                <th>
                    Categoría
                </th>
                <th>
                    Creado por
                </th>
                <th>
                    Archivo
                </th>
                <th>
                    Acciones
                </th>
            </thead>
            <tbody>
                @foreach ($conciliacion->files as $key => $file)
                    <tr>
                        <td>

                            {{ $file->pivot->concepto }}
                        </td>
                        <td>
                            {{ $file->categoryInConciliacion()->orderBy('created_at', 'desc')->first()->ref_nombre }}
                        </td>
                        <td>
                            {{ $file->userinconciliacion()->orderBy('created_at', 'desc')->first()->name }}
                            {{ $file->userinconciliacion()->orderBy('created_at', 'desc')->first()->lastname }}
                            <small>
                                <i>
                                    ({{ $file->userinconciliacion()->orderBy('created_at', 'desc')->first()->role()->first()->display_name }})
                                </i>
                            </small>
                        </td>
                        <td>
                            <small>
                                <a target="_blank" href="/conciliaciones/download/file/{{ $file->id }}">
                                    {{ $file->original_name }}
                                </a>
                            </small>
                        </td>
                        <td>
                            <button class="btn btn-warning">
                                Compartir
                            </button>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
