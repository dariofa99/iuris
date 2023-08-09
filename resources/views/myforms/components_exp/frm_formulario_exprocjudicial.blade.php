<form id="{{ isset($procjudi) ? 'myFormDetallesProcJudicialExp' : 'myFormGestionProcJudicialExp'}}">
            <div class="row datos_genpj" id="row_estadoid">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="pj_estadoid">Estado</label>
                        <select class="form-control" name="estado_id" id="pj_estadoid">
                            <option value="">Seleccione</option>
                            @foreach ($est_projexp as $estado)
                                @if ($expediente->asignacion->estadosProcJudCount() >= 2 and $expediente->asignacion->procesojud_id == 246)
                                    @if ($estado->id == 243 || $estado->id == 247)
                                        <option value="{{ $estado->id }}">
                                            {{ $estado->ref_nombre }}
                                        </option>
                                    @endif
                                @else
                                    @if ($estado->id == 243 || $estado->id == 247 || $estado->id == 244)
                                        <option value="{{ $estado->id }}"> {{ $estado->ref_nombre }}</option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row datos_genpj" id="row_fechaauto" style="display: none">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="fecha_proj" id="lbl_fechaprocju">Fecha autoadmisorio</label>
                        <input type="date" class="form-control" id="fecha_proj" name="fecha" placeholder="Nombre">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="alert alert-info">
                        Recuerda que a partir de la fecha de inadmisión tienes 5 días hábiles
                        para subsabanar. <p>Fecha límite de entrega: <label><i id="lbl_fechaaproxprcj">
                            Esperando fecha de autoinadmisorio </i>
                            </label></p>
                    </div>
                </div>
            </div>
            <div class="row datos_genpj" id="row_fechahoaudiencia" style="display: none">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nombre">Fecha de audiencia</label>
                        <input type="date" class="form-control" id="proj_fecha_aud" name="fecha" placeholder="Nombre">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nombre">Hora de audiencia</label>
                        <input type="time" class="form-control" id="pj_hora" name="hora" placeholder="Nombre">
                    </div>
                </div>
            </div>
            <div class="row datos_genpj" id="row_fileproex">
                <div class="col-md-12">
                    <label for="nombre">Soporte <i><small>(PDF)</small></i></label>
                    <div class="form-group">
                        <input type="file" accept=".pdf" id="fileid" name="fileprocjud">
                    </div>
                </div>
            </div>

            <div class="row" id="row_comentarioproex">
                <div class="col-md-12">
                    <label for="nombre">Comentario</label>
                    <div class="form-group">
                        <textarea name="comentario" id="comentario_procjud" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm btn-block">
                            Enviar
                        </button>
                    </div>
                </div>
            </div>

        </form>