<div class="box-body table-responsive no-padding">
    <table class="table">
        <thead>
            <th>Nombre</th>
            <th>Fecha de Inicio </th>
            <th>Fecha final</th>
            <th>Periodo</th>
            <th width="10%">Estado</th>
            <th></th>

            <th>Acciones</th>
        </thead>
        <tbody>
            @foreach ($segmentos as $segmento)
                <tr>
                    <td>{{ $segmento->segnombre }}</td>
                    <td>{{ $segmento->fecha_inicio }}</td>
                    <td>{{ $segmento->fecha_fin }}</td>
                    <td>
                        {{ $segmento->prddes_periodo }}
                    </td>
                    <td>
                        @if ($segmento->estado)
                            <label class="bg-green dis-block">{{ $segmento->getEstado() }}</label>
                        @else
                            <label class="bg-red dis-block">{{ $segmento->getEstado() }}</label>
                        @endif


                    </td>
                    <td>
                        <input data-id="{{ $segmento->id }}" type="radio"
                            @if ($segmento->estado) checked="true" @endif name="radio_state_segmen"
                            id="radio_state_segmento-{{ $segmento->id }}" class="radio_state_segmento"
                            value="{{ $segmento->act_fc }}">

                    </td>

                    <td>




                        @if ($segmento->est_evaluado)
                            <a href="#" disabled id="segmento_id-{{ $segmento->id }}" class="btn btn-warning ">
                                Cerrado
                            </a>
                        @else
                            @if (date('Y-m-d') >= $segmento->fecha_fin)
                                <a href="#" data-id="{{ $segmento->id }}" id="segmento_id-{{ $segmento->id }}"
                                    class="btn btn-warning btn_cerrar_seg">
                                    Cerrar Corte
                                </a>
                            @else
                                <button disabled class="btn btn-sm btn-warning" id="segmento_id-{{ $segmento->id }}">
                                    Fecha
								</button>
                            @endif
                        @endif
                        <button data-id="{{ $segmento->id }}" type="button" id="segmento_id-{{ $segmento->id }}"
                            class="btn btn-primary btn_edit_seg">
                            Editar
                        </button>

                        <button data-id="{{ $segmento->id }}" type="button" id="segmento_id-{{ $segmento->id }}"
                            class="btn btn-danger btn_del_seg">
                            Eliminar
                        </button>
                    </td>
                </tr>
            @endforeach


        </tbody>



    </table>
</div>
