  <div class="row">
      <div class="col-md-12">
          <div class="form-group item_value">
              <small data-model="conciliacion" data-table="conciliaciones" data-summernote="{{ $mySummernote }}" data-short_name="numero_radicado"
                  class="item_con" data-type="conciliacion" data-name="numero_radicado">
                  Número de radicado
              </small>
          </div>
          <div class="form-group item_value">
              <small data-model="conciliacion" data-table="conciliaciones" data-summernote="{{ $mySummernote }}"
                  data-short_name="fecha_hora_radicado" class="item_con" data-type="conciliacion"
                  data-name="fecha_hora_radicado">
                  Fecha y hora de radicado
              </small>
          </div>
          <div class="form-group item_value">
              <small data-model="audiencia" data-table="conciliacion_audiencias" data-summernote="{{ $mySummernote }}"
                  data-short_name="fecha_audiencia" class="item_con" data-type="general"
                  data-name="fecha_audiencia">Fecha y
                  hora de audiencia</small>
          </div>
      </div>
      <div class="col-md-12">
          <div class="form-group item_value">
              <small data-model="conciliacion" data-table="conc_hechos_preten" data-summernote="{{ $mySummernote }}" data-short_name="hechos"
                  class="item_con" data-type="hepr" data-name="hechos">Hechos</small>
          </div>
      </div>
      <div class="col-md-12">
          <div class="form-group item_value">
              <small data-model="conciliacion" data-table="conc_hechos_preten" data-summernote="{{ $mySummernote }}"
                  data-short_name="pretensiones" class="item_con" data-type="hepr"
                  data-name="pretensiones">Pretensiones</small>
          </div>
      </div>
      <div class="col-md-12">
          <div class="form-group item_value">
              <small data-model="conciliacion" data-table="conc_hechos_preten" data-summernote="{{ $mySummernote }}" data-short_name="acuerdos"
                  class="item_con" data-type="hepr" data-name="acuerdos">Acuerdos</small>
          </div>
      </div>
      @include('myforms.summernote_reportes.componentes.categories_ajax', [
          'categories_report' => getReferencesDataBySection('asunto', 'conciliaciones'),
          'required' => 'required',
          'model' => 'conciliacion',
          'user_type' => 'conc_aditional_data',
      ])
  </div>
