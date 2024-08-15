  <div class="row justify-content-center">
    <div class="col-md-2">
        <button id="btn_new_categoryInExp" class="btn btn-sm btn-primary">
          <i class="fas fa-plus"></i>  Adicionar pregunta
        </button>
    </div>
                <div class="col-md-6 " id="sortable">
                    @include('myforms.categorias.refs_aditional_data', [
                        'data' => getReferencesDataBySection('personalizado', 'exp_encuesta_satisf'),
                        'col' => 12,
                        'habDelete'=>true,
                        'item_sortable'=>"item_sortable",
                        'design' => 'card_question',
                    ])
                </div>
            </div>