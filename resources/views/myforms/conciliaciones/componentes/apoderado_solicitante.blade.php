@php
$user = $conciliacion->getUser(196);

@endphp
<div class="row">
    <div class="col-md-12">
        <h4 align="center">
            <strong>APODERADO DE LA PARTE SOLICITANTE</strong>
          
           @if(((currentUser()->hasRole('diradmin') || currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai')))
           || ((currentUserInConciliacion($conciliacion->id,['autor','auxiliar','conciliador'])))) 
          @if(($conciliacion->estado_id==174 || $conciliacion->estado_id==176 || $conciliacion->estado_id==194))
          @if($user->idnumber==null and !Request::has('paso')) 
                <button data-form="form_apoderado" type="button" @if($user->idnumber!=null) data-user="{{$user->idnumber}}" @endif data-section="apoderado_solicitante" data-type="196" class="btn btn-primary btn-sm btn_asinar_usuario_conciliacion pull-right">  
                    <i class="fa fa-plus"> </i> {{$user->idnumber!=null ? 'Actualizar' : 'Agregar'}} 
                </button>
        @endif 
      
                @if($user->idnumber!=null )  
                <button type="button" data-user="{{$user->idnumber}}" data-pivot="{{$user->pivot->id}}" class="btn btn-danger btn-sm btn_delete_usuario_conciliacion pull-right">  
                    <i class="fa fa-trash"> </i> <small style="color:aliceblue"></small>
                </button>
               @endif
           @endif    
            @endif
        </h4>

    </div>
</div>

    <div class="row" id="form_apoderado">
        <div class="col-md-offset-9 col-md-3" id="ctbotones-196" style="display: none">
            <button data-form="form_apoderado" style="margin: 1px" type="button" @if($user->idnumber!=null) data-user="{{$user->idnumber}}" @endif data-type="196" class="btn btn-default btn-sm btn_cancel_usuario_conciliacion pull-right">  
                Cancelar
            </button>
    
            <button data-form="myUserApoderadoForm" style="margin: 1px" type="button" @if($user->idnumber!=null) data-user="{{$user->idnumber}}" @endif data-section="solicitante" data-type="196" class="btn btn-success btn-sm btn_agregar_usuario_conciliacion pull-right">  
                <i class="fa fa-plus"> </i> {{$user->idnumber!=null ? 'Actualizar' : 'Agregar'}} 
            </button>
         </div>
         <div id="user_apoderado_form" style="width: 100%">
            @include('myforms.conciliaciones.componentes.user_apoderado_form')
         </div>
    </div>

 

