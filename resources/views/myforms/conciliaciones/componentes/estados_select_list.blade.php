
<select name="type_status_id" class="form-control" required>
    <option value="">Seleccione</option>


    @if($conciliacion->getUser(199)->hasRole('estudiante') || $conciliacion->getUser(199)->hasRole('amatai'))

    @if(currentUser()->hasRole('estudiante') || currentUser()->hasRole('amatai'))
    @if($conciliacion->estado_id==174 || $conciliacion->estado_id==176)
    <option value="175"> Enviar a revisión </option>
    @endif
    @endif

    @endif

    @if(auth()->user()->hasRole('docente') || currentUser()->hasRole('coord_centro_conciliacion')  || currentUser()->hasRole('diradmin') || currentUser()->hasRole('amatai'))
 
    @if($conciliacion->estado_id==175)
    <option value="176"> Corregir </option>
    <option  value="{{(currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai')) ? "178" : "177"}}"> 
        {{(currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai')) ? "Aprobar (radicar)" : "Aprobar (docente)"}} </option>
    @endif 
    @endif

    @if(auth()->user()->hasRole('estudiante') || currentUser()->hasRole('diradmin') || currentUser()->hasRole('amatai'))
    @if($conciliacion->estado_id==177)
    <option value="225"> Solicitar radicado </option>    
    @endif
    @endif

    


    @if(((currentUser()->hasRole('diradmin') ||  currentUser()->hasRole('amatai') ||  currentUser()->hasRole('secretaria'))))
    @if($conciliacion->estado_id==194 || $conciliacion->estado_id==225)
    <option value="178"> Radicar </option>
    <option value="228"> Solicitar correcciones </option>
    @endif
    @endif
 

    @if(((currentUser()->hasRole('diradmin') ||  currentUser()->hasRole('amatai') ||  currentUser()->hasRole('estudiante'))))
    @if($conciliacion->estado_id==228)
    <option value="225"> Solicitar radicado </option>  
    @endif    
    @endif
    

    @if(currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai'))
    
    
    @if($conciliacion->estado_id==178 || $conciliacion->estado_id==194)
   
    @if($conciliacion->estado_id!=194)
    <option value="179"> Inadmitida. Asunto no conciliable </option>
    @endif

    <option value="180"> Inadmitir-anular </option>

    @if($conciliacion->estado_id!=194) <option value="194"> Inadmitida Solicitud de correcciones </option>@endif
    @if($conciliacion->estado_id!=194) <option value="181"> Admitida </option>@endif
    @endif
    @endif

    @if($conciliacion->estado_id==181 || $conciliacion->estado_id==183 || $conciliacion->estado_id==190)
    @if(currentUserInConciliacion($conciliacion->id,['autor','solicitante']))
    <option value="182"> Solicitud de aplazamiento </option>
    <option value="184"> Solicitud de desistimiento </option>
    @endif
    @if($conciliacion->estado_id!=183)
    @if(currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai') ||
    currentUserInConciliacion($conciliacion->id,['conciliador','auxiliar']))
    <option value="183"> Suspendida </option>
    @endif
    @endif
   
    @if(currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai') ||
    currentUserInConciliacion($conciliacion->id,['conciliador','auxiliar']))
    <option value="185"> Acuerdo </option>
    <option value="186"> Acuerdo parcial </option>
    <option value="187"> No acuerdo </option>
    <option value="188">No acuerdo parcial </option>
    <option value="189">Informe no asistencia </option>
    @endif  
    @endif

    @if(currentUser()->hasRole('coord_centro_conciliacion'))
    @if($conciliacion->estado_id==182)
    <option value="190">Aplazada</option>
    @endif
    @if($conciliacion->estado_id==184)
    <option value="191">Informe de archivo desistimiento</option>
    @endif
    @endif
    @if(currentUser()->hasRole('secretaria') || currentUser()->hasRole('diradmin'))
    @if($conciliacion->estado_id==185 || $conciliacion->estado_id==186 ||
    $conciliacion->estado_id==187 || $conciliacion->estado_id==188 || $conciliacion->estado_id==192)
    <option value="193">Registrado en SICAB</option>
    @endif
    @endif
    @if(currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai'))
    @if($conciliacion->estado_id==189)
    <option value="192">Constancia no asistencia</option>
    @endif
    @endif

</select>