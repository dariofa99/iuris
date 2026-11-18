@component('components.b4.modal_large')
    @slot('trigger')
        myModal_exp_url_add
    @endslot

    @slot('title')
        Registro de URL de expediente
    @endslot


    @slot('body')
        <div id="content_infoexp">
            <form id="frm_add_url_log" class="iuris-url-form" method="POST">
                <div class="iuris-url-form__intro">
                    <span class="iuris-url-form__icon" aria-hidden="true">
                        <i class="fa fa-globe"></i>
                    </span>
                    <p>Agrega el enlace para consultar el expediente judicial.</p>
                </div>

                <div class="form-group">
                    <label for="url_exp_judicial">URL del expediente</label>
                    <div class="input-group iuris-url-form__input">
                        <div class="input-group-prepend">
                            <span class="input-group-text" aria-hidden="true">
                                <i class="fa fa-link"></i>
                            </span>
                        </div>
                        <input type="url" required class="form-control" id="url_exp_judicial"
                            name="url_exp_judicial" value="{{ $expediente->url_exp_judicial ?? '' }}"
                            placeholder="https://..." aria-describedby="url_exp_judicial_help">
                    </div>
                    <small class="form-text text-muted" id="url_exp_judicial_help">
                        Incluye el protocolo, por ejemplo: https://
                    </small>
                </div>

                <div class="iuris-url-form__actions">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    <button id="btn_add_url" type="submit" class="btn btn-iuris-primary">
                        <i class="fa fa-save" aria-hidden="true"></i>
                        Guardar URL
                    </button>
                </div>
            </form>
        </div>

    @endslot
@endcomponent
<!-- /modal -->
