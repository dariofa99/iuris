<input type="hidden" name="id" value="{{isset($user) ? $user->id : ''}}">

<div class="col-md-3">
<div class="form-group has-feedback"><label for="idnumber">Tipo documento*</label>
  <select  {{isset($disabled) ? $disabled : ''}}  name="tipodoc_id" id="tipodoc_id" class="form-control form-control-sm required" required>
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
       No. Documento*
    </label>
    <input {{isset($disabled) ? $disabled : ''}}   data-name="cc_nit"  required  type="text"
    value="{{$user->idnumber}}" name="idnumber"
         
        class="form-control form-control-sm required"
        
       >

</div>
</div>
<div class="col-md-3">
<div class="form-group">
    <label >  
        Nombres*</label>
    <input   data-name="nombre"  required  type="text"
    {{isset($disabled) ? $disabled : ''}} 
    value="{{$user->name}}"  name="name"
       
        class="form-control form-control-sm required"
       
      >

</div>
</div>

<div class="col-md-3">
<div class="form-group">
    <label >  
        Apellidos*</label>
    <input
    {{isset($disabled) ? $disabled : ''}}  
     data-name="nombre"   required  type="text"
    value="{{$user->lastname}}" name="lastname"
         
        class="form-control form-control-sm required"
       
      >

</div>
</div>

<div class="col-md-3">
<div class="form-group">
    <label >Teléfono*
    </label>
    <input {{isset($disabled) ? $disabled : ''}}   data-name="tel1" name="tel1"  required  type="text"
    value="{{$user->tel1}}" name="tel1"
         
        class="form-control form-control-sm required"
   >

</div> 
</div>