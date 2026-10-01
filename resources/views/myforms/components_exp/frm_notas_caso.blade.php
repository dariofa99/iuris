<div class="row nota-caso-component">
    
    @if (count($expediente->getNotas()) >= 0)

        @php
            $notas = $expediente->getNotas();
        @endphp

        <div class="col-md-12 nota-caso-toolbar">
            <span class="nota-caso-status"><i class="fa fa-check-circle" aria-hidden="true"></i> Evaluación
                {{ $notas['nota_tipo_text'] }}</span>

            <a class="nota-caso-view" id="btn_edit_nt_exp"><i class="fa fa-eye" aria-hidden="true"></i> Ver notas</a>
        </div>
    @else
        <div class="col-md-12 nota-caso-toolbar">
            @if ($expediente->expestado_id == 4 || $expediente->expestado_id == 2)
                <button type="button" class="btn-nota-caso add_nota_expedientes" data-toggle="modal"
                    data-target="#myModal_add_nota_final_expedientes" id="1">
                    <i class="fa fa-calculator" aria-hidden="true"></i>
                    <span>Asignar notas finales</span>
                </button>
            @endif
        </div>




    @endif
</div>
