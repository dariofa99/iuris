<!-- Trigger the modal with a button -->
<div class="row">
    <div class="col-md-8">
        @if (!$readonly)
            @if (currentUser()->hasRole('amatai') ||
                    $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                    currentUser()->hasRole('estudiante'))
                @if ($expediente->expestado_id != '2' and $expediente->expestado_id != '4')
                    @if ($expediente->exptipoproce_id != '1')
                        <button type="button"
                            @if (currentUser()->hasRole('docente')) id="btn_new_act_doct" @else id="btn_new_act" @endif
                            class="btn btn-primary btn-sm btn_new_act" data-toggle="modal"
                            data-titulo_modal="Nueva actuación" data-target="#myModal_act_create">Nueva actuación</button>
                    @endif
                    <button type="button" class="btn btn-default btn-sm btn_new_act" data-toggle="modal"
                        data-target="#myModal_act_create" data-titulo_modal="Nuevo anexo" id="btn_new_anex">
                        Agregar anexo general
                    </button>
                @endif
            @endif
        @endif
    </div>

    <div class="col-md-4" style="border-bottom: 1px solid rgb(233, 233, 233);padding-bottom:3px">
        @if ($expediente->exptipoproce_id != '1')
            {!! $expediente->getDaysForNexAct() !!} 
        @endif
    </div>



</div>


