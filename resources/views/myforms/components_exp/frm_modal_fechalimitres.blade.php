@component('components.b4.modal_medium')
    @slot('trigger')
        fechalimitres
    @endslot
    @slot('size')
        modal-dialog modal-md
    @endslot

    @slot('title')
        <span class="iuris-deadline-modal-title">
            <i class="fa fa-calendar" aria-hidden="true"></i>
            Fecha límite de respuesta
        </span>
    @endslot

    @slot('body')
        <div class="iuris-deadline-modal" id="ct_forcita">
            <input type="hidden" class="form-control" id="id" name="id">

            <div class="iuris-deadline-modal-summary">
                <div class="iuris-deadline-modal-person">
                    <span class="iuris-deadline-modal-icon" aria-hidden="true">
                        <i class="fa fa-user"></i>
                    </span>
                    <div>
                        <span class="iuris-deadline-modal-label">Estudiante</span>
                        <strong id="lbl_nombre_estudiante">
                            {{ $expediente->estudiante->name }} {{ $expediente->estudiante->lastname }}
                        </strong>
                    </div>
                </div>

                <div class="iuris-deadline-modal-case">
                    <span class="iuris-deadline-modal-label">N.º expediente</span>
                    <strong id="lbl_expid">{{ $expediente->expid }}</strong>
                </div>
            </div>

            <div class="form-group iuris-deadline-modal-field">
                <label for="expfecha_res">Nueva fecha límite</label>
                <div class="iuris-deadline-modal-input">
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                    <input
                        type="date"
                        min="{{ date('Y-m-d') }}"
                        class="form-control"
                        id="expfecha_res"
                        name="expfecha_res"
                        value="{{ $expediente->expfecha_res }}"
                    >
                </div>
                <small>Selecciona la fecha límite para responder este caso.</small>
            </div>

            <div class="iuris-deadline-modal-actions">
                <button type="button" class="btn iuris-deadline-modal-cancel" data-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" id="btn_mod_expfecha_res" class="btn iuris-deadline-modal-submit">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    Guardar fecha
                </button>
            </div>
        </div>
    @endslot
@endcomponent
<!-- /modal -->
