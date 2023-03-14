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
        @if(!Request::has('id'))
                <button type="button" @if($user->idnumber!=null) data-user="{{$user->idnumber}}" @endif data-section="apoderado_solicitante" data-type="196" class="btn btn-primary btn-sm btn_asinar_usuario_conciliacion pull-right">  
                    <i class="fa fa-plus"> </i> {{$user->idnumber!=null ? 'Actualizar' : 'Agregar'}} 
                </button>
        @endif
                @if($user->idnumber!=null)  
                <button type="button" data-user="{{$user->idnumber}}" data-pivot="{{$user->pivot->id}}" class="btn btn-danger btn-sm btn_delete_usuario_conciliacion pull-right">  
                    <i class="fa fa-trash"> </i> <small style="color:aliceblue">Eliminar</small>
                </button>
               @endif
           @endif    
            @endif
        </h4>

    </div>
</div>
<div class="row">
    <div class="col-md-3">        
        <div class="form-group has-feedback"><label for="idnumber">Tipo documento*</label>
          <select @if(!Request::has('id') || $user->idnumber!=null) disabled @endif  name="tipodoc_id" id="tipodoc_id" class="form-control form-control-sm required" required>
              <option value="">Seleccione...</option>
              @foreach($tipodoc as $key => $doc)
              <option {{$doc->id==$user->tipodoc_id ? "selected" : ""}} value="{{$doc->id}}">{{$doc->ref_nombre}}</option>
              @endforeach
          </select>
        </div>
      </div>

    <div class="col-md-3">
        <div class="form-group">
			<label>
               No. Documento*
            </label>
            <input @if(!Request::has('id') || $user->idnumber!=null) disabled @endif  data-name="cc_nit"  data-section="{{$section}}" required  type="text"
            value="{{$user->idnumber}}" name="idnumber"
            @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
                 
                class="form-control form-control-sm required"
                @else 
                class="form-control form-control-sm insert_adv"
                @endif>

		</div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
			<label >  
                Nombres*</label>
            <input   data-name="nombre"  data-section="{{$section}}" required  type="text"
            @if(!Request::has('id') || $user->idnumber!=null)
                disabled
            @endif 
            value="{{$user->name}}"  name="name"
            @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
                
                class="form-control form-control-sm required"
                @else 
                class="form-control form-control-sm insert_adv"
                @endif>

		</div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
			<label >  
                Apellidos*</label>
            <input
             @if(!Request::has('id') || $user->idnumber!=null) disabled @endif 
             data-name="nombre"  data-section="{{$section}}" required  type="text"
            value="{{$user->lastname}}" name="lastname"
            @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
                 
                class="form-control form-control-sm required"
                @else 
                class="form-control form-control-sm insert_adv"
                @endif>

		</div>
    </div>
    
    <div class="col-md-3">
        <div class="form-group">
			<label >Teléfono*
            </label>
            <input @if(!Request::has('id') || $user->idnumber!=null) disabled @endif  data-name="tel1"  data-section="{{$section}}" required  type="text"
            value="{{$user->tel1}}" name="tel1"
            @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
                 
                class="form-control form-control-sm required"
                @else 
                class="form-control form-control-sm insert_adv"
                @endif>

		</div> 
    </div>

    @foreach($conciliacion->getUserForm('apoderado_solicitante','sin_seccion') as $key => $question)
    <div class="col-md-3">
        <div class="form-group">
            <label > {{$question->name}}*</label>
            <input @if(!Request::has('id') || $user->idnumber!=null) disabled @endif  data-name="{{$question->short_name}}"  data-section="{{$question->section}}" required  type="text"
            @if($user->getDataValWShort($question->short_name))
            value="{{$user->getDataValWShort($question->short_name)->value}}" @endif
            @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
              
             class="form-control form-control-sm"
            @else 
            class="form-control form-control-sm insert_adv"
             @endif>
    
        </div>
    </div>
    @endforeach

</div>
