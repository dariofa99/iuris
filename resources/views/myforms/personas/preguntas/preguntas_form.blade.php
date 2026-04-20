@include('myforms.categorias.refs_aditional_data', [
                          'data' => $encuesta->preguntas,
                          'col' => 12,
                          'habDelete' => true,
                          'item_sortable' => 'item_sortable',
                          'design' => 'card_question',
                      ])  