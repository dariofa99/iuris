
    <form action="{{route('solicitudes.conciliacion')}}" method="post">
        {!! csrf_field() !!}
  
    <div class="row">
          <div class="col-md-6">
            <div class="form-group has-feedback"><label for="idnumber">Tipo documento</label>
              <select name="tipodoc_id" id="tipodoc_id" class="form-control form-control-sm required" required>
                  <option value="">Seleccione...</option>
                  @foreach($tipodoc as $key => $doc)
                  <option value="{{$key}}">{{$doc}}</option>
                  @endforeach
              </select>
            </div>
          </div>
  
           <div class="col-md-6">
              <div class="form-group has-feedback"><label for="idnumber">Número de documento</label>
              <input id='idnumber' name='idnumber' required type="text" class="form-control form-control-sm onlynumber"  data-toggle="tooltip" title="Solo números"  placeholder="Número de documento" maxlength="12" >
              <span class="nav-icon fa fa-id-card form-control-feedback"></span>
              </div>
          </div>
   
        </div>
  
    
     
          <div class="row">
          
           <div class="col-md-6">
            <div class="form-group has-feedback"><label for="name">Nombres</label>
          <input id='name' name='name' required type="text" class="form-control form-control-sm" placeholder="Tu nombre">
          <span class="nav-icon fa fa-user form-control-feedback"></span>
        </div>
          </div>
      
          <div class="col-md-6">
            <div class="form-group has-feedback"><label for="lastname">Apellidos</label>
          <input id='lastname' required name='lastname' type="text" class="form-control form-control-sm" placeholder="Tu apellido">
          <span class="nav-icon fa fa-user form-control-feedback"></span>
        </div>
          </div>
        </div>
  
        
  <div class="row">  
  
          <div class="col-md-6">
            <label for="email">Correo electrónico</label>
            <input id='email' required name='email' type="email" class="form-control form-control-sm"  data-toggle="tooltip" title="Ingresa un correo" placeholder="Correo electrónico" maxlength="50" >
         
          </div>
  
          <div class="col-md-6">
            {{-- <div class="form-group has-feedback"><label for="tel1">Número de celular</label>
          <input id='tel1' required name='tel1' type="text" class="form-control form-control-sm onlynumber"  data-toggle="tooltip" title="Solo números" placeholder="Tu número de teléfono" maxlength="10" >
          <span class="nav-icon fa fa-phone form-control-feedback"></span>
        </div> --}}
          </div>
  </div>
  

  
        <div class="row">
          <div class="form-check" style="padding-left: 2rem;">
            <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
              <label class="form-check-label" for="exampleCheck1" style="margin-bottom: 10px; margin-left:15px;" >Conozco y acepto los <a href="/terminosycondiciones" target="_blank" title="Click para ver...">términos y condiciones</a></label>
          </div>
  
        </div>
  
        <div class="row">
          <div class="col-md-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Enviar solicitud</button>
          </div>
        </div>
  
        
  
    
      <hr>
         
  
  
      </form>
      