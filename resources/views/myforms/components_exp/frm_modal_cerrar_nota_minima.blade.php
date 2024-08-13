@component('components.b4.modal_medium')
    @slot('trigger')
        mymodalCerrarNotaMinima
    @endslot

    @slot('title')
        Cerrando caso
    @endslot


    @slot('body')
        <div class="row justify-content-center">
            <div class="col-md-12">
                <form>
                    <div class="form-group">
                        <div class="alert alert-info">
                            <h3>
                                Atención. Se asignarán como notas la mímina aprobatoria (3.0)
                                y se cerrará el caso.
                            </h3>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Descrip</label>
                        <textarea required class="form-control " name="description" id="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <button>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endslot
@endcomponent
<!-- /modal -->
