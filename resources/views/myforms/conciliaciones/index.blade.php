@extends('layouts.dashboard')
@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
@endpush

@section('titulo_area')
   @if(currentUser()->hasRole('solicitante') || currentUser()->can('crear_conciliaciones'))
        <a href="/conciliaciones/create" id="btn_new_conciliacion" class="btn btn-primary btn-lg">
           <i class="fa fa-edit"></i> Solicitar nueva conciliación
        </a>
            @else
            Conciliaciones
  @endif
@endsection
@section('navbar')
    @include('content.navbar')
@endsection
@section('area_forms')
    {!! Form::open(['route' => 'conciliaciones.index', 'method' => 'GET', 'id' => 'myformConcFilter']) !!}
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <select name="tipo_busqueda" id='tipo_busqueda_conciliacion' class="form-control" placeholder="Seleccione..."
                    required="required">
                    <option value="">Seleccione...</option>

                    <option @if (Request::has('tipo_busqueda') and Request::get('tipo_busqueda') == 'num_conciliacion') selected @endif value="num_conciliacion">Número de
                        conciliación</option>

                    <option @if (Request::has('tipo_busqueda') and Request::get('tipo_busqueda') == 'estado_id') selected @endif value="estado_id">Estado</option>

                    <option @if (Request::has('tipo_busqueda') and Request::get('tipo_busqueda') == 'fecha_radicado') selected @endif value="fecha_radicado">Fecha de
                        radicado</option>

                    <option @if (Request::has('tipo_busqueda') and Request::get('tipo_busqueda') == 'fecha_rango') selected @endif value="fecha_rango">Rango Fechas
                    </option>

                    <option @if (Request::has('tipo_busqueda') and Request::get('tipo_busqueda') == 'all') selected @endif value="all">Todo</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <select name="data" class="form-control input-search selectpicker" required id="select_data">
                @foreach ($types_status as $id => $item)
                    <option {{ $id == Request::get('data') ? 'selected' : '' }} value="{{ $id }}">
                        {{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success"><i class="fa fa-search"> </i> Buscar </button>

        </div>
    </div>



    <div class="row">

        <div class="col-md-8">

            <table class="table-buscar-expe">

                <tr>
                    <td colspan="">

                    </td>
                    @if (currentUser()->hasRole('docente'))
                        <td>
                            {{--   <input type="checkbox" @if ((Request::has('search_onlyMy_exp') and Request::has('search_onlyMy_exp')) || empty(request()->all())) checked @endif name="search_onlyMy_exp" id="search_onlyMy_exp">Mis casos
          --}} </td>
                    @endif
                </tr>
                <tr>
                    <td width="35%">

                    </td>
                    <td width="35%">

                        @php
                            //dd($types_status);
                            $disabled = '';
                            
                        @endphp



                        <div id="input_text" class="inputs"
                            @if (Request::has('tipo_busqueda') and
                                    Request::get('tipo_busqueda') == 'num_conciliacion' || Request::get('tipo_busqueda') == 'idnumber') style="display: block" @else style="display: none" @endif>

                            <input type="text"
                                @if (Request::has('tipo_busqueda') and
                                        Request::get('tipo_busqueda') == 'num_conciliacion' || Request::get('tipo_busqueda') == 'idnumber') value="{{ Request::get('data') }}" @else disabled @endif
                                name="data" class="form-control input-search" required id="input_data_text" />



                        </div>

                        <div class="input-group inputs" id="input_select"
                            @if (Request::has('tipo_busqueda') and Request::get('tipo_busqueda') == 'estado_id') style="display: block" @else style="display: none" @endif>
                            <select name="data" class="form-control input-search" required id="select_data">
                                @foreach ($types_status as $id => $item)
                                    <option {{ $id == Request::get('data') ? 'selected' : '' }} value="{{ $id }}">
                                        {{ $item }}</option>
                                @endforeach
                            </select>

                            {{-- {!!Form::select('data',$types_status,182,['class' => 'form-control input-search', 'required' => 'required','id'=>'select_data'] ); !!} --}}
                        </div>





                    </td>
                    <td>
                    </td>
                </tr>

            </table>

        </div>
    </div>
    {!! Form::close() !!}


    <div class="row">
        <div class="col-md-12 table-responsive no-padding">

            <table class="table">
                <thead>
                    <th>
                        Número
                    </th>
                    <th>
                        Solicitante
                    </th>

                    <th>
                        Tipo
                    </th>
                    <th>
                        Estado
                    </th>
                    <th>
                        Fecha
                    </th>
                    <th>
                        Acciones
                    </th>
                </thead>
                <tbody>
                    @foreach ($conciliaciones as $key => $conciliacion)
                        <tr>
                            <td>
                                <div class="container_img">
                                    {{-- <img src="{{asset('dist/img/folder_icon.png')}}" alt=""> --}}
                                    <span>
                                        {{ $conciliacion->num_conciliacion }}
                                    </span>
                                </div>

                            </td>
                            <td>
                                @if (count(
                                        $conciliacion->usuarios()->where('tipo_usuario_id', 205)->get()) > 0)
                                    {{ $conciliacion->usuarios()->where('tipo_usuario_id', 205)->first()->name }}
                                    {{ $conciliacion->usuarios()->where('tipo_usuario_id', 205)->first()->lastname }}
                                @else
                                    Sin usuarios
                                @endif
                            </td>

                            <td>
                                {{ $conciliacion->categoria->ref_nombre }}
                            </td>
                            <td>
                                <span style="color:#ffffff;background-color: {{ $conciliacion->estado->color }}"
                                    class="badge">
                                    {{ $conciliacion->estado->ref_nombre }}
                                </span>
                            </td>

                            <td>

                                {{ getSmallDateWithHour($conciliacion->created_at) }}
                                <p style="font-size: 14px"><small>
                                        <i>({{ \Carbon\Carbon::parse($conciliacion->created_at)->diffForHumans() }})</i>
                                    </small></p>
                            </td>
                            <td>
                                <a href="/conciliaciones/{{ $conciliacion->id }}/edit"
                                    class="btn btn-sm btn-primary">Gestionar</a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            {{ $conciliaciones->appends(request()->query())->links() }}
        </div>
    </div>

@stop
@push('scripts')
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>
    <script type="module" src={{ asset('js/admin_conciliacion.js') }}></script>
    <script>
        $(document).ready(function() {
            $(".selectpicker").selectpicker()
        });
    </script>
@endpush
