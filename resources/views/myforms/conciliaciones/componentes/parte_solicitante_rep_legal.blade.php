@php
    $user = $conciliacion->getUser($tipo_usuario_id);
@endphp
<div class="row">
    <div class="col-md-12">
        <h4 >
            Representante Legal (Diligenciar solo para personas jurídicas, o naturales incapaces) 
       

           @if( ((currentUser()->hasRole('diradmin') || currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai')))
            || ((currentUserInConciliacion($conciliacion->id,['autor','auxiliar','conciliador']))))
             @if(($conciliacion->estado_id==174 || $conciliacion->estado_id==176 || $conciliacion->estado_id==194))
              
             @if($user->idnumber==null and !Request::has('paso'))
             <button data-form="form_rep_legal" type="button" @if($user->idnumber!=null) data-user="{{$user->idnumber}}" @endif data-section="rep_legal_solicitante" data-type="{{$tipo_usuario_id}}" class="btn btn-primary btn-sm btn_asinar_usuario_conciliacion pull-right">  
                <i class="fa fa-plus"> </i> {{$user->idnumber!=null ? 'Actualizar' : 'Agregar'}} 
               </button>
            @endif
               
               @if($user->idnumber!=null) 
               <button type="button" data-user="{{$user->idnumber}}" data-pivot="{{$user->pivot->id}}" class="btn btn-danger btn-sm btn_delete_usuario_conciliacion pull-right">  
                   <i class="fa fa-trash"> </i>
               </button>
              @endif

           @endif

        @endif
    </h4>
    </div>
</div>
<div class="row" id="form_rep_legal">
    <div class="col-md-offset-9 col-md-3" id="ctbotones-{{$tipo_usuario_id}}" style="display: none">
        <button data-form="form_rep_legal" style="margin: 1px" type="button" @if($user->idnumber!=null) data-user="{{$user->idnumber}}" @endif data-type="{{$tipo_usuario_id}}" class="btn btn-default btn-sm btn_cancel_usuario_conciliacion pull-right">  
            Cancelar
        </button>

        <button data-form="myUserRepLegalForm" style="margin: 1px" type="button" @if($user->idnumber!=null) data-user="{{$user->idnumber}}" @endif data-section="rep_legal" data-type="{{$tipo_usuario_id}}" class="btn btn-success btn-sm btn_agregar_usuario_conciliacion pull-right">  
            <i class="fa fa-plus"> </i> {{$user->idnumber!=null ? 'Actualizar' : 'Agregar'}} 
        </button>
     </div>      
</div>
<div id="user_rep_legal_form">
    @include('myforms.conciliaciones.componentes.user_replegal_form') 
</div>


