@extends('layouts.dashboard')
@section('titulo_area')
<i>Número de solicitud:</i> {{$solicitud->number}} <br> <i>Estado de la solicitud:</i> 


               <span id="lbl_status_sol"> {{$solicitud->estado->ref_nombre}} </span>
                  


@endsection
@section('area_buttons')

@if($solicitud->type_status_id == 155 || $solicitud->type_status_id == 156)
<p><i>Número de turno:</i> {{$solicitud->turno}}</p>

<div class="" id="con_timer" @if($solicitud->type_status_id == 155 || $solicitud->type_status_id == 156) style="display:none" @endif>
<input type="hidden"  id="tiempo_espera" value="{{$solicitud->tiempo_espera}}">
<h6>Tiempo disponible para entrar al chat: <div id="clock"></div></h6>
</div>

@endif 
@if(count($solicitud->expedientes)>0)
<p>No. Expediente: {{$solicitud->expedientes[0]->expid}}</p>

@endif    
<p>Categoria:     <label id="lbl_cta_ref_n"> {{$solicitud->categoria->ref_nombre}}     </label>   </p>            
@endsection



@section('area_forms')

@include('msg.success')

 
                       
<div class="row">
<div class="col-md-12 table-responsive no-padding" id="content_edit_solicitud">

 <input type="hidden" id="solicitud_id" value="{{$solicitud->id}}">
 <input type="hidden" value="{{$solicitud->type_status_id}}" id="soli_type_status_id">
			
	<ul class="nav nav-tabs">
      

	<li    class="active">
                <a id="a_tab_recibidos" href="#tab_datos_gen" data-toggle="tab">
         Datos generales</a>
        </li>
        
        
         <li><a href="#tab_chat" id="a_tab_chat" data-toggle="tab">Chat</a></li>
         
         
    
       
        
        <li id="li_tab_asig_exp"><a href="#tab_asig_exp" id="a_tab_asig_exp" data-toggle="tab">Asignar expediente</a></li>

		
		</ul>
<!--Tab contnent--> 
		<div class="tab-content">
			<!--Tab pane tab_3-->
            <div   class="tab-pane active"  id="tab_datos_gen">
                <div class="row">
                        <div  class="content_oficina_virtual">                     
                             @include('myforms.solicitudes.frm_solicitud')
                        </div>  <!-- /.md12-->              
                </div>             
            </div>
                <!--Tab pane tab_3-->

                <!--Tab pane tab_3-->
            <div  class="tab-pane"  id="tab_chat">
                    <div class="row">
                            <div id="content_oficina_virtual" class="content_oficina_virtual">                      
                             
                               @include('myforms.solicitudes.frm_chat')
                               </div>  <!-- /.md12-->              
                    </div> <!-- /.row -->	
            </div>
                <!--Tab pane tab_3-->

                
                <!--Tab pane tab_3-->
                        <div class="tab-pane" id="tab_asig_exp" style="min-height:500px;">
                        @include('myforms.solicitudes.frm_asignar_expediente')	
                        </div>
              
                <!--Tab pane tab_3-->


        </div>

</div>
</div>


 @include('myforms.components_exp.frm_modal_videollamada',['user_idnumber'=>$solicitud->idnumber])
 @include('myforms.solicitudes.frm_modal_user_register')
@include('myforms.solicitudes.frm_modal_acept_solicitud')
@include('myforms.solicitudes.frm_modal_denied_solicitud')
@include('myforms.solicitudes.frm_modal_adm_documentos')
@include('myforms.frm_expediente_user_edit') 
	
@stop
@push('scripts')
<script src="{{asset('js/timer.js')}}"></script>
<script src="{{asset('js/recepcion.js')}}"></script>
@endpush