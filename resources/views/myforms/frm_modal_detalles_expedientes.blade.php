@component('components.b4.modal_large')
    @slot('trigger')
        myModal-{{ $expediente->id }}
    @endslot


    @slot('title')
       Detalles del caso
    @endslot


    @slot('body')
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            @if (!currentUser()->hasRole('solicitante'))
                <tr>
                    <th>DOCENTE:</th>
                    <td>{{ $expediente->getDocenteAsig()->name }}
                        {{ $expediente->getDocenteAsig()->lastname }}</td>
                </tr>
                <tr>
                    <th>CÓDIGO EXPEDIENTE:</th>
                    <td>{{ $expediente->expid }}</td>
                </tr>

                <tr>
                    <th>RAMA DERECHO:</th>
                    <td>
                        {{ $expediente->rama_derecho->ramadernombre }}
                    </td>
                </tr>

                <tr>
                    <th>IDENTIFICACIÓN SOLICITANTE:</th>
                    <td>{{ $expediente->solicitante ? $expediente->solicitante->idnumber :'No hay solicitante' }}
                    </td>
                </tr>

                <tr>
                    <th>SOLICITANTE:</th>
                    <td>{{ $expediente->solicitante ? FullName($expediente->solicitante->name, $expediente->solicitante->lastname) :'No hay solicitante'}}
                    </td>
                </tr>

                <tr>
                    <th>TELÉFONO SOLICITANTE:</th>
                    <td> 
                        @if($expediente->solicitante)
                        {{ $expediente->solicitante->tel1 }} @if ($expediente->solicitante->tel2 != '')
                            - {{ $expediente->solicitante->tel2 }}
                        @endif
                        @else
                        Sin solicitante
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>DIRECCIÓN SOLICITANTE:</th>
                    <td> {{ $expediente->solicitante ? $expediente->solicitante->address : "Sin solicitante" }}
                    </td>
                </tr>
            @endif
            <tr>
                <th>FECHA CREACIÓN EXPEDIENTE:</th>
                <td> {{ $expediente->expfecha }}
                </td>
            </tr>


            <tr>
                <th>ÚLTIMA ACTUALIZACIÓN:</th>
                <td> {{ $expediente->updated_at }}
                </td>
            </tr>
            <tr>
                <th></th>
                <td>
                </td>
            </tr>
            @if (!currentUser()->hasRole('estudiante'))
                <tr>

                    <td colspan="2" style="text-align: center;">
                        <img src="{{ is_file(public_path('thumbnails/' . $expediente->estudiante->image)) ? asset('thumbnails/' . $expediente->estudiante->image) : asset('thumbnails/default.jpg') }}"
                            style="border-radius: 10px;-webkit-box-shadow: -9px 10px 9px 0px rgba(0,0,0,0.75);-moz-box-shadow: -9px 10px 9px 0px rgba(0,0,0,0.75);box-shadow: -9px 10px 9px 0px rgba(0,0,0,0.75); width: 180px;"
                            alt="User Image">
                    </td>
                </tr>
            @endif
            @if (!currentUser()->hasRole('solicitante'))
                <tr>
                    <th>IDENTIFICACIÓN ESTUDIANTE:</th>
                    <td> {{ $expediente->estudiante->idnumber }}
                    </td>
                </tr>
            @endif
            <tr>
                <th>ESTUDIANTE:</th>
                <td> {{ FullName($expediente->estudiante->name, $expediente->estudiante->lastname) }}
                </td>
            </tr>
            <tr>
                <th>CURSO:</th>
                <td> {{ $expediente->estudiante->curso->ref_nombre }}
                </td>
            </tr>
            @if (!currentUser()->hasRole('solicitante'))
                <tr>
                    <th>TELÉFONO ESTUDIANTE:</th>
                    <td>{{ $expediente->estudiante->tel1 }} @if ($expediente->estudiante->tel2 != '')
                            - {{ $expediente->estudiante->tel2 }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>DIRECCIÓN ESTUDIANTE:</th>
                    <td> {{ $expediente->estudiante->address }}
                    </td>
                </tr>

                <tr>
                    <th>TURNO:</th>
                    <td>
                        @if ($expediente->estudiante->turno)
                            <a href="/horarios" style="cursor: pointer;"
                                title="Ir a horarios">
                                <label style="cursor: pointer;"
                                    class="label {{ $expediente->getColorTurno($expediente->estudiante->turno->color->ref_value) }}">

                                    {{ $expediente->getMjs($expediente->estudiante->turno->horario->ref_value) }}

                                </label>
                            </a>
                        @endif



                    </td>
                </tr>
            @endif
            @if (currentUser()->hasRole('solicitante'))
                <tr>
                    <th>DUDAS ADICIONALES DE SU CASO SOLO:</th>
                    <td>
                        @if ($expediente->estudiante->turno)
                            {{ $expediente->getMjs($expediente->estudiante->turno->horario->ref_value) }}
                        @endif

                    </td>
                </tr>
            @endif
        </table>
    </div>
    @endslot
@endcomponent
<!-- /modal -->
