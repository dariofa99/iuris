@component('components.b4.modal_medium')

    @slot('trigger')
        myModal_act_create
    @endslot

    @slot('title')
        Actuación
    @endslot


    @slot('body')
        <form method="POST" id="myformCreateAct" enctype="multipart/form-data" class="act-form">
            @csrf
            <input type="hidden" name="actestado_id" id="actestado_id" value="101">
            <input type="hidden" name="actdocnompropio" value=".">
            <input type="hidden" name="actdocruta" value=".">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="actexpid">Código expediente</label>
                        <input type="text" name="actexpid" id="actexpid" class="form-control" value="{{ $expediente->expid }}" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="actfecha">Fecha</label>
                        <div class="act-date-field">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            <input type="text" name="actfecha" id="actfecha" class="form-control" value="{{ fechaActual() }}" required data-inputmask="'alias': 'yyyy/mm/dd'" data-mask readonly>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="actnombre" id="lbl_type_actuacion">Nueva actuación</label>
                        <input type="text" name="actnombre" id="actnombre" class="form-control required" maxlength="60" required>
                    </div>
                </div>

                @if (currentUser()->hasRole('docente') || $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber)
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="fecha_limit" id="fecha">Fecha límite de entrega</label>
                            <input type="date" name="fecha_limit" id="fecha_limit" class="form-control required" maxlength="60" min="{{ \Carbon\Carbon::now()->addDay(1)->format('Y-m-d') }}" required>
                        </div>
                    </div>
                @endif

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="actdescrip">Descripción</label>
                        <textarea name="actdescrip" id="actdescrip" class="form-control required" maxlength="2000" rows="5" required></textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="actdocnomgen">Subir archivo</label>
                        <input type="file" name="actdocnomgen" id="actdocnomgen" class="form-control required" {{currentUser()->hasRole('estudiante') ? 'required' : ''}}  accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                    </div>
                </div>

                <div class="col-md-12 act-form-actions">
                    <button id="myformCreateActButton" class="btn-act-create" type="button">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Crear actuación
                    </button>
                </div>
            </div>
        </form>
    @endslot
@endcomponent
<!-- /modal -->
