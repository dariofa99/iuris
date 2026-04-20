{{--   <div class="row justify-content-center">
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
            </div> --}}
 <div class="row">

      <div class="col-md-6">
          <div class="card">
              <div class="card-header border-0">
                  <h3 class="card-title">Encuestas</h3>
                  <div class="card-tools">
                      <a href="#" id="btnCreateEncuesta" class="btn btn-tool btn-sm">
                          <i class="fas fa-plus">
                              Nueva encuesta
                          </i>
                      </a>

                  </div>
              </div>
              <div class="card-body table-responsive p-0" id="tblListaEncuestas">
                 @include('myforms.encuestas.conciliaciones.encuestas_list_ajax')  
              </div>
          </div>
      </div>

      <div class="col-md-6">
          <div class="card">
              <div class="card-header border-0">
                  <h3 class="card-title">
                    Preguntas: <span id="lblTestName"></span>
                </h3>
                  <div class="card-tools">
                    
                      <a style="display: none"  id="btn_new_categoryInExp" href="#" class="btn btn-tool btn-sm">
                          
                              Nueva Pregunta
                         
                      </a>

                      <a style="display: none"  id="btn_load_categoryInExp" href="#" class="btn btn-tool btn-sm">
                        Cargar Pregunta
                    </a>

                  </div>
              </div>
              <div class="card-body">
                  {{-- <button id="btn_new_categoryInExp" class="btn btn-sm btn-primary">
                      <i class="fas fa-plus"></i> Adicionar pregunta
                  </button> --}}
                  <div class="co" id="sortable_questions">
                       
                       {{-- @include('myforms.encuestas.expedientes.preguntas_form')  --}}
                  </div>
              </div>
          </div>

      </div>
  </div>
