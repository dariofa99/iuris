<form id="{{ isset($procjudi) ? 'myFormDetallesProcJudicialExp' : 'myFormGestionProcJudicialExp' }}">
    <div class="row datos_genpj" id="row_estadoid">
        <div class="col-md-12">
            <div class="form-group">
                <label for="pj_estadoid">Estado</label>
                <input type="text" disabled class="form-control" value="{{ $procjudi->estado->ref_nombre }}">
            </div>
        </div>
    </div>
    @if ($procjudi->estado_id == 244)
        <div class="row datos_genpj" id="row_fechaauto" style="display: block">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="fecha_proj" id="lbl_fechaprocju">Fecha
                        {{ $procjudi->estado_id == 244 ? 'autoinadmisorio' : 'autoadmisorio' }} </label>
                    <input type="date" disabled value="{{ $procjudi->fecha }}" class="form-control" id="fecha_proj"
                        name="fecha" placeholder="Nombre">
                </div>
            </div>
            <div class="col-md-12">
                <div class="alert alert-info">
                    Recuerda que a partir de la fecha de inadmisión tienes 5 días hábiles
                    para subsabanar. <p>Fecha límite de entrega: <label><i id="lbl_fechaaproxprcj">Esperando fecha</i>
                        </label></p>
                </div>
            </div>
        </div>
    @endif
    @if ($procjudi->estado_id == 243)
        <div class="row datos_genpj" id="row_fechahoaudiencia" style="display: block">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="nombre">Fecha de audiencia</label>
                    <input type="date" disabled value="{{ $procjudi->fecha }}" class="form-control"
                        id="proj_fecha_aud" name="fecha" placeholder="Nombre">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="nombre">Hora de audiencia</label>
                    <input type="time" disabled value="{{ $procjudi->hora }}" class="form-control" id="pj_hora"
                        name="hora" placeholder="Nombre">
                </div>
            </div>
        </div>
    @endif
    <div class="row datos_genpj" id="row_fileproex">
        <div class="col-md-12">
            <label for="nombre">Soporte <i><small></small></i></label><br>
            @forelse($procjudi->files as $key => $file)
                <a target="_blank" href="{{ route('file.download', $file->id) }}"> {{ $file->original_name }} </a> <br>
            @empty
                <label> Sin archivos</label>
            @endforelse
        </div>
    </div>
    {{--  <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-sm btn-block">
                    Enviar
                </button>
            </div>
        </div>
    </div> --}}

</form>
