       
               <div class="row">
                @if($solicitud->type_status_id == 157)
   
                <div class="col-md-12">
                   <h2>
                     Esta solicitud ha sido rechazada                               
                   </h2>    
                        
               </div>
               @elseif($solicitud->type_status_id == 158)
               <div class="col-md-12">
                 <h2>
                   Esta solicitud ha sido cancelada                               
                 </h2>            
             </div>
             @elseif($solicitud->type_status_id == 162)
                     <div class="col-md-12">
                       <h2>
                         Esta solicitud ya tiene asignado un expediente.
                         <br> 
                         Debes iniciar sesión                               
                       </h2>  
                               
                     </div>
                     @elseif($solicitud->type_status_id == 165)
                     <div class="col-md-12">
                       <h2>
                         Esta solicitud ya ha sido aceptada.
                         <br> 
                         Debes iniciar sesión                               
                       </h2>  
                              
                     </div>
                       @else
   
                     
                         <div class="col-md-6">
                           <h2>Número de solicitud:</h2>       
                         </div>
                         <div class="col-md-6"> 
                             <h2><strong style="border-bottom:1px solid black">
                               {{$solicitud->number}}</strong>                               
                             </h2>    
                                 <small>Anota este número para que puedas retomar la solicitud</small> 
                         </div>
                        
             @endif
   </div>
          <hr style="border-color: green">
   
          @if($solicitud->type_status_id == 154 || $solicitud->type_status_id == 155)
          <div class="row" style="margin-bottom: 2px">
                             <div class="col-md-4 col-md-offset-4" align="center" style="border: 1px solid gray;border-radius:20px">
                               <h3>Tú turno es:<br>                          
                                   <strong>{{$solicitud->turno}}</strong>                           
                               </h3>       
                             </div>
                         
           </div>
        
           @endif
                       <input type="hidden" value="{{$solicitud->type_status_id}}" id="soli_type_status_id">
   
   
                             @if($solicitud->type_status_id != 156 and $solicitud->type_status_id != 157)
                                     <div class="row">
   
                                       @if(($tur_aten and $solicitud->turno == $tur_aten->turno) || $solicitud->type_status_id == 155 )
                                       <div class="col-md-12" style="text-align: center;">
                                       <h2><span>Por favor espera,<br> Estamos revisando tu solicitud...</span>
                                       </h2>       
                                     </div>  
                               @elseif($solicitud->type_status_id == 171)  
                               <div class="col-md-12">
                                 <h4>Esperando a que el usuario se registre...</h4>                               
                               </div>
                             
                               @elseif($solicitud->type_status_id != 158 and $solicitud->type_status_id != 162 and $solicitud->type_status_id != 165)                         
                                   <div class="col-md-6">
                                     <h4>Turno en atención:</h4>       
                                   </div>
                                   <div class="col-md-6">
                                     <h4><strong id="lbl_turno">{{$tur_aten ? $tur_aten->turno : 'Por favor espera un momento...'}}</strong></h4>
                                   </div>
                                 
   
                               @endif                
                             </div>
                        {{--      @if($user and ($solicitud->type_status_id == 154 || $solicitud->type_status_id == 155))
                           <div class="row">
                                 <div class="col-md-12">
                               <div class="alert alert-info alert-dismissible" role="alert">
                                 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <strong>Atención!</strong>
                                 Al parecer ya tienes una cuenta, puedes iniciar sesión desde la opción en la parte derecha, si no recuerdas tu usuario o contraseña 
                                 espera la anteción.
                               </div> 
                                 </div>
                           </div>
                         @endif --}}
   
                         <hr>
                          @endif 
                             <div class="row">
                                 <div class="col-md-12">
                               @if($solicitud->type_status_id == 154)
                                 <a href="#" class="btn btn-warning btn-block" data-id="{{$solicitud->id}}" id="btn_cancel_solicitud">
                                 Cancelar solicitud</a>
                               @elseif($solicitud->type_status_id == 156 || $solicitud->type_status_id == 171)
                                 <div class="row">
                                   <div class="col-md-10 col-md-offset-1">
                                     <input type="hidden" id="tokenc" value="">
                                     <div class="embed-responsive embed-responsive-4by3" style=" height: 500px; ">
                                         {!! \Facades\App\Facades\NewChat::username($solicitud->name." ".$solicitud->lastname)
                                         ->idusuario($solicitud->idnumber)                              
                                         ->can_write(true)
                                         ->room($solicitud->number)->render() !!}
                                     </div> 
                                     </div> 
                                 </div>
                               {{--   <div class="alert alert-success" >
                                  <h4>La asesora requiere más información para tomar tu caso por favor dale click al botón para entrar al chat
                                   </h4>
                                 </div>   
   
                                <div id="clockdate" style="display:none">                              
                                     <div class="clockdate-wrapper">
                                      <h6>Tiempo disponible para entrar al chat</h6>
                                       <div id="clock"></div>
                                    </div>
                                   </div>
   
                                   <input type="hidden"  id="tiempo_espera" value="{{$solicitud->tiempo_espera}}">
                                 
                                 <a @if($user) href="#" id="btn_open_login" @else href="#" id="btn_going_chat"   @endif class="btn btn-primary btn-block">
                                   Entrar al chat
   
                                   </a> --}}
                               @elseif($solicitud->type_status_id == 157)
                                 <div class="alert alert-warning" >                             
                                  <h4>{{$solicitud->mensaje }}</h4>
                                 </div>
                               
                                  @elseif($solicitud->type_status_id == 158)
                                  {{--      
                                   <div id="clockdate" style="display:block">                              
                                     <div class="clockdate-wrapper">
                                      <h6>Tiempo disponible para entrar al chat</h6>
                                       <div id="clock"><i class='fa fa-info-circle'> </i> Se ha cancelado la solicitud. <br>Descripción: {{$solicitud->categoria->ref_nombre}}</div>
                                    </div>
                                   </div> 
                                  
                                   <div class="alert alert-info alert-dismissible" role="alert">
                                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <strong>Atención!</strong>
                                     Esta solicitud ha sido cancelada 
                                   
                                 </div>
   --}}
                                
                               @endif
                                 </div>
                             </div>
   
                        
            {{--                     <div class="row">  
     
   
                               <div class="col-md-12">
                              <hr> 
                               <div class="form-group">
                               ¿Ya estas registrado?
                               <a href="{{url('/')}}" target="_blank" class="btn btn-secondary btn-sm btn-block">Ír a inicio de sesión</a>
                               </div>
                               </div>    
                   </div>
                    --}}