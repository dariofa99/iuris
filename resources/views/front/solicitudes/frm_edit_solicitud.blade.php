@extends('layouts.front.dashboard')
@section('titulo_area')
<i>Número de solicitud:</i> {{$solicitud->number}} <br> <i>Estado de la solicitud:</i> 


                {{$solicitud->estado->ref_nombre}}
                  


@endsection

@section('area_buttons')
@if(count($solicitud->expedientes)>0)
No. Expediente: {{$solicitud->expedientes[0]->expid}}
<br>
@endif
@if(count($solicitud->sedes)>0)
Sede: {{($solicitud->sedes[0]->nombre)}}
@endif
@endsection

@section('area_forms')

@include('msg.success')

<div class="row">
<div class="col-md-12 table-responsive no-padding" id="content_edit_solicitud">

			
	<ul class="nav nav-tabs">
         <li class="active"><a href="#tab_chat" id="a_tab_chat" data-toggle="tab">Chat</a></li>
       
	<li  ><a id="a_tab_recibidos" href="#tab_datos_gen" data-toggle="tab">Datos generales</a></li>
        
		
		</ul>
<!--Tab contnent-->
		<div class="tab-content">
			<!--Tab pane tab_3-->
            <div class="tab-pane" id="tab_datos_gen">
                    @include('front.solicitudes.frm_solicitud')
            </div>
                <!--Tab pane tab_3-->

                <!--Tab pane tab_3-->
            <div class="tab-pane active" id="tab_chat">
                    <div class="row">
                            <div class="col-md-12">	
                                            
                             @if($solicitud->type_status_id == 154 || $solicitud->type_status_id==155
                              || $solicitud->type_status_id==157 || $solicitud->type_status_id==158)
                             <div id="content_solicitud_espera">          
                          @include('front.solicitudes.frm_solicitud_espera',[
                                     'user'=>$solicitud->user
                             ]) 
                             </div>
                             @else
                             <div id="content_oficina_virtual">          
                               @include('front.solicitudes.frm_chat') 
                        </div>  
                               @endif
                              
                            </div> <!-- /.md12-->              
                    </div> <!-- /.row -->	
            </div>
                <!--Tab pane tab_3-->

                
                <!--Tab pane tab_3-->
          
                <!--Tab pane tab_3-->


        </div>

</div>
</div>


@include('front.solicitudes.frm_modal_adm_documentos')

@include('front.solicitudes.frm_modal_notifications')

@stop
@push('scripts')
<script src="{{asset('js/recepcion.js')}}"></script>
@endpush