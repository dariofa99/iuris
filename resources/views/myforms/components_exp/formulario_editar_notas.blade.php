  <form action="{{ url('/notas/update') }}" method="POST" class="myform_update_notas " id="{{ $id }}"
      display="none">
      @csrf
      <input type="text" style="display: none" value="{{ $expediente->id }}" name="exp_id">
      <input type="text" style="display: none" value="" name="origen" id="origen">
      <input type="text" style="display: none" value="" name="tbl_org_id" id="up_tbl_org_id">
      {{-- <input type="text" style="display: none" value="" disabled name="tipo_nota_id" id="tipo_nota_id"> --}}

      <div class="iuris-form-card">
          <div class="iuris-form-header">
              <div class="iuris-form-title">
                  <span class="iuris-form-icon"><i class="fa fa-graduation-cap" aria-hidden="true"></i></span>
                  <div>
                      <h5>Calificación</h5>
                      <small>Evaluado por: <i id="lbldocevnameD"></i></small>
                  </div>
              </div>
          </div>

          <div class="iuris-form-body">

              <div class=" row iuris-detail-header">
                  <div class="col-md-6 col-xs-12">
                      <small>Periodo</small>
                      <div class="iuris-detail-title" id="lbl_periodo"></div>
                  </div>
                  {{-- <div class="col-4">
                                <small>Corte</small>
                                <div class="iuris-detail-title" id="lbl_segmento"></div>
                            </div> --}}
                  <div class="col-4">
                      <small>Tipo de nota</small>
                      <div class="iuris-badge" id="lbl_tipo">Parcial</div>
                  </div>
              </div>


              <div class="iuris-table-wrapper">
                  <table id="tbl_cierre_cas" class="table iuris-detail-table">
                      <thead>
                          <tr>
                              <th scope="col">Componente</th>
                              <th scope="col">Calificación</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr class="fil_nt_co">
                              <th scope="row">Nota conocimiento</th>
                              <td>
                                  <div class="input-group">
                                      <input type="text" class="form-control notat" disabled id="nota_conocimiento"
                                          name="nota[]" value="" data-inputmask="'mask': ['9.9']" data-mask>
                                      <input type="text" class="form-control notat" style="display: none" disabled
                                          name="nota_id[]" id="nota_conocimientoid" value="">
                                  </div>
                              </td>
                          </tr>
                          <tr class="fil_nt_co">
                              <th scope="row">Nota aplicación</th>
                              <td>
                                  <div class="input-group">
                                      <input type="text" class="form-control notat" disabled id="nota_aplicacion"
                                          name="nota[]" value="" data-inputmask="'mask': ['9.9']" data-mask>
                                      <input type="text" class="form-control notat" style="display: none" disabled
                                          id="nota_aplicacionid" name="nota_id[]" value="">
                                  </div>
                              </td>
                          </tr>
                          <tr>
                              <th scope="row">Nota ética</th>
                              <td>
                                  <div class="input-group">
                                      <input type="text" class="form-control notat" disabled id="nota_etica"
                                          name="nota[]" value="" data-inputmask="'mask': ['9.9']" data-mask>
                                      <input type="text" class="form-control notat" style="display: none" disabled
                                          id="nota_eticaid" name="nota_id[]" value="">
                                  </div>
                              </td>
                          </tr>
                          <tr>
                              <th scope="row">Concepto de las notas</th>
                              <td>
                                  <div class="input-group">
                                      <textarea name="nota[]" id="nota_concepto" disabled class="form-control notat"></textarea>
                                      <input type="text" style="display: none" class="form-control notat" disabled
                                          id="nota_conceptoid" name="nota_id[]" value="">
                                  </div>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>

              <div class="iuris-note-type-selector">
                  <div class="iuris-note-type-header">
                      <div class="iuris-note-type-icon">
                          <i class="fa fa-tag" aria-hidden="true"></i>
                      </div>
                      <div>
                          <strong>Estado de la calificación</strong>
                          <span>Seleccione el tipo de nota que desea registrar</span>
                      </div>
                  </div>

                  <div class="iuris-note-type-options">


                      <label class="iuris-note-type-option">
                          <input type="radio" name="tipo_nota_id" value="1" checked>

                          <span class="iuris-note-type-content">
                              <span class="iuris-note-type-radio"></span>

                              <span class="iuris-note-type-text">
                                  <strong>Definitiva</strong>
                                  <small>La calificación no puede ser modificada. Se tendrá en cuenta para el reporte
                                      definitivo.</small>
                              </span>
                          </span>
                      </label>
                      <label class="iuris-note-type-option">
                          <input type="radio" name="tipo_nota_id" value="2">

                          <span class="iuris-note-type-content">
                              <span class="iuris-note-type-radio"></span>

                              <span class="iuris-note-type-text">
                                  <strong>Provisional</strong>
                                  <small>La calificación aún puede ser modificada. No se tendrá en cuenta para el
                                      reporte
                                      definitivo.</small>
                              </span>
                          </span>
                      </label>


                  </div>
              </div>
              <div class="row" id="btns_edit_notas" style="display: block;">
                  @if (
                      $periodo and
                          $segmento and
                          $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                              currentUser()->hasRole('amatai') ||
                              currentUser()->hasRole('dirgral') ||
                              currentUser()->hasRole('diradmin'))
                      <div class="col-md-12">
                          <div class="iuris-form-footer">
                              <button type="submit" class="btn-iuris-primary" id="btn_update_notas">
                                  <i class="fa fa-save" aria-hidden="true"></i> Actualizar
                              </button>


                              @if ($expediente->expestado_id == '4')
                               {{--    <button type="button" class="btn btn-outline-danger" id="btn_delete_notas">
                                      <i class="fa fa-trash" aria-hidden="true"></i> Eliminar notas
                                  </button> --}}
                              @endif
                          </div>
                      </div>
                      <div class="col md-12 mt-3">
                          <button type="button" class="btn btn-outline-secondary btn_cancelar_notas"
                              id="btn_cancelar_notas">
                              <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                          </button>

                      </div>
                  @else
                      <div class="col-md-12">
                          <label>No tiene permisos para cambiar o eliminar las notas</label>
                      </div>
                  @endif
              </div>
          </div>
      </div>
  </form>
