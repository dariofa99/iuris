<!--notas-->




@if (count($expediente->get_has_nota_final()) <= 0)

    <div class="row nota-caso-component">
        @if ($segmento and $periodo)
        
            @if (count($expediente->get_nota_corte('etica')) > 0 and
                    $expediente->get_nota_corte('etica')['id'] == 0 and
                    $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber)

                <div class="col-md-12 nota-caso-toolbar">

                    @if ($segmento->fecha_fin >= date('Y-m-d'))
                  


                        @if ($expediente->expestado_id == 4 
                        || $expediente->expestado_id == 2)
                          
                            <button type="button" class="btn-nota-caso add_nota_expedientes"
                                data-toggle="modal" data-target="#myModal_add_nota_final_expedientes" id="1">
                                <i class="fa fa-calculator" aria-hidden="true"></i>
                                <span>Asignar notas finales</span>
                            </button>
                       
                       @elseif(
                            $expediente->expestado_id == 1 || $expediente->expestado_id == 3 and
                                $segmento->act_fc and
                                $expediente->exptipoproce_id != 1)
                            <button type="button" class="btn-nota-caso add_nota_expedientes" id="2" data-toggle="modal"
                                data-target="#myModal_add_nota_final_expedientes" data-placement="top"
                                data-original-title="Agregar Nota">
                                <i class="fa fa-calculator" aria-hidden="true"></i>
                                <span>Asignar nota final de corte</span>
                            </button>
                        @endif
                    @else
                        <button type="button" disabled class="btn-nota-caso btn-nota-caso--disabled">
                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                            <span>Fecha de corte vencida</span>
                        </button>
 
                    @endif

                </div>
            @elseif(count($expediente->get_nota_corte('etica')) > 0 and $expediente->get_nota_corte('etica')['id'] != 0)
                <div class="col-md-12 nota-caso-toolbar">
                    @if ($expediente->get_nota_corte('etica')['tipo_id'] == '2')
                        <span class="nota-caso-status"><i class="fa fa-check-circle" aria-hidden="true"></i> Evaluación provisional</span>
                    @elseif($expediente->get_nota_corte('etica')['tipo_id'] == '1')
                        <span class="nota-caso-status"><i class="fa fa-check-circle" aria-hidden="true"></i> Evaluación definitiva</span>
                    @endif
                    <a class="nota-caso-view" id="btn_edit_nt_exp"><i class="fa fa-eye" aria-hidden="true"></i> Ver notas</a>
                </div>
            @endif
        @endif

    </div>
@else
    <div class="row">
        <div class="col-md-12 nota-caso-toolbar">
            <span class="nota-caso-status"><i class="fa fa-check-circle" aria-hidden="true"></i> Evaluación final</span>
            <a class="nota-caso-view" id="btn_edit_nt_exp"><i class="fa fa-eye" aria-hidden="true"></i> Ver notas</a>
        </div>
    </div>
@endif

<!--notas-->
