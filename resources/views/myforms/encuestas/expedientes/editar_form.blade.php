  <div class="row">

      <div class="col-md-5">
          <div class="card">
              <div class="card-header border-0">
                  <h3 class="card-title">Encuestas</h3>
                  <div class="card-tools">
                      <a href="#" class="btn btn-tool btn-sm">
                          <i class="fas fa-plus">
                              Nueva encuesta
                          </i>
                      </a>

                  </div>
              </div>
              <div class="card-body table-responsive p-0">
                  <table class="table table-striped table-valign-middle">
                      <thead>
                          <tr>
                              <th>Nombre</th>
                              <th>Versión</th>
                              <th>Código</th>
                              <th>Fecha vigencia</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($admin_encuestas as $key => $encuesta)
                              <tr>
                                  <td>
                                      {{ $encuesta->nombre }}
                                  </td>
                                  <td> {{ $encuesta->version }}</td>
                                  <td>
                                      {{ $encuesta->codigo }}
                                  </td>
                                  <td>
                                      {{ $encuesta->fecha_vigencia }}
                                  </td>
                              </tr>
                          @endforeach


                      </tbody>
                  </table>
              </div>
          </div>
      </div>

      <div class="col-md-6">
          <div class="card">
              <div class="card-header border-0">
                  <h3 class="card-title">Preguntas de la encuesta</h3>
                  <div class="card-tools">
                      <a id="btn_new_categoryInExp" href="#" class="btn btn-tool btn-sm">
                          <i class="fas fa-plus">
                              Nueva Pregunta
                          </i>
                      </a>

                  </div>
              </div>
              <div class="card-body">
                  {{-- <button id="btn_new_categoryInExp" class="btn btn-sm btn-primary">
                      <i class="fas fa-plus"></i> Adicionar pregunta
                  </button> --}}
                  <div class="co" id="sortable">
                       
                       @include('myforms.categorias.refs_aditional_data', [
                          'data' => $encuesta->preguntas,
                          'col' => 12,
                          'habDelete' => true,
                          'item_sortable' => 'item_sortable',
                          'design' => 'card_question',
                      ]) 
                  </div>
              </div>
          </div>

      </div>
  </div>
