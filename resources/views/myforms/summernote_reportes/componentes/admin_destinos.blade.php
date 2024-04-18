<form id="myFormAsigReporte">
    <div class="row">
        <div class="col-md-4">
            <label>Seleccione una categoria</label>

            <select name="tabla_destino" required class="form-control select_reportes" id="">
                <option value="">Seleccione...</option>

                @forelse($types_categories_report as $key => $types_categorie)
                    <option value="{{ $key }}">{{ $types_categorie }}</option>
                @empty
                    <option value="">Sin categoria</option>
                @endforelse

            </select>
            <input type="hidden" value="1" name="status_id">
            <label for="status_id">Estado donde mirar el formato</label>
            <select name="status_id" required class="form-control buscar_asignacion_re">
                <option value="">Primero seleccione una categoria...</option>
                @foreach ($types_status as $key => $type_status)
                    <option value="{{ $key }}">{{ $type_status }}</option>
                @endforeach
            </select>
            <label style="display: none" for="categoria">Cuando aplicar el formato <small><i>(enviar correo
                        electrónico)</i></small></label>
            <select style="display: none" name="categoria" required class="form-control buscar_asignacion_re">
                <option value="">Seleccione el mensaje</option>
                <option value="mensaje_radicado">Cuando se radique la conciliación</option>
                <option value="mensaje_sol_conciliador">Cuando se asigne a conciliador</option>
                <option value="mensaje_sol_asistente">Cuando se asigne a asistente</option>             
                <option value="mensaje_notificarse">Cuando el conciliador o asistente se notifique para Aceptar</option>
                <option value="mensaje_notificarse_cancelar">Cuando el conciliador o asistente se notifique para No Aceptar</option>
                <option value="mensaje_rec_conciliador">Cuando acepta el conciliador (email de recomendaciones)
                </option>
                <option value="mensaje_rec_asistente">Cuando acepta el asistente (email de recomendaciones)</option>
                            </select>



        </div>
        <div class="col-md-6">
            @if (isset($reportes))
                <h3>Seleccionar formatos</h3>
                <ul id="checks_reportes">
                    {{-- @forelse($reportes as $key => $reporte)
        <li>
            <input class="checks_reportes" type="checkbox" id="chk_reporte_{{$reporte->id}}" value="{{$reporte->id}}" name="reporte_id[]" > {{$reporte->nombre_reporte}}
        </li>                     
         @empty
       <label> Sin formatos</label> 
         @endforelse   --}}
                </ul>
            @endif
        </div>
    </div>
    @if(!currentUser()->hasRole('visitante_conciliacion'))
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </div>
    @endif
</form>
