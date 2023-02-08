<div class="row">


    <div class="col-md-10">
        @if(count($conciliacion->expedientes)>0)
        <h6><i>Número de expediente: 
           <a href="/expedientes/{{$conciliacion->expedientes[0]->expid}}/edit">
             {{$conciliacion->expedientes[0]->expid}}
           </a></i></h6>
           @endif
    </div>
   
    

    @if (count(currentUser()->conciliaciones()
    ->where([
        'conciliacion_id'=>$conciliacion->id,
        'tipo_usuario_id'=>203,
        'conciliacion_has_user.estado_id'=>229
        ])
    ->get())>0)

    
<div class="col-md-2">
    <button data-user_estado="230" data-pivot_id="{{  currentUser()->conciliaciones()
        ->where(['conciliacion_id'=>$conciliacion->id,'tipo_usuario_id'=>203])
        ->first()->pivot->id }}"
    style="display: block;margin-bottom:1px;" data-estado="{{$conciliacion->estado_id}}" id="btn_notificarse" class="btn btn-success">
    Aceptar (Notificarse)
</button>
<button data-user_estado="231" data-pivot_id="{{  currentUser()->conciliaciones()
    ->where(['conciliacion_id'=>$conciliacion->id,'tipo_usuario_id'=>203])
    ->first()->pivot->id }}"
style="display: block;margin-bottom:1px;" data-estado="{{$conciliacion->estado_id}}" id="btn_notificarse_cancelar" class="btn btn-warning">
NO Aceptar 
</button>
</div>

@endif 



</div>


<div class="row">
    <div class="col-md-12" id="content_conciliaciones">    
    <form id="myForm">
        <input type="hidden" id="conciliacion_id" value="{{$conciliacion->id}}">
        <input type="hidden" id="older_value">
    <div class="row">
        <div class="col-md-12">
            <h5>
                INFORMACIÓN DE LAS PARTES
            </h5>
        </div>
    </div>
    <div class="box_section">
        @include('myforms.conciliaciones.componentes.parte_solicitante',[     
            'section'=>'parte_solicitante'
        ])
      
        @include('myforms.conciliaciones.componentes.parte_solicitante_rep_legal',[     
            'section'=>'representante_legal_solicitante'
        ])
    
    </div>
    
    
   
    
    <div class="box_section">
        @include('myforms.conciliaciones.componentes.apoderado_solicitante',[     
        'section'=>'apoderado_solicitante'
    ])
    </div>
    
    <div class="box_section">
        @include('myforms.conciliaciones.componentes.parte_solicitada',[     
        'section'=>'parte_solicitada'
    ])
    @include('myforms.conciliaciones.componentes.parte_solicitada_rep_legal',[     
        'section'=>'representante_legal_solicitada'
    ])
    </div>
    
    <div class="box_section">
        @include('myforms.conciliaciones.componentes.elementos_juridicos',[     
            'section'=>'elementos_juridicos' 
        ])
      
    </div>
    <div class="box_section">
        @include('myforms.conciliaciones.componentes.anexos',[     
            'section'=>'anexos'
        ])
    </div>
    
    </form>
    
    </div>
    </div>