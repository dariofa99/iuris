<div class="row">
    <div class="col-md-9">
        <form id="{{ $myForm }}" method="POST">
            <input name="reporte" type="hidden">
            @if (isset($conciliacion))
                <input name="conciliacion_id" value="{{ $conciliacion->id }}" type="hidden">
                <input name="status_id" value="{{ isset($estado) ? $estado : $conciliacion->status_id }}" type="hidden">
            @endif
            <input name="report_keys" id="report_keys" value="" type="hidden">
            <div class="row">
                <div class="col-md-5">
                    <label> Seleccione una categoria </label>
                    <div class="form-group">
                        <select required name="categoria_id" id="categoria_id"
                            class="form-control form-control-sm required"
                            @if ($view and $view == 'update_temp') disabled @endif>
                            <option value="">Seleccione...</option>
                            @forelse($types_categories_report as $key => $types_categorie)
                                <option
                                    {{ (isset($reporte) and $reporte != null and $reporte->categoria_id == $key) ? 'selected' : '' }}
                                    value="{{ $key }}">{{ $types_categorie }}</option>
                            @empty
                                <option value="">Sin categoria</option>
                            @endforelse
                        </select>
                    </div>
                </div>

                <div class="col-md-5">
                    @if ($view and $view == 'update')
                        <label>Seleccionar formato</label>
                        <select name="id" id="sel_reporte_id" required
                            class="form-control form-control-sm required">
                            <option value="">Primero seleccione una categoria...</option>
                        </select>
                    @endif

                    @if ($view and $view == 'update_temp')
                        <div id="cont_temp">
                            @if ($reporte->is_temp)
                                <input type="hidden" name="is_temp" value="{{ $reporte->id }}">
                                <input type="hidden" name="id" value="{{ $reporte->pdf_reporte_id }}">
                            @else
                                <input type="hidden" name="id" value="{{ $reporte->id }}">
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div
                @if ($view and $view == 'update') style="border-top: 1px solid rgb(222, 220, 220);margin-top:2px" @endif>
                <div class="row">
                    <div class="col-md-6">
                        <label> {{ ($view and $view == 'update') ? 'Cambiar' : '' }} Nombre del formato
                        </label>
                        <input type="text" required class="form-control form-control-sm required"
                            name="nombre_reporte" @if ($view and $view == 'update_temp') disabled @endif
                            @if ($view and $view == 'update_temp' and $reporte) value="{{ $reporte->nombre_reporte }}" @endif>

                    </div>

                    @if ($view and $view == 'update')
                        <div class="col-md-6">
                            <label> Cambiar categoria </label>
                            <select name="categorianew_id" id="categorianew_id"
                                class="form-control required form-control-sm">
                                <option value="">Primero seleccione un formato ...</option>
                                @forelse($types_categories_report as $key => $types_categorie)
                                    <option value="{{ $key }}">{{ $types_categorie }}</option>
                                @empty
                                    <option value="">Sin formato</option>
                                @endforelse
                            </select>
                        </div>
                    @endif

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="content_s">

                            <div id="{{ $mySummernote }}" class="summernote">
                                @if (isset($reporte) and $view and $view == 'update_temp')
                                    {!! $reporte->reporte !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">
                    <br>

                    @if ($view and $view != 'update_temp' and !currentUser()->hasRole('visitante_conciliacion'))

                        <button type="submit" class="btn btn-primary btn-sm" style="margin: 2px"><i class="fa fa-save">
                            </i>
                            @if ($view and $view == 'store')
                                Guardar
                            @elseif($view == 'update')
                                Actualizar
                            @endif
                        </button>
                    @endif
                    @if ($view and $view == 'update_temp' and !currentUser()->hasRole('visitante_conciliacion'))
                        <button type="button" id="btnGuardarPdfTemp" class="btn btn-success btn-sm"
                            style="margin: 2px"><i class="fa fa-save"> </i>
                            Guardar cambios
                        </button>
                        <button type="button" id="btnCancelPdfTemp" class="btn btn-default btn-sm"
                            style="margin: 2px"><i class="fa fa-close"> </i>
                            Cerrar
                        </button>
                    @endif

                    @if ($view == 'update' and !currentUser()->hasRole('visitante_conciliacion'))
                        <button type="button" @if (isset($reporte) and !$reporte->is_temp and $view == 'update_temp') style="display:none" @endif
                            id="btnDeletePdfTemp" class="btn btn-danger btn-sm" style="margin: 2px"><i
                                class="fa fa-trash"> </i>
                            Eliminar
                        </button>
                    @endif


                </div>
                <div class="col-md-5">
                    <br>
                    <div class="input-group pull-right">
                        <span class="input-group-addon bg-orange" id="basic-addon1">
                            <a href="#" style="color: black" data-summernote="{{ $mySummernote }}"
                                data-form="{{ $myForm }}" id="{{ $myForm }}"
                                class="btn_generate_pdf_preview" style="margin: 2px">
                                <i class="fa fa-file-pdf-o"> </i>
                                Vista previa</a>

                        </span>
                        <select required name="tipo_papel" class="form-control ">
                            <option @if (isset($reporte) and $reporte->getConfig()->tipo_papel == 'a4') selected @endif value="a4">Tamaño
                                Carta
                            </option>
                            <option @if (isset($reporte) and $reporte->getConfig()->tipo_papel == 'a3') selected @endif value="a3">Tamaño
                                Oficio
                            </option>
                        </select>

                    </div>
                    <a href="#" data-modal="myModal_configuraciones_pdf_{{ $view }}"
                        class="but_mar selec_confi_av">Margenes</a>
                    <a href="#" data-modal="myModal_configuraciones_formato_pdf_{{ $view }}"
                        class="but_mar selec_confi_av">Formato</a>
                </div>
            </div>
            @include('myforms.conciliaciones.componentes.modal_configuraciones_pdf', [
                'id' => 'myModal_configuraciones_pdf_' . $view,
                'configuraciones' => (isset($reporte) and $reporte != null) ? $reporte->getConfig() : false,
            ])

            @include('myforms.conciliaciones.componentes.modal_configuraciones_formato_pdf', [
                'id' => 'myModal_configuraciones_formato_pdf_' . $view,
                'config_encab' =>
                    (isset($reporte) and $reporte != null) ? $reporte->getPdfConfig('encabezado') : null,
                'config_pie' => (isset($reporte) and $reporte != null) ? $reporte->getPdfConfig('pie') : null,
            ])

        </form>



    </div>
    <div class="col-md-3" id="inputs">


        <div class="my-fixed-item" id="my-fixed-item">
            <div class="row">
                <div class="col-md-12">
                    <select id="select_values_{{ $view }}" data-view="{{ $view }}"
                        class="form-control select_values">

                        <option value="solicitante_{{ $view }}">Parte solicitante</option>
                        <option value="rep_legal_solicitante_{{ $view }}">Rep. Legal parte
                            solicitante
                        </option>
                        <option value="apoderado_solicitante_{{ $view }}">Apoderado parte
                            solicitante</option>

                        <option value="apoderado_solicitada_{{ $view }}">Apoderado parte
                            solicitada</option>

                        <option value="solicitada_{{ $view }}">Parte solicitada</option>
                        <option value="conciliador_{{ $view }}">Conciliador</option>
                        <option value="asistente_{{ $view }}">Asistente</option>
                        <option value="rep_legal_solicitada_{{ $view }}">Rep. Legal parte
                            solicitada</option>
                        {{--  <option value="hechos_pretensiones_{{ $view }}">Hechos - Pretensiones -
                            Acuerdos</option>
                        <option value="audiencia_{{ $view }}">Audiencia</option> --}}

                        <option value="info_conciliacion_{{ $view }}">Información de conciliación
                        </option>
                        <option value="personalizado_{{ $view }}">Personalizado</option>
                    </select>

                </div>
            </div>
            <div class="contenedor_inputs">
                <div class="content_values_{{ $view }}" style="display: block"
                    id="solicitante_{{ $view }}">
                    @include('myforms.summernote_reportes.componentes.reportes_values', [
                        'tipo_usuario_id' => 205,
                        'parte' => 'solicitante',
                        'view' => 'user_values',
                        'mySummernote' => $mySummernote,
                    ])
                </div>

                <div class="content_values_{{ $view }}" style="display: none"
                    id="conciliador_{{ $view }}">
                    @include('myforms.summernote_reportes.componentes.reportes_values', [
                        'tipo_usuario_id' => 203,
                        'parte' => 'conciliador',
                        'view' => 'user_values',
                        'mySummernote' => $mySummernote,
                    ])
                </div>

                <div class="content_values_{{ $view }}" style="display: none"
                    id="asistente_{{ $view }}">
                    @include('myforms.summernote_reportes.componentes.reportes_values', [
                        'tipo_usuario_id' => 204,
                        'parte' => 'asistente',
                        'view' => 'user_values',
                        'mySummernote' => $mySummernote,
                    ])
                </div>

                <div class="content_values_{{ $view }}" style="display: none"
                    id="rep_legal_solicitante_{{ $view }}">
                    @include('myforms.summernote_reportes.componentes.reportes_values', [
                        'tipo_usuario_id' => 195,
                        'parte' => 'rep_legal_solicitante',
                        'view' => 'user_values',
                        'mySummernote' => $mySummernote,
                        //'section'=>'rep_legal_solicitante'
                    ])
                </div>
                <div class="content_values_{{ $view }}" style="display: none"
                    id="apoderado_solicitante_{{ $view }}">
                    @include('myforms.summernote_reportes.componentes.reportes_values', [
                        'tipo_usuario_id' => 196,
                        'parte' => 'apoderado_solicitante',
                        'view' => 'user_values',
                        'mySummernote' => $mySummernote,
                    ])
                </div>

                <div class="content_values_{{ $view }}" style="display: none"
                    id="apoderado_solicitada_{{ $view }}">
                    @include('myforms.summernote_reportes.componentes.reportes_values', [
                        'tipo_usuario_id' => 196,
                        'parte' => 'apoderado_solicitada',
                        'view' => 'user_values',
                        'mySummernote' => $mySummernote,
                    ])
                </div>

                <div class="content_values_{{ $view }}" style="display: none"
                    id="solicitada_{{ $view }}">
                    @include('myforms.summernote_reportes.componentes.reportes_values', [
                        'tipo_usuario_id' => 197,
                        'parte' => 'solicitada',
                        'view' => 'user_values',
                        'mySummernote' => $mySummernote,
                    ])
                </div>

                <div class="content_values_{{ $view }}" style="display: none"
                    id="rep_legal_solicitada_{{ $view }}">
                    @include('myforms.summernote_reportes.componentes.reportes_values', [
                        'tipo_usuario_id' => 198,
                        'parte' => 'rep_legal_solicitada',
                        'view' => 'user_values',
                        'mySummernote' => $mySummernote,
                    ])
                </div>

                <div class="content_values_{{ $view }}" style="display: none;margin-top:3px"
                    id="personalizado_{{ $view }}">
                    @if ($view != 'update_temp')
                        <div class="col-md-12 mb-2">
                            <button data-summernote="{{ $mySummernote }}" class="btn btn-success"
                                id="btn_create_category">+</button>
                        </div>
                    @endif
                    <div class="col-md-12" id="content_categories_ajax" class="content_categories_ajax">
                        @include('myforms.summernote_reportes.componentes.categories_ajax', [
                            'categories_report' => getReferencesDataBySection('personalizado', 'pdf_reportes'),
                            'mySummernote' => $mySummernote,
                            'user_type' => 'pdfrep_aditional_data',
                            'parte' => 'personalizado',
                            'model' => 'conciliaciones',
                        ])
                    </div>
                </div>

                <div class="content_values_{{ $view }}" style="display: none;margin-top:3px"
                    id="info_conciliacion_{{ $view }}">
                    @include('myforms.summernote_reportes.componentes.reportes_values', [
                        'tipo_usuario_id' => 'conciliacion',
                        'parte' => 'conciliaciones',
                        'view' => 'conciliaciones_values',
                        'mySummernote' => $mySummernote,
                    ])
                </div>
            </div>


        </div>



    </div>
</div>
