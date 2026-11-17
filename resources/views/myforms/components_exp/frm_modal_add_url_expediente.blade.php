@component('components.b4.modal_large')
    @slot('trigger')
        myModal_exp_url_add
    @endslot

    @slot('title')
        Registro de URL de expediente
    @endslot


    @slot('body')
      
    <div id="content_infoexp">
    
                <form id="frm_add_url_log" method="POST">
                    <div class="col-md-12">
                        <label for="url_exp_judicial">URL del expediente judicial</label>
                    </div>
                    <div class="col-md-12">
                        <input type="url" required class="form-control required form-control-sm" id="url_exp_judicial"
                            name="url_exp_judicial" value="{{ $expediente->url_exp_judicial ?? '' }}">
                    </div>
                    <div class="form-group col-md-12 mt-3">
    
                        <button id="btn_add_url" type="submit" class="btn-block btn btn-primary btn-sm">
                            Guardar
                        </button>
    
                    </div>
                </form>
    </div>

    @endslot
@endcomponent
<!-- /modal -->
