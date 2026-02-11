@component('components.b4.modal_large')
    @slot('trigger')
        myModal-{{ $expediente->id }}
    @endslot


    @slot('title')
        Detalles del caso
    @endslot


    @slot('body')
        <div class="container-fluid expediente-pro py-3">

            <div class="row">

                <!-- ===================================================== -->
                <!-- PERFIL ESTUDIANTE -->
                <!-- ===================================================== -->
                <div class="col-lg-4 mb-4">

                    <div class="pro-card text-center h-100">

                        <img class="avatar mb-3 avatar-click"
                            src="{{ is_file(public_path('thumbnails/' . $expediente->estudiante->image))
                                ? asset('thumbnails/' . $expediente->estudiante->image)
                                : asset('thumbnails/default.jpg') }}"
                            data-img="{{ is_file(public_path('thumbnails/' . $expediente->estudiante->image))
                                ? asset('thumbnails/' . $expediente->estudiante->image)
                                : asset('thumbnails/default.jpg') }}">


                        <h5 class="font-weight-bold mb-1">
                            {{ FullName($expediente->estudiante->name, $expediente->estudiante->lastname) }}
                        </h5>

                        <small class="text-muted d-block mb-3">
                            {{ $expediente->estudiante->idnumber }}
                        </small>

                        <div class="chips justify-content-center mb-3">
                            
                            
                               
     

                            <span class="chip {{$expediente->getColorTurno($expediente->estudiante->turno->color->ref_value)}}">
                                 {{ $expediente->estudiante->curso->ref_nombre }}<br>
                                {{ $expediente->getMjs($expediente->estudiante->turno->horario->ref_value) }}
                            </span> 
                        </div>

                        <hr>

                        <div class="text-left small">
                            <p><i class="fas fa-phone mr-2"></i> {{ $expediente->estudiante->tel1 }}</p>
                            <p><i class="fas fa-map-marker-alt mr-2"></i> {{ $expediente->estudiante->address }}</p>
                        </div>

                    </div>
                </div>


                <!-- ===================================================== -->
                <!-- INFORMACIÓN -->
                <!-- ===================================================== -->
                <div class="col-lg-8">

                    <!-- =================== EXPEDIENTE =================== -->
                    <div class="pro-card mb-4">

                        <h6 class="section-title">
                            <i class="fas fa-folder-open mr-2"></i>
                            Información del Expediente
                        </h6>

                        <div class="info-grid">

                            <div class="info-item">
                                <span>Docente</span>
                                <b>{{ $expediente->getDocenteAsig()->name }} {{ $expediente->getDocenteAsig()->lastname }}</b>
                            </div>

                            <div class="info-item">
                                <span>Código</span>
                                <b>{{ $expediente->expid }}</b>
                            </div>

                            <div class="info-item">
                                <span>Rama del derecho</span>
                                <b>{{ $expediente->rama_derecho->ramadernombre }}</b>
                            </div>

                            <div class="info-item">
                                <span>Fecha creación</span>
                                <b>{{ getSmallDateWithHour($expediente->created_at) }}</b>
                            </div>

                            <div class="info-item">
                                <span>Fecha de asignación</span>
                                @if ($expediente->asignacion)
                                    <b>{{ getSmallDateWithHour($expediente->asignacion->fecha_asig) }}</b>
                                @else
                                    <b>—</b>
                                @endif

                            </div>

                        </div>
                    </div>


                    <!-- =================== SOLICITANTE =================== -->
                    @if (!currentUser()->hasRole('solicitante'))
                        <div class="pro-card">

                            <h6 class="section-title">
                                <i class="fas fa-user mr-2"></i>
                                Datos del Solicitante
                            </h6>

                            <div class="info-grid">

                                <div class="info-item">
                                    <span>Identificación</span>
                                    <b>{{ $expediente->solicitante->idnumber ?? '—' }}</b>
                                </div>

                                <div class="info-item">
                                    <span>Nombre</span>
                                    <b>
                                        {{ $expediente->solicitante
                                            ? FullName($expediente->solicitante->name, $expediente->solicitante->lastname)
                                            : 'Sin solicitante' }}
                                    </b>
                                </div>

                                <div class="info-item">
                                    <span>Teléfono</span>
                                    <b>
                                        {{ $expediente->solicitante->tel1 ?? '—' }}
                                        @if (isset($expediente->solicitante->tel2) && $expediente->solicitante->tel2 != '')
                                            - {{ $expediente->solicitante->tel2 }}
                                        @endif
                                    </b>
                                </div>

                                <div class="info-item">
                                    <span>Dirección</span>
                                    <b>{{ $expediente->solicitante->address ?? '—' }}</b>
                                </div>

                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    @endslot
@endcomponent
<!-- /modal -->
