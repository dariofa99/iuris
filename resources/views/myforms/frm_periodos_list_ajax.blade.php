<div class="box-body table-responsive no-padding">
	
    <table class="table">
        <thead>
            <th>Nombre</th>
            <th>Fecha de Inicio </th>
            <th>Fecha final</th>
            <th width="10%">Estado</th>
            <th></th>
            <th>Acciones</th>
        </thead>
        <tbody>
            @foreach ($periodos as $periodo)
                <tr>
                    <td>{{ $periodo->prddes_periodo }}</td>
                    <td>{{ $periodo->prdfecha_inicio }}</td>
                    <td>{{ $periodo->prdfecha_fin }}</td>
                    <td>
                        @if ($periodo->estado)
                            <label class="bg-green dis-block">{{ $periodo->getEstado() }}</label>
                        @else
                            <label class="bg-red dis-block">{{ $periodo->getEstado() }}</label>
                        @endif


                    </td>
                    <td>

                        <input type="radio" @if ($periodo->estado) checked="true" @endif
                            name="radio_state_periodo" data-id="{{ $periodo->periodo_id }}" id="radio_state_periodo-{{ $periodo->periodo_id }}"
                            class="radio_state_periodo">
                    </td>
                    <td>
                        <button type="button" data-id="{{ $periodo->periodo_id }}"  class="btn btn-primary btn_edit_per"
                            id="btn_editar-{{ $periodo->periodo_id }}">
                            Editar
                        </button>

                        <button type="button" data-id="{{ $periodo->periodo_id }}"  class="btn btn-danger btn_del_per"
                            id="btn_delete-{{ $periodo->periodo_id }}">
                            Eliminar
                        </button>

                    </td>

                </tr>
            @endforeach


        </tbody>



    </table>
</div>
{{-- 
{!! $periodos->appends(request()->query())->links()!!} --}}
