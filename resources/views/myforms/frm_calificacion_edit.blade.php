@component('components.b4.modal_medium')

    @slot('trigger')
        myModal_edit_notas
    @endslot

    @slot('title')
        Editando Notas:
    @endslot


    @slot('body')
        @php
            if (!isset($ajax)) {
                $nota_conocimiento = '0.0';
                $nota_etica = '0.0';
                $nota_aplicacion = '0.0';
                $nota_final = '0.0';
                $nota_conocimientoid = 0;
                $nota_eticaid = 0;
                $nota_aplicacionid = 0;
                $nota_concepto = 0;
                $nota_conceptoid = 0;
                if ($periodo and $segmento) {
                    /*  if(count($tabla->get_has_nota_final())>0){
                $nota_conocimiento = number_format($tabla->get_has_nota_final()['nota_conocimiento']['nota'],1,'.','.');
                $nota_conocimientoid = $tabla->get_has_nota_final()['nota_conocimiento']['id'];
                $nota_etica = number_format($tabla->get_has_nota_final()['nota_etica']['nota'],1,'.','.');
                $nota_eticaid = $tabla->get_has_nota_final()['nota_etica']['id'];
                $nota_aplicacion = number_format($tabla->get_has_nota_final()['nota_aplicacion']['nota'],1,'.','.');
                $nota_aplicacionid = $tabla->get_has_nota_final()['nota_aplicacion']['id'];
                $nota_concepto = ($tabla->get_has_nota_final()['nota_concepto']['nota']);
                $nota_conceptoid = $tabla->get_has_nota_final()['nota_concepto']['id'];
                $nota_final = number_format($tabla->get_has_nota_final()['nota_final']['nota'],1,'.','.');
            }elseif ($tabla->get_nota_corte('conocimiento')) {
                $nota_conocimiento = number_format($tabla->get_nota_corte('conocimiento')['nota'],1,'.','.');
                 $nota_conocimientoid = $tabla->get_nota_corte('conocimiento')['id'];
                 $nota_etica = number_format($tabla->get_nota_corte('etica')['nota'],1,'.','.');
                 $nota_eticaid = $tabla->get_nota_corte('etica')['id'];
                 $nota_aplicacion = number_format($tabla->get_nota_corte('aplicacion')['nota'],1,'.','.');
                 $nota_aplicacionid = $tabla->get_nota_corte('aplicacion')['id'];
                 $nota_concepto = ($tabla->get_nota_corte('concepto')['nota']);
                 $nota_conceptoid = $tabla->get_nota_corte('concepto')['id'];
                 $nota_final = number_format($tabla->get_nota_corte('final')['nota'],1,'.','.');
        
            } */
                }
            }
        @endphp
        <form action="{{ url('/notas/update') }}" method="POST" id="myform_update_notas" class="act-form">
        @csrf
        <input type="text" style="display:none" value="{{ $expediente->id }}" name="exp_id">
        <input type="text" style="display:none" value="" name="origen" id="origen">
        <input type="text" style="display:none" value="" name="tbl_org_id" id="tbl_org_id">
        <input type="text" style="display:none" value="" disabled name="tipo_nota_id" id="tipo_nota_id">
        <div class="iuris-form-card">
            <div class="iuris-form-header">
                <div class="iuris-form-title">
                    <span class="iuris-form-icon"><i class="fa fa-graduation-cap" aria-hidden="true"></i></span>
                    <div>
                        <h5>Edición de notas</h5>
                        <small>Evaluado por: <i id="lbldocevname"></i></small>
                    </div>
                </div>
            </div>
            <div class="iuris-form-body">
                @if ($periodo and $segmento)
                    <div class="iuris-detail-header">
                        <div>
                            <small>Período</small>
                            <div class="iuris-detail-title" id="lbl_periodo">{{ $periodo->prddes_periodo }}</div>
                        </div>
                        <div>
                            <small>Corte</small>
                            <div class="iuris-detail-title" id="lbl_segmento">{{ $segmento->segnombre }}</div>
                        </div>
                        <div>
                            <small>Tipo de nota</small>
                            <div class="iuris-badge" id="lbl_tipo">Parcial</div>
                        </div>
                    </div>
                @endif
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
                                <th>Nota Conocimiento</th>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control notat" disabled id="nota_conocimiento"
                                            name="nota[]" value="{{ $nota_conocimiento }}" data-inputmask="'mask': ['9.9']"
                                            data-mask>
                                        <input type="text" class="form-control notat" style="display:none" disabled
                                            name="nota_id[]" id="nota_conocimientoid" value="{{ $nota_conocimientoid }}">
                                    </div>
                                </td>
                            </tr>
                            <tr class="fil_nt_co">
                                <th>Nota Aplicación </th>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control notat" disabled id="nota_aplicacion"
                                            name="nota[]" value="{{ $nota_aplicacion }}" data-inputmask="'mask': ['9.9']"
                                            data-mask>
                                        <input type="text" style="display:none" class="form-control notat" disabled
                                            id="nota_aplicacionid" name="nota_id[]" value="{{ $nota_aplicacionid }}">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Nota Ética </th>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control notat" disabled id="nota_etica" name="nota[]"
                                            value="{{ $nota_etica }}" data-inputmask="'mask': ['9.9']" data-mask>
                                        <input type="text" style="display:none" class="form-control notat" disabled
                                            id="nota_eticaid" name="nota_id[]" value="{{ $nota_eticaid }}">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Concepto Notas </th>
                                <td>
                                    <div class="input-group">
                                        <textarea name="nota[]" id="nota_concepto" style="width: 100%" disabled class="form-control notat">{{ $nota_concepto }}</textarea>
                                        <input type="text" style="display:none" class="form-control notat" disabled
                                            id="nota_conceptoid" name="nota_id[]" value="{{ $nota_conceptoid }}">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {{--  Promedio Corte: <label id="lbl_nota_gen_caso">{{ $nota_final }}</label> --}}
            </div>
        </div>
        <div class="row" id="btns_edit_notas" style="display:none">
            @if ($periodo and
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
                        <button type="button" class="btn btn-outline-secondary" id="btn_cancelar_notas">
                            <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                        </button>

                    @if ($expediente->expestado_id == '2')
                        <button type="button" class="btn btn-outline-info" id="btn_cambiar_notas">
                            <i class="fa fa-refresh" aria-hidden="true"></i> Cambiar notas
                        </button>
                    @endif
                    @if ($expediente->expestado_id == '4')
                        <button type="button" class="btn btn-outline-warning" data-value="" id="btn_tipo_update">
                            Cambiar notas a:
                        </button>
                    @endif
                    @if ($expediente->expestado_id == '2')
                        <button type="button" class="btn btn-outline-danger" id="btn_delete_notas">
                            <i class="fa fa-trash" aria-hidden="true"></i> Eliminar las notas
                        </button>
                    @endif
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <label>No tiene permisos para cambiar o eliminar las notas</label>
                </div>
            @endif
        </div>

        </form>
    @endslot
@endcomponent
<!-- /modal -->
