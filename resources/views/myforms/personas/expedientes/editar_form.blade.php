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
                @include('myforms.personas.expedientes.encuestas_list_ajax') 
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
