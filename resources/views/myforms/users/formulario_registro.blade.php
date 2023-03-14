    {!! csrf_field() !!}
    
    <div class="row">        
        <div class="col-md-6">
            <div class="form-group has-feedback"><label for="idnumber">Tipo de Persona*</label>
              <select required name="tipopers_id" id="tipopers_id" class="form-control form-control-sm required">
                  <option value="">Seleccione...</option>
                  @foreach($tipopers as $key => $doc)
                  <option {{(isset($user) and $user->tipopers_id == $key) ? "selected":"" }} value="{{$key}}">{{$doc}}</option>
                  @endforeach
              </select>
            </div>
          </div>
        
        <div class="col-md-6">
          <div class="form-group has-feedback"><label for="idnumber">Tipo documento*</label>
            <select name="tipodoc_id" id="tipodoc_id" class="form-control form-control-sm required" required>
                <option value="">Seleccione...</option>
                @foreach($tipodoc as $key => $doc)
                <option  {{(isset($user) and $user->tipodoc_id == $key) ? "selected":"" }} value="{{$key}}">{{$doc}}</option>
                @endforeach
            </select>
          </div>
        </div>

         <div class="col-md-6">
            <div class="form-group has-feedback"><label for="idnumber">Número de documento*</label>
            <input id='idnumber' value="{{(isset($user)) ? $user->idnumber:"" }}" name='idnumber' required type="text" class="form-control form-control-sm onlynumber required"  data-toggle="tooltip" title="Solo números"  placeholder="Número de documento" maxlength="12" >
           
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group has-feedback"><label for="name">Nombres*</label>
                <input id='name' value="{{(isset($user)) ? $user->name:"" }}" name='name' required type="text" class="form-control form-control-sm required"  data-toggle="tooltip" title="Nombres"  placeholder="Nombres" maxlength="20" >
                
            </div>           
        </div>
    
        <div class="col-md-6">
            <div class="form-group has-feedback"><label for="name">Apellidos*</label>
                <input id='lastname' value="{{(isset($user)) ? $user->lastname:"" }}" name='lastname' required type="text" class="form-control form-control-sm required"  data-toggle="tooltip" title="Apellidos"  placeholder="Apellidos" maxlength="20" >
                
            </div>  
        </div>
       <div class="col-md-6">
            <div class="form-group has-feedback"><label for="name">Dirección para notificaciones*</label>
                <input id='address' value="{{(isset($user)) ? $user->address:"" }}" name='address' required type="text" class="form-control form-control-sm required"  data-toggle="tooltip" title="Dirección de residencia"  placeholder="Dirección de residencia" maxlength="20" >
                
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group has-feedback"><label for="name">Correo electrónico*</label>
                <input id='email' name='email' value="{{(isset($user)) ? $user->email:"" }}" required type="email" class="form-control form-control-sm required validate_email"  data-toggle="tooltip" title="Correo electrónico"  placeholder="Correo electrónico" maxlength="50" >
                
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group has-feedback"><label for="name">Telefóno celular*</label>
                <input id='tel1' value="{{(isset($user)) ? $user->tel1:"" }}" name='tel1' required type="text" class="form-control form-control-sm onlynumber required"  data-toggle="tooltip" title="Número de contacto"  placeholder="Número de contacto" maxlength="10" >
                
            </div>
        </div>

      {{--   <div class="col-md-6">
            <div class="form-group has-feedback"><label for="name">Telefóno fijo*</label>
                <input id='tel2' name='tel2' required type="text" class="form-control form-control-sm onlynumber"  data-toggle="tooltip" title="Número de contacto"  placeholder="Número de contacto" maxlength="10" >
                
            </div>
        </div> --}}

 
      </div>

     {{--  <div id="content_aditional_data">
        @include('myforms.components_user.aditional_comp_data')
    </div> --}}
