@component('components.modal')
    @slot('trigger')
        myModal_create_comentario
    @endslot

    @slot('title')
        Agregando comentario
    @endslot


    @slot('body')
        <div class="row">
            <div class="col-md-12">


                <form method="POST" class="form_store" accept-charset="UTF-8" id="myformCreateComentario">

                    <input type="hidden" name="comentario_id">
                    <div class="form-group">
                        <label for="description">Asunto</label>
                        <div id="asunto" class="form-control6" style="min-height:15px"></div>
                    </div>

                    <div class="form-group">
                        <label for="description">Mensaje</label>
                        <div id="comentario" class="form-control6" style="min-height:50px"></div>
                    </div>

                </form>
            </div>

        </div>
    @endslot
@endcomponent
<!-- /modal -->
