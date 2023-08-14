@extends('layouts.dashboard')


@section('titulo_general')
    Turnos
@endsection

@section('titulo_area')
    Docentes
@endsection

@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('area_forms')

    @include('msg.success')
    <div class="card card-tabs">
        <div class="card-header p-2">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active urlactive" href="#horario-asig" data-toggle="tab">
                        Asignación horario
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link urlactive" id="asistencia-tab" data-toggle="tab" href="#asistencia_tab" role="tab"
                        aria-controls="identitaria_tab" aria-selected="false">
                        Reporte asistencia
                    </a>
                </li>


            </ul>
        </div><!-- /.card-header -->
        <div class="card-body">
            <div class="tab-content">

                <div class="tab-pane active" id="horario-asig">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sel1">Docentes:</label>
                                <select class="form-control" id="select_doc_horario">
                                    <option value="0">Seleccione...</option>
                                    @foreach ($docentes as $docente)
                                        <option value="{{ $docente->idnumber }}">{{ $docente->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <label id="name_doc_horairo">Seleccione un docente</label>
                        </div>
                    </div>

                    <div class="row" id="content-docentetr" style="display: none">
                        <div class="col-md-12">
                            <div class="box-body table-responsive no-padding">
                                <table class="normal-table table-list-est-tur table" id="table_turnos_docentes">
                                    <thead>

                                        <th width="150px">
                                            Hora Inicio
                                        </th>
                                        <th width="150px">
                                            Hora Fin
                                        </th>
                                        <th width="80px">
                                            Lun
                                        </th>
                                        <th width="80px">
                                            Mar
                                        </th>
                                        <th width="80px">
                                            Mie
                                        </th>
                                        <th width="80px">
                                            Jue
                                        </th>
                                        <th width="80px">
                                            Vie
                                        </th>
                                        <th width="40px">
                                            Eliminar
                                        </th>
                                    </thead>
                                    <tbody id="new_dia_docente">



                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-md-6">
                                        <button id="guardar_horario_doc" class="btn btn-primary">Guardar</button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button id="horariomas" value="1" class="btn btn-success">Agregar</button>
                                    </div>
                                </div>

                                <br><br>
                                <br><br>
                                <br>
                            </div>
                            <hr>
                        </div>
                    </div>


                </div>
                <!-- /.tab-pane -->

                <!-- /.tab-pane -->
                <div id="asistencia_tab" class="tab-pane fade" role="tabpanel" aria-labelledby="asistencia-tab">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-body table-responsive no-padding">
                                <table id="tbl_repor_asis" class="table table-bordered table-striped dataTable"
                                    role="grid">

                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Cédula</th>
                                            <th>Nombre</th>
                                            <th>Horas asistidas</th>
                                            <th>Horas permisos</th>
                                            <th>Horas reposiciones</th>
                                            <th>Horas pendientes</th>


                                        </tr>
                                    </thead>
                                    <tbody id="contenrepasistenciadoc">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>


                <!-- /.tab-pane -->

                <!-- /.tab-pane -->

                <!-- /.tab-pane -->

                <!-- /.tab-pane -->


            </div>
            <!-- /.tab-content -->
        </div><!-- /.card-body -->
    </div>


@stop

@push('scripts')
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>
    <script type="module" src={{ asset('js/admin_turnos.js') }}></script>
@endpush
