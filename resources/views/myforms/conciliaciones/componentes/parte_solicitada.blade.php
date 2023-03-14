@php
    $user = $conciliacion->getUser(197);
@endphp
<div class="row" >
    <div class="col-md-12">
        <h4> <strong> PARTE SOLICITADA</strong>
           
             
              @if(((currentUser()->hasRole('diradmin') || currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai')))
              || ((currentUserInConciliacion($conciliacion->id,['autor','auxiliar','conciliador']))))  
               @if(($conciliacion->estado_id==174 || $conciliacion->estado_id==176 || $conciliacion->estado_id==194))
                
               @if(!Request::has('id'))
               <button type="button" @if($user->idnumber!=null) data-user="{{$user->idnumber}}" @endif data-section="solicitada" data-type="197" class="btn btn-primary btn-sm btn_asinar_usuario_conciliacion pull-right">  
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

{{-- {{dd($conciliacion->getStaticDataVal('fecha',$section))}} --}}

    

<div class="row">

    <div class="col-md-3">
        <div class="form-group has-feedback"><label for="idnumber">Tipo de Persona*</label>
          <select @if(!Request::has('id') || $user->idnumber!=null) disabled @endif required name="tipopers_id" id="tipopers_id" class="form-control form-control-sm required">
              <option value="">Seleccione...</option>
              @foreach($tipopers as $key => $doc)
              <option {{(isset($user) and $user->tipopers_id == $key) ? "selected":"" }} value="{{$key}}">{{$doc}}</option>
              @endforeach
          </select>
        </div>
      </div>

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
               No. Identificación*
            </label>
            
            <input name="idnumber" {{(!Request::has('id') || $user->idnumber!=null) ? "disabled" : ""}} data-name="idnumber"  data-section="{{$section}}" required  type="text"
            value="{{$user->idnumber}}"  class="form-control form-control-sm required"
            @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
              
            
           
             @endif>
            
    
        </div>
    </div>
    <div class="col-md-3">
    <div class="form-group">
        <label >  
            Nombres*</label>
        <input  name="name"  {{(!Request::has('id') || $user->idnumber!=null) ? "disabled" : ""}}  data-name="nombre"  data-section="{{$section}}" required  type="text"
         value="{{$user->name}}" class="form-control form-control-sm required"
        @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
         
        @endif>

    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label >  
            Apellidos*</label>
        <input  name="lastname"  {{(!Request::has('id') || $user->idnumber!=null) ? "disabled" : ""}}  data-name="nombre"  data-section="{{$section}}" required  type="text"
         value="{{$user->lastname}}" class="form-control form-control-sm required"
        @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
       
         
        @endif>

    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label >Teléfono*
        </label>
        <input  name="tel1"  {{(!Request::has('id') || $user->idnumber!=null) ? "disabled" : ""}} data-name="telefono"  data-section="{{$section}}" required  type="text"
        value="{{$user->tel1}}" class="form-control form-control-sm required"
        @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
         
         
        
         @endif>

    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label > Correo electrónico*
            </label>
        <input  name="email"  {{(!Request::has('id') || $user->idnumber!=null) ? "disabled" : ""}} data-name="correo_electronico"  data-section="{{$section}}" required  type="text"
        value="{{$user->email}}"
         
         class="form-control form-control-sm required"
       
        >
  
    </div>
</div>
</div>