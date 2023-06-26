
    <input type="hidden" name="id" value="{{isset($user) ? $user->id : ''}}">
 <div class="col-md-3">
    <div class="form-group has-feedback"><label for="idnumber">Tipo documento*</label>
      <select @if(!Request::has('id') || $user->idnumber!=null) disabled @endif name="tipodoc_id" id="tipodoc_id" class="form-control form-control-sm required" required>
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
               No. Documento *
            </label>
            <input @if(!Request::has('id') || $user->idnumber!=null) disabled @endif data-name="cc_nit"   required  type="text"
            value="{{$user->idnumber}}" name="idnumber"
            @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
                
                class="form-control form-control-sm required"
                @else 
                class="form-control form-control-sm  required"
                @endif>

		</div>
    </div>

    
    <div class="col-md-3">
        <div class="form-group">
			<label >  
                Nombres *</label>
            <input @if(!Request::has('id') || $user->idnumber!=null) disabled @endif data-name="nombre"   required  type="text"
            value="{{$user->name}}"  name="name"
            @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
                 
                class="form-control form-control-sm required"
                @else 
                class="form-control form-control-sm  required"
                @endif>

		</div>
    </div>
    
    <div class="col-md-3">
        <div class="form-group">
			<label >  
                Apellidos *</label>
            <input @if(!Request::has('id') || $user->idnumber!=null) disabled  @endif data-name="nombre"   required  type="text"
            value="{{$user->lastname}}"  name="lastname"
            @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
                 
                class="form-control form-control-sm required"
                @else 
                class="form-control form-control-sm  required"
                @endif>

		</div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
			<label >Teléfono *
            </label>
            <input @if(!Request::has('id') || $user->idnumber!=null) disabled @endif data-name="tel1"   required  type="text"
            value="{{$user->tel1}}"  name="tel1"
            @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
                 
                class="form-control form-control-sm required"
                @else 
                class="form-control form-control-sm  required"
                @endif>

		</div> 
    </div> 

    <div class="col-md-3">
        <div class="form-group">
			<label >Dirección *
            </label>
            <input @if(!Request::has('id') || $user->idnumber!=null) disabled @endif data-name="addreess"   required  type="text"
            value="{{$user->address}}"  name="address"
            @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
                 
                class="form-control form-control-sm required"
                @else 
                class="form-control form-control-sm  required"
                @endif>

		</div> 
    </div>  
 