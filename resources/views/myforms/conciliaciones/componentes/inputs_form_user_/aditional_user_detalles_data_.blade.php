
   @php  $section = 'solicitante';  @endphp
   @if(count($conciliacion->getUserQueForm($section,'datos_personales'))>0)    
     <div class="content_sin_secc_component">
           @include('myforms.conciliaciones.componentes.aditional_user_detalles_data_question',
           [
               "data"=>$conciliacion->getUserQueForm($section,'datos_personales')
           ])
       </div> 
   
   @endif

   @if(count($conciliacion->getUserQueForm($section,'sin_seccion'))>0)    
   <div class="content_sin_secc_component">
         @include('myforms.conciliaciones.componentes.aditional_user_detalles_data_question',
         [
             "data"=>$conciliacion->getUserQueForm($section,'sin_seccion')
         ])  
     </div> 
 
 @endif

 @if(count($conciliacion->getUserQueForm($section,'socio_economica'))>0)    
 <div class="content_sin_secc_component">
       @include('myforms.conciliaciones.componentes.aditional_user_detalles_data_question',
       [
           "data"=>$conciliacion->getUserQueForm($section,'socio_economica')
       ])
   </div> 

@endif

@if(count($conciliacion->getUserQueForm($section,'discapacidad'))>0)    
<div class="content_sin_secc_component">
     @include('myforms.conciliaciones.componentes.aditional_user_detalles_data_question',
     [
         "data"=>$conciliacion->getUserQueForm($section,'discapacidad')
     ])
 </div> 

@endif

 