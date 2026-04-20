@component('components.b4.modal_large')
    @slot('trigger')
        myModal_encuesta_add_preguntas
    @endslot

    @slot('title')
        <label id="lbl_title_fract">
            Creando encuesta
        </label>
    @endslot


    @slot('body')
        <div class="row">
            <div class="col-md-12">
                Seleccione las preguntas que desea agregar
            </div>
        </div>
        <form id="myFormAddPreguntasEncuestas">

     
        <div class="row list_preguntas_add_test" id="list_preguntas_add_test">


           
        </div>
        <div class="row justify-content-center mt-3">


            <div class="form-group col-md-12">
                <div class="input-group">
                    <button type="submit" class="btn btn-primary">
                        Agregar preguntas a la encuesta
                    </button>
                </div>
            </div>
        </div>
    </form>
    @endslot
@endcomponent
<!-- /modal -->
