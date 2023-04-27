@component('components.modal')
	
	@slot('trigger')
		myModal_notificacion
	@endslot

	@slot('title')
		Notificaciones
	@endslot

 
	@slot('body')



@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')
<div id="cont_adm_docs">


	<ul class="nav nav-tabs">
        @if(currentUser()->can('crear_notif_oficina_virtual'))
	<li  class="active"><a id="a_tab_notificacion_create" href="#tab_notificacion_create" data-toggle="tab">Nueva</a></li>
        @endif
        <li @if(!currentUser()->can('crear_notif_oficina_virtual')) class="active"   @endif>
        <a href="#tab_notificacion_historial" id="a_tab_notificacion_historial" data-toggle="tab">
        Historial
        </a>
        </li>
        
	
		
		</ul>
<!--Tab contnent-->
		<div class="tab-content">
			<!--Tab pane tab_3-->
            <div @if(currentUser()->can('crear_notif_oficina_virtual')) class="tab-pane active" @else class="tab-pane" @endif  id="tab_notificacion_create">
                    <div class="row">
                            <div class="col-md-12" id="content_create_notificacion">	
                                 <form method="POST" class="form_store" accept-charset="UTF-8" id="myformCreateNotificacion" enctype="multipart/form-data">
                                    {{csrf_field()}}
                          <input type="hidden" name="id" id="log_id"> 
             <input type="hidden" value="151" name="type_log_id" id="type_log_id">
           

                           <div class="form-group">
                                <label for="description">Notificación</label>
                                <textarea required class="form-control " name="description" id="description"  rows="3"></textarea> 
                            </div>
                        
                                 
                                                  
                                                  

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <br>
                                                <button type="submit" class="btn btn-block btn-primary btn-sm">
                                                Guardar
                                                </button>

                                                <button type="button" style="display:none" id="btn_cancel_upddoc" class="btn btn-block btn-default btn-sm">
                                                Cancelar
                                                </button>
                                                
                                        </div>
                                        </div>
                                    </form> 
                            </div> <!-- /.md12-->              
                    </div> <!-- /.row -->	
            </div>
                <!--Tab pane tab_3-->

                <!--Tab pane tab_3-->
            <div  @if(!currentUser()->can('crear_notif_oficina_virtual')) class="tab-pane active" @else class="tab-pane"  @endif  id="tab_notificacion_historial">
                    <div class="row">
                            <div class="col-md-12">	
                               <div id="content_notifications">

                                
                             
                              @include('myforms.components_exp.frm_list_notificaciones')
                               
                             
                               
                               </div>   
                            </div> <!-- /.md12-->              
                    </div> <!-- /.row -->	
            </div>
                <!--Tab pane tab_3-->

      


        </div>




</div>
	@endslot
@endcomponent
<!-- /modal -->










