@extends('layouts.dashboard')


@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('titulo_area')
    Nuevo documento general
@endsection


@section('area_forms')

    @include('msg.success')
    <div class="row">
        <div class="col-md-12">
            {!! Form::open(['route' => 'bibliotecas.index', 'method' => 'GET', 'id' => 'myformSearchBiblioteca']) !!}
            <div class="box-body table-responsive no-padding">
                <table class="table">
                    <tr>
                        <td>
                            Seleccione la rama del derecho
                        </td>
                        <td>
                            Seleccione la categoria
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::select('bibliid_ramaderecho', $rama_derecho, null, [
                                'id' => 'bibliid_ramaderecho',
                                'class' => 'form-control required',
                                'placeholder' => 'Seleccione...',
                            ]) !!}
                        </td>
                        <td>
                            {!! Form::select('bibliid_tipoarchivo', $tipo_archivo, null, [
                                'id' => 'bibliid_tipoarchivo',
                                'class' => 'form-control required',
                                'placeholder' => 'Seleccione...',
                            ]) !!}
                        </td>
                        <td>
                            <input type="submit" value="Buscar" class="btn btn-success">
                            <a href="/bibliotecas" class="btn btn-default">Ver todo</a>
                        </td>
                        <td>
                           
                        </td>
                    </tr>
                </table>
            </div>
            {!! Form::close() !!}


        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="contenedo">
                <section>



                    <div class="box-body">
                        <div class="box-body table-responsive no-padding">

                            <table class="table table-striped">
                                <thead>
                                    <th>
                                        Archivo
                                    </th>
                                    <th>
                                        Última modificación
                                    </th>
                                    <th>
                                        Tamaño
                                    </th>
                                    <th>
                                        Categoría
                                    </th>
                                    <th>
                                        Rama del derecho
                                    </th>
                                    <th width="5%" >
                                        Acción
                                    </th>
                                </thead>
                                <tbody>
                                    @foreach ($bibliotecas as $biblioteca)
                                        <tr role="row" class="odd">

                                            <td class="sorting_1">
                                                @if ($biblioteca->isFile('pdf'))
                                                    <span style="color:red;margin-right: 5px;"
                                                        class="fa fa-file-pdf-o"></span>
                                                @elseif($biblioteca->isFile('docx'))
                                                    <span style="color:blue;margin-right: 5px;"
                                                        class="fa fa-file-word-o"></span>
                                                @elseif($biblioteca->isFile('doc'))
                                                    <span style="color:blue;margin-right: 5px;"
                                                        class="fa fa-file-word-o"></span>
                                                @elseif($biblioteca->isFile('jpg'))
                                                    <span style="color:black;margin-right: 5px;"
                                                        class="fa fa-file-picture-o"></span>
                                                @elseif($biblioteca->isFile('png'))
                                                    <span style="color:black;margin-right: 5px;"
                                                        class="fa fa-file-picture-o"></span>
                                                @elseif($biblioteca->isFile('xlsx'))
                                                    <span style="color:green;margin-right: 5px;"
                                                        class="fa fa-file-excel-o"></span>
                                                @elseif($biblioteca->isFile('xls'))
                                                    <span style="color:green;margin-right: 5px;"
                                                        class="fa fa-file-excel-o"></span>
                                                @endif


                                                {{ $biblioteca->biblinombre }}
                                            </td>
                                            <td>                                                
                                                {{ getSmallDateWithHour($biblioteca->updated_at) }}<br>
                                                <small> {{ ($biblioteca->updated_at->diffForHumans()) }}
                                                    </small>
                                            </td>
                                            <td>
                                                @if ($biblioteca->biblidoctamano / 1024 >= 1000)
                                                    {{ number_format($biblioteca->biblidoctamano / 1024 / 1024, 2, '.', '.') }}
                                                    Mb
                                                @else
                                                    {{ number_format($biblioteca->biblidoctamano / 1024, 0, '.', '.') }} Kb
                                                @endif

                                            </td>
                                            <td>
                                                {{ $biblioteca->categoria->tiparchinombre }}
                                            </td>
                                            <td>
                                                {{ $biblioteca->rama_derecho->ramadernombre }}
                                            </td>
                                            <td>
                                                {!! link_to_route(
                                                    'biblioteca.pdf',
                                                    $title = 'Descargar',
                                                    $parameters = $biblioteca->id,
                                                    $attributes = ['class' => 'btn btn-warning btn-sm btn-block', 'target' => '_blank'],
                                                ) !!}
                                                <!-- Trigger the modal with a button -->
                                                <button type="button" class="btn btn-success btn-sm btn-block btn_buscar_biblioteca"
                                                    data-id="{{ $biblioteca->id }}" data-toggle="modal"
                                                    data-target="#myModal_show_details">
                                                    Detalles</button>
                                                <!-- Trigger the modal with a button -->
                                                @if (!currentUser()->hasRole('estudiante'))
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm btn-block btn_buscar_biblioteca"
                                                        data-id="{{ $biblioteca->id }}" data-toggle="modal"
                                                        data-target="#myModal_act_edit_doc">Editar</button>
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm btn-block btn_delete_biblioteca"
                                                        data-id="{{ $biblioteca->id }}">Eliminar</button>

                                                    {{--  {!! link_to_route('biblioteca.change', $title = 'Eliminar', $parameters = $biblioteca->biblioteca_id, $attributes = ['class'=>'btn btn-danger btn-sm btn-block','onclick'=>'return confirm("¿Está seguro de eliminar el registro?")']) !!}
           --}}
                                                @endif
                                            </td>

                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>


                            </table>
                            {{ $bibliotecas->appends(request()->query())->links() }}
                        </div>
                    </div>

                </section>

            </div>
        </div>
    </div>
    @include('galeria.frm_biblioteca_edit')
    @include('galeria.show_detalles_modal')


@stop
@push('scripts')
    <script type="module" src={{ asset('js/admin_bibliotecas.js') }}></script>
@endpush
