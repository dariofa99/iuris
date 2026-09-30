@component('components.b4.modal_large')
    @slot('trigger')
        myModal_act_edit_docen
    @endslot

    @slot('title')
        Editar docente
    @endslot

    @slot('body')
        @section('msg-contenido')
            Registrado
        @endsection
        @include('msg.ajax.success')

        <form method="POST" id="myform_act_edit_docente" enctype="multipart/form-data" class="act-form act-edit-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
            <input type="hidden" name="idact" id="idact">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="actexpid">Código expediente</label>
                        <input type="text" name="actexpid" id="actexpid" class="form-control"
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

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="actnombre_cr">Actuación</label>
                        <input type="text" name="actnombre" id="actnombre_cr" class="form-control required" maxlength="225"
                            readonly>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="actdescrip">Descripción</label>
                        <textarea name="actdescrip" id="actdescrip" class="form-control required" maxlength="225" rows="4" readonly></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="actestado">Estado de la actuación</label>
                        <select name="actestado_id" id="actestado" class="form-control required" required>
                            <option value="">Selecciona...</option>
                            <option value="102">Realizar correcciones</option>
                            <option value="104">Aprobar</option>
                            <option value="234">Anular</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="actdocnomgen">Subir archivo</label>
                        <input type="file" name="actdocnomgen" id="actdocnomgen" class="input form-control">

                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="fecha_limit_doc">Fecha límite de entrega</label>
                        <input required type="date" name="fecha_limit_doc" id="fecha_limit_doc" class="form-control required"
                            maxlength="225" min="{{ date('Y-m-d') }}" disabled>
                    </div>
                    <p id="error-message" class="act-edit-error" style="display: none;">La fecha debe ser superior al día
                        actual.</p>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="actdocenrecomendac">Recomendación</label>
                        <textarea required name="actdocenrecomendac" id="actdocenrecomendac" class="form-control required" maxlength="10000"
                            rows="4"></textarea>
                    </div>
                </div>

                <div id="formAddNotas" class="addNotasAct row act-grade-panel" style="display: none">
                    @if ($segmento and $periodo)
                        @if ($segmento->fecha_fin >= date('Y-m-d'))
                            <div class="col-md-12 iuris-section-heading">
                                <h4><i class="fa fa-graduation-cap" aria-hidden="true"></i> Registro de calificación</h4>
                            </div>
                            <input required disabled type="hidden" name="orgntsid" value="2" class="form-control required"
                                id="orgntsid">
                            <input type="hidden" name="tpntid" value="1" class="form-control required" id="tpntid">
                            @if ($periodo and $segmento)
                                <input type="hidden" name="segid" value="{{ $segmento->id }}"
                                    class="form-control required" id="segid">
                                <input type="hidden" name="perid" value="{{ $periodo->id }}"
                                    class="form-control required" id="perid">
                            @endif
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ntaconocimiento">Nota conocimiento</label>
                                    <input type="text" name="ntaconocimiento" id="ntaconocimiento"
                                        class="form-control required" data-inputmask="'mask': ['9.9']" data-mask=""
                                        required disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ntaaplicacion">Nota aplicación</label>
                                    <input type="text" name="ntaaplicacion" id="ntaaplicacion"
                                        class="form-control required" data-inputmask="'mask': ['9.9']" data-mask=""
                                        required disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ntaetica">Nota ética</label>
                                    <input type="text" name="ntaetica" id="ntaetica" class="form-control required"
                                        data-inputmask="'mask': '9.9'" data-mask="" required disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="ntaconcepto">Concepto de la nota</label>
                                    <textarea required disabled name="ntaconcepto" id="ntaconcepto" class="form-control required" maxlength="100000"
                                        rows="4"></textarea>
                                </div>
                            </div>
                        @else
                            <div class="col-md-12">
                                <div class="alert alert-warning">No se puede evaluar si no hay un corte activo.</div>
                            </div>
                        @endif
                    @else
                        <div class="col-md-12">
                            <div class="alert alert-warning">No hay un periodo o corte activo.</div>
                        </div>
                    @endif
                </div>

                @if ($periodo and $segmento)
                    <div class="col-md-12 act-form-actions">
                        <button id="btn_act_edit_docen" type="button" class="btn-act-create">
                            <i class="fa fa-save" aria-hidden="true"></i>
                            Actualizar
                        </button>
                    </div>
                @else
                    <div class="col-md-12">
                        <div class="alert alert-warning">No existe un periodo o un segmento activo.</div>
                    </div>
                @endif
            </div>
        </form>

    

    @endslot
@endcomponent
<!-- /modal -->
