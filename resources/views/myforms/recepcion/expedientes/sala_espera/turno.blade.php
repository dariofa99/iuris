<div class="row">

    <div class="col-md-5">
        <h3>
            <strong> Su número de solicitud:</strong>
            {{ $solicitud->number }}
            <br>
            {{ $solicitud->name }} {{ $solicitud->lastname }}
        </h3>

    </div>
    <div class="col-md-7">
        @if (isset($solicitud) and $solicitud->type_status_id == 154)
            <div class="content-turno">
                <label class="numero_turno"> <span>
                        @if (isset($solicitud))
                            {{ $solicitud->turno }}
                        @endif
                    </span>
                </label>
                <label class="lbl_turno"> SU TURNO </label>
            </div>
            <div class="content-turno">
                <label class="lbl_turno">
                    El turno que se está atendiendo es:
                </label>

                <label class="numero_turno"> <span>
                        @if (isset($solicitud))

                            @if ($solicitud->getTurnoEnAtencion() != 0)
                                {{ $solicitud->getTurnoEnAtencion() }}
                            @else
                                Espere un momento por favor...
                            @endif

                        @endif
                    </span>
                </label>
            </div>
        @elseif(isset($solicitud) and $solicitud->type_status_id == 155)
            <div class="content-turno">
                <h3>
                    La solicitud esta siendo revisada, por favor espere...
                </h3>
            </div>
        @elseif(isset($solicitud) and $solicitud->type_status_id == 157)
            <h3>
                {{ $solicitud->mensaje }}
            </h3>
        @endif
    </div>

</div>
