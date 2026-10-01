@component('components.b4.modal_large')
    @slot('trigger')
        myModal_act_details
    @endslot

    @slot('title')
        Detalles
    @endslot

    @slot('body')
        <form method="POST" id="myform_act_edit_docen" enctype="multipart/form-data" class="act-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
            @section('msg-contenido')
                Registrado
            @endsection
            @include('msg.ajax.success')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="actexpid_det">Código expediente</label>
                        <input type="text" name="actexpid_det" id="actexpid_det" class="form-control"
                            value="{{ $expediente->expid }}" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="actfecha">Fecha de creación</label>
                        <div class="act-date-field">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            <input type="text" name="actfecha" id="actfecha" class="form-control" required
                                data-inputmask="'alias': 'yyyy/mm/dd'" data-mask readonly>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fullnameest">Creado por</label>
                        <input type="text" name="fullnameest" id="fullnameest" class="form-control required" maxlength="225"
                            readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="actnombre_det">Título</label>
                        <input type="text" name="actnombre_det" id="actnombre_det" class="form-control required"
                            maxlength="225" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fecha_limit_d">Fecha límite de entrega</label>
                        <input type="date" name="fecha_limit_d" id="fecha_limit_d" class="form-control required"
                            maxlength="225" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="actestado_det">Estado de la actuación</label>
                        <select name="actestado_det" id="actestado_det" class="form-control" required disabled>
                            <option value="">Selecciona...</option>
                            @foreach ($act_estados as $estadoId => $estadoNombre)
                                <option value="{{ $estadoId }}">{{ $estadoNombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="actdescrip_det">Descripción</label>
                        <textarea name="actdescrip_det" id="actdescrip_det" class="form-control required" maxlength="225" rows="4"
                            readonly></textarea>
                    </div>
                </div>



                <div id="datos_docente" class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="iuris-info-box">
                                <div class="iuris-info-box-title"><i class="fa fa-file" aria-hidden="true"></i> Archivo
                                    estudiante</div>
                                <div class="iuris-info-box-text"><small id="lab-nombre-est">Nombre del archivo</small></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="iuris-info-box">
                                <div class="iuris-info-box-title"><i class="fa fa-file" aria-hidden="true"></i> Archivo docente
                                </div>
                                <div class="iuris-info-box-text"><small id="lab-nombre-doc">Nombre del archivo</small></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="actdocenrecomendac_det">Recomendación</label>
                                <textarea name="actdocenrecomendac_det" id="actdocenrecomendac_det" class="form-control required" maxlength="10000"
                                    rows="4" disabled></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Última actualización hecha por
                            <span id="label_nombre_docente" class="iuris-detail-title">Nombre
                                del docente</span>
                        </label>

                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row" id="cont_notas_ac" style="display: none;">
                        <div class="col-md-12 iuris-section-heading">
                            <h4>Notas</h4>
                            
                                    <span id="lbl_not_tipo" class="iuris-total">d</span>
                                
                        </div>
                        <input type="hidden" id="actuacion_id">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nota conocimiento</label>
                                <span id="lbl_not_conac" class="iuris-total">-</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nota aplicación</label>
                                <span id="lbl_not_aplac" class="iuris-total">-</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nota ética</label>
                                <span id="lbl_not_etiac" class="iuris-total">-</span>
                            </div>
                        </div>


                        @if ($segmento)
                            <div class="col-md-3">
                                <input type="hidden" value="{{ $segmento->id }}" id="segmento_id">
                                <button type="button" id="btn_cam_nt_act" class="btn-iuris-primary">Cambiar notas</button>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ntaconcepto_text">Concepto</label>
                                <textarea name="ntaconcepto" id="ntaconcepto_text" class="form-control required" maxlength="225" rows="3"
                                    disabled></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Evaluado por <i id="lbldocevname">user</i></label>

                        </div>
                    </div>
                </div>
            </div>
        </form>


        <form action="{{ url('/notas/update') }}" method="POST" id="myform_update_notas" class="act-form" display="none">
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
                                        <small>La calificación aún puede ser modificada. No se tendrá en cuenta para el reporte
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
                                    <button type="button" class="btn btn-outline-secondary" id="btn_cancelar_notas">
                                        <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                                    </button>
                                    @if ($expediente->expestado_id == '2')
                                        <button type="button" class="btn btn-outline-info" id="btn_cambiar_notas">
                                            <i class="fa fa-refresh" aria-hidden="true"></i> Cambiar notas
                                        </button>
                                    @endif
                                    @if ($expediente->expestado_id == '4')
                                        <button type="button" class="btn btn-outline-warning" data-value=""
                                            id="btn_tipo_update">
                                            Cambiar notas a:
                                        </button>
                                    @endif
                                    @if ($expediente->expestado_id == '2')
                                        <button type="button" class="btn btn-outline-danger" id="btn_delete_notas">
                                            <i class="fa fa-trash" aria-hidden="true"></i> Eliminar notas
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
                </div>
            </div>
        </form>
    @endslot
@endcomponent
<!-- /modal -->
