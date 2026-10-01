@component('components.b4.modal_large')

    @slot('trigger')
        myModal_add_nota_final_expedientes
    @endslot

    @slot('title')
        Registro de nota expediente
    @endslot


    @slot('body')
        <form method="POST" id="myform_add_nota_final_expedientes" class="act-form grade-form">
            {{ csrf_field() }}
            <input type="hidden" name="orgntsid" id="orgntsid" value="1">
           {{--  <input type="hidden" name="tpntid" id="tpntid" value="1"> --}}
            <input type="hidden" name="expid" id="expid" value="{{ $expediente->expid }}">

            @if ($periodo and $segmento)
                <input type="hidden" name="segid" id="segid" value="{{ $segmento->segmento_id }}">
                <input type="hidden" name="perid" id="perid" value="{{ $periodo->periodo_id }}">

                <div class="grade-form-context">
                    <span class="grade-form-eyebrow">Periodo de evaluación</span>
                    <div class="grade-form-tags">
                        <span class="grade-form-tag"><i class="fa fa-calendar" aria-hidden="true"></i>
                            {{ $periodo->prddes_periodo }}</span>
                        <span class="grade-form-tag"><i class="fa fa-bookmark" aria-hidden="true"></i>
                            {{ $segmento->segnombre }}</span>
                    </div>
                </div>
            @else
                <div class="grade-form-warning" role="alert">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                    Asegúrate de que el periodo y el segmento de corte estén activos.
                </div>
            @endif

            <div class="grade-form-grid">
                <div class="form-group grade-form-score">
                    <label for="ntaconocimiento">Conocimiento</label>
                    <input type="text" name="ntaconocimiento" id="ntaconocimiento" class="form-control required"
                        placeholder="5.0" inputmode="decimal" autocomplete="off" required data-inputmask="'mask': ['9.9']"
                        data-mask>
                </div>

                <div class="form-group grade-form-score">
                    <label for="ntaaplicacion">Aplicación</label>
                    <input type="text" name="ntaaplicacion" id="ntaaplicacion" class="form-control required"
                        placeholder="5.0" inputmode="decimal" autocomplete="off" required data-inputmask="'mask': ['9.9']"
                        data-mask>
                </div>

                <div class="form-group grade-form-score">
                    <label for="ntaetica">Ética</label>
                    <input type="text" name="ntaetica" id="ntaetica" class="form-control required" placeholder="5.0"
                        inputmode="decimal" autocomplete="off" required data-inputmask="'mask': ['9.9']" data-mask>
                </div>
            </div>

            <div class="form-group grade-form-concept">
                <label for="ntaconcepto">Concepto de la nota</label>
                <textarea name="ntaconcepto" id="ntaconcepto" class="form-control required" maxlength="100000" rows="4"
                    placeholder="Escribe una valoración breve del desempeño..." required></textarea>
            </div>

            <div class="iuris-note-type-selector">
                <div class="iuris-note-type-header">
                    <div class="iuris-note-type-icon">
                        <i class="fa fa-tag" aria-hidden="true"></i>
                    </div>
                    <div>
                        <strong>Estado de la calificación</strong>
                        <span>Seleccione el tipo de nota que desea registrar</span>
                    </div>
                </div>

                <div class="iuris-note-type-options">


                    <label class="iuris-note-type-option">
                        <input type="radio" name="tpntid" value="1" checked>

                        <span class="iuris-note-type-content">
                            <span class="iuris-note-type-radio"></span>

                            <span class="iuris-note-type-text">
                                <strong>Definitiva</strong>
                                <small>La calificación no puede ser modificada. Se tendrá en cuenta para el
                                    reporte definitivo.</small>
                            </span>
                        </span>
                    </label>
                    <label class="iuris-note-type-option">
                        <input type="radio" name="tpntid" value="2">

                        <span class="iuris-note-type-content">
                            <span class="iuris-note-type-radio"></span>

                            <span class="iuris-note-type-text">
                                <strong>Provisional</strong>
                                <small>La calificación aún puede ser modificada. No se tendrá en cuenta para el
                                    reporte definitivo.</small>
                            </span>
                        </span>
                    </label>
                </div>
            </div>

            @if ($periodo and $segmento)
                <div class="grade-form-actions">
                    <button type="button" class="btn-act-create" id="btn_add_nota">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        <span>Guardar nota</span>
                    </button>
                </div>
            @endif
        </form>
    @endslot
@endcomponent
<!-- /modal -->
