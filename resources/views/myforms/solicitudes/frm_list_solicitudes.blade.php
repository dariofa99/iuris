@extends('layouts.dashboard')
@section('titulo_area')
Solicitudes
@endsection

@section('area_forms')

@include('msg.success')
<div class="row">
<div class="col-md-12">
<ul class="nav nav-tabs">
	    <li  class="active"><a id="a_tab_por_revisar" href="#tab_por_revisar" data-toggle="tab">Por revisar</a></li>
        <li><a href="#tab_historial" id="a_tab_historial" data-toggle="tab">Historial</a></li>        
</ul>
<div class="tab-content">
			<!--Tab pane tab_3-->
            <div class="tab-pane active" id="tab_por_revisar">
                    <div class="row">
                            <div class="col-md-12 table-responsive no-padding" id="content_list_solicitudes">	
                                 @include("myforms.solicitudes.frm_list_solicitudes_ajax")
                            </div> <!-- /.md12-->              
                    </div> <!-- /.row -->	
            </div>
                <!--Tab pane tab_3-->

        <!--Tab pane tab_3-->
            <div class="tab-pane" id="tab_historial">
                    <div class="row">
                             <div class="col-md-12 table-responsive no-padding" id="content_list_solicitudesh">	
                                 @include("myforms.solicitudes.frm_list_solicitudesh_ajax")
                            </div> <!-- /.md12-->              
                    </div> <!-- /.row -->	
            </div>
                <!--Tab pane tab_3-->


</div>
<!--tab-content-->
</div>
</div>






<div class="row">



<div class="col-md-12 ">


			

</div>
</div>



              @stop