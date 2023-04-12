<input type="hidden" name="id" value="{{isset($user) ? $user->id : ''}}">

<div class="col-md-3">
    <div class="form-group has-feedback"><label for="idnumber">Tipo de Persona*</label>
      <select {{$disabled}}  required name="tipopers_id" id="tipopers_id" class="form-control form-control-sm required">
          <option value="">Seleccione...</option>
          @foreach($tipopers as $key => $doc)
          <option {{(isset($user) and $user->tipopers_id == $key) ? "selected":"" }} value="{{$key}}">{{$doc}}</option>
          @endforeach
      </select>
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group has-feedback"><label for="idnumber">Tipo documento*</label>
      <select {{$disabled}}  name="tipodoc_id" id="tipodoc_id" class="form-control form-control-sm required" required>
          <option value="">Seleccione...</option>
          @foreach($tipodoc as $key => $doc)
          <option  {{(isset($user) and $user->tipodoc_id == $key) ? "selected":"" }} value="{{$key}}">{{$doc}}</option>
          @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-group">
        <label>
           No. Identificación*
        </label>
        
        <input name="idnumber" {{$disabled}}  data-name="idnumber"   required  type="text"
        value="{{$user->idnumber}}"  class="form-control form-control-sm required"
        >
        

    </div>
</div>
<div class="col-md-3">
<div class="form-group">
    <label >  
        Nombres*</label>
    <input  name="name"  {{$disabled}}  data-name="nombre"   required  type="text"
     value="{{$user->name}}" class="form-control form-control-sm required"
    @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
     
    @endif>

</div>
</div>

<div class="col-md-3">
<div class="form-group">
    <label >  
        Apellidos*</label>
    <input  name="lastname"  {{$disabled}}  data-name="nombre"   required  type="text"
     value="{{$user->lastname}}" class="form-control form-control-sm required"
    @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
   
     
    @endif>

</div>
</div>
<div class="col-md-3">
<div class="form-group">
    <label >Teléfono*
    </label>
    <input  name="tel1"  {{$disabled}}  data-name="telefono"   required  type="text"
    value="{{$user->tel1}}" class="form-control form-control-sm required"
    @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
     
     
    
     @endif>

</div>
</div>

<div class="col-md-3">
<div class="form-group">
    <label > Correo electrónico*
        </label>
    <input  name="email"  {{$disabled}}  data-name="correo_electronico"   required  type="text"
    value="{{$user->email}}"
     
     class="form-control form-control-sm required"
   
    >

</div>
</div>