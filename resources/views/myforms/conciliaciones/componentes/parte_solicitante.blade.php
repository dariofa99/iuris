@php
    $user = $conciliacion->getUser(205);
@endphp

<div class="row" >
    <div class="col-md-12">
        <h4> <strong> PARTE SOLICITANTE</strong>
          

           @if(((currentUser()->hasRole('diradmin') || currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai')))
            || ((currentUserInConciliacion($conciliacion->id,['autor','auxiliar','conciliador']))))
                    @if(($conciliacion->estado_id==174 || $conciliacion->estado_id==176 || $conciliacion->estado_id==194))
               <button type="button" @if($user->idnumber!=null) data-user="{{$user->idnumber}}" @endif data-section="solicitante" data-type="205" class="btn btn-primary btn-sm btn_asinar_usuario_conciliacion pull-right">  
                <i class="fa fa-plus"> </i> {{$user->idnumber!=null ? 'Actualizar' : 'Agregar'}} 
               </button>

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

{{-- {{dd($conciliacion->getStaticDataVal('fecha',$section))}} --}}
    


<div class="row">
<div class="col-md-6">
    <div class="form-group">
        <label >  
            Nombre</label>
        <input  data-name="nombre"  data-section="{{$section}}" required  type="text"
         value="{{$user->name}} {{$user->lastname}}" 
        @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
         disabled 
         class="form-control form-control-sm"
        @else 
        class="form-control form-control-sm insert_adv"
         @endif
         disabled
        >

    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label>
           No. Identificación
        </label>
        
        <input data-name="idnumber"  data-section="{{$section}}" required  type="text"
        value="{{$user->idnumber}}" 
        @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
         disabled 
         class="form-control form-control-sm"
        @else 
        class="form-control form-control-sm insert_adv"
         @endif
         disabled> 
        

    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label >Teléfono
        </label>
        <input data-name="telefono"  data-section="{{$section}}" required  type="text"
        value="{{$user->tel1}}" 
        @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
         disabled 
         class="form-control form-control-sm"
        @else 
        class="form-control form-control-sm insert_adv"
         @endif
         disabled
         >

    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label > Correo electrónico
            </label>
        <input data-name="correo_electronico"  data-section="{{$section}}" required  type="text"
        value="{{$user->email}}"
        @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
         disabled 
         class="form-control form-control-sm"
        @else 
        class="form-control form-control-sm insert_adv"
         @endif disabled>

    </div>
</div>


@foreach($conciliacion->getUserForm('solicitante','datos_personales') as $key => $question)
<div class="col-md-3">
    <div class="form-group">
        <label > {{$question->name}}</label>
        <input data-name="{{$question->short_name}}"  data-section="{{$question->section}}" required  type="text"
        @if($user->getDataValWShort($question->short_name))
        value="{{$user->getDataValWShort($question->short_name)->value}}" @endif
        @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
         disabled 
         class="form-control form-control-sm"
        @else 
        class="form-control form-control-sm insert_adv"
         @endif disabled>

    </div>
</div>
@endforeach

@foreach($conciliacion->getUserForm('solicitante','discapacidad') as $key => $question)
<div class="col-md-3">
    <div class="form-group">
        <label > {{$question->name}}</label>
        <input data-name="{{$question->short_name}}"  data-section="{{$question->section}}" required  type="text"
        @if($user->getDataValWShort($question->short_name))
        value="{{$user->getDataValWShort($question->short_name)->value}} {{$user->getDataValWShort($question->short_name)->value_is_other}}" @endif
        @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
         disabled 
         class="form-control form-control-sm"
        @else 
        class="form-control form-control-sm insert_adv"
         @endif disabled>

    </div>
</div>
@endforeach



{{-- <div class="col-md-3">
    <div class="form-group">
        <label > 
              Edad</label>
        <input data-name="edad"  data-section="{{$section}}" required  type="text"
        @if($user->getDataValWShort('edad'))
        value="{{$user->getDataValWShort('edad')->value}}" @endif
        @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
         disabled 
         class="form-control form-control-sm"
        @else 
        class="form-control form-control-sm insert_adv"
         @endif>

    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label >
            Discapacidad</label>
        <input data-name="discapacidad"  data-section="{{$section}}" required  type="text"
        @if($user->getDataValWShort('tipo_discapacidad'))
        value="{{$user->getDataValWShort('tipo_discapacidad')->value}} {{$user->getDataValWShort('tipo_discapacidad')->value_is_other}}" @endif
        @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
         disabled 
         class="form-control form-control-sm"
        @else 
        class="form-control form-control-sm insert_adv"
         @endif>

    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label >
           Dirección notificaciones</label>
        <input data-name="direccion_notificaciones"  data-section="{{$section}}" required  type="text"
        @if($user->getDataValWShort('direccion_notificaciones'))
        value="{{$user->getDataValWShort('direccion_notificaciones')->value}} " @endif
        @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
         disabled 
         class="form-control form-control-sm"
        @else 
        class="form-control form-control-sm insert_adv"
         @endif>

    </div>
</div> --}}



</div>
<div>
    
    @include('myforms.conciliaciones.componentes.info_socio_economica',[     
    'section'=>'info_socio_economica'
])
</div>

