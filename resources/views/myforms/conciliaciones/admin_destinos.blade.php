<form id="myFormAsigReporte">
<div class="row">
    <div class="col-md-4">
     <label>Seleccione una categoria</label>
   
     <select name="tabla_destino" required class="form-control select_reportes" id="">
        <option value="">Seleccione...</option>            
       
        @forelse($types_categories_report as $key => $types_categorie)
            <option value="{{$key}}">{{$types_categorie}}</option>
        @empty
        <option value="">Sin categoria</option>
        @endforelse  
     </select> 
     <label for="status_id">Estado donde mirar el formato</label>
     <select name="status_id" required class="form-control select">
        <option value="">Seleccione...</option>
        @foreach($types_status as $key => $type_status)
        <option value="{{$key}}">{{$type_status}}</option>
        @endforeach
    </select>
    <label for="categoria">Mensaje donde aplicar el formato</label>
     <select style="display: none"  name="categoria" required class="form-control select">
         <option value="">Seleccione el mensaje</option>
         <option value="mensaje_sol_conciliador">Mensaje para email de solicitud a conciliador</option>
         <option value="mensaje_sol_asistente">Mensaje para email de solicitud a asistente</option>
         <option value="mensaje_radicado">Mensaje para email radicado</option>
         <option value="mensaje_rec_conciliador">Mensaje para email de recomendaciones conciliadores</option>
         <option value="mensaje_rec_asistente">Mensaje para email de recomendaciones asistentes</option>
         <option value="mensaje_notificarse">Mensaje para email de notificarse (Aceptar)</option>
         <option value="mensaje_notificarse_cancelar">Mensaje para email de notificarse (No Aceptar)</option>
     </select>

     

    </div>
     <div class="col-md-4">
     @if(isset($reportes))
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
<div class="row">
<div class="col-md-12">
    <button class="btn btn-primary" type="submit">Guardar</button>
</div>
</div>
</form>