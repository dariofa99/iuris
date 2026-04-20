@component('components.b4.modal_medium')
    @slot('trigger')
        myModal_persona_externa_create
    @endslot

    @slot('title')
        <label id="lbl_title_fract">
            Creando formulario de persona externa
        </label>
    @endslot


    @slot('body')
        <form id="myFormCreatePersonaExterna" method="POST">
            <div class="row justify-content-center">
                <div class="form-group col-md-12">
                    <label for="tipo_persona">Tipo de persona</label>
                    <div class="input-group">
                        <input type="text" id="tipo_persona" name="tipo_persona" class="form-control required" value="">
                    </div>
                </div>
            {{--     <div class="form-group col-md-12">
                    <label for="codigo">Código</label>
                    <div class="input-group">
                        <input type="text" id="codigo" name="codigo" class="form-control required" value="">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label for="codigo">Versión</label>
                    <div class="input-group">
                        <input type="text" id="version" name="version" class="form-control required" value="">
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label for="fecha_vigencia">Fecha de vigencia</label>
                    <div class="input-group">
                        <input type="date" id="fecha_vigencia" name="fecha_vigencia" class="form-control required" value="">
                    </div>
                </div> --}}

                <div class="form-group col-md-12">                   
                    <div class="input-group">
                        <button id="btnStoreEncuesta" type="submit" class="btn btn-primary">
                            Crear encuesta
                        </button>
                    </div>
                </div>
            </div>

        </form>
    @endslot
@endcomponent
<!-- /modal -->
