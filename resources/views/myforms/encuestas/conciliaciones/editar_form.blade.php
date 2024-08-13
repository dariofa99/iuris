  <div class="row justify-content-center">
    <div class="col-md-2">
        <button id="btn_new_category" class="btn btn-primary">
            Adicionar pregunta
        </button>
    </div>
                <div class="col-md-6">
                    @include('myforms.categorias.refs_aditional_data', [
                        'data' => getReferencesDataBySection('personalizado', 'conc_encuesta_satisf'),
                        'col' => 12,
                        'habDelete'=>true,
                        'design' => 'card_question',
                    ])
                </div>
            </div>