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

        @include('myforms.components_exp.formulario_editar_notas', [
            'id' => 'myform_update_notas_act',
        ])
    @endslot
@endcomponent
<!-- /modal -->
