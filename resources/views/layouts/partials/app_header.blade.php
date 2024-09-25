<div class="row" style="background-color: #222d32; opacity: 1; margin-right: 0px;" >
    <div class="col-md-3 image d-none d-sm-inline-block" style="padding-left: 50px;">
        <a href="/">
            <img src="{{ asset('dist/img/udenarbl.png') }}" class="img" style="width: 250px;margin:10px;" alt="User Image">
        </a>
    </div>
    <div class="col-md-7" style="padding-top: 25px; text-align: center; font-size: 17px;">
        <p style="color:#ffffff;     font-size: 20px; font-weight: 900;"><b>Consultorios Jurídicos y Centro de Conciliación<br>"Eduardo Alvarado Hurtado"</b></p> 
    </div>
  {{--   <div class="col-md-2" style="padding: 25px;">
        <a href="/">
            Iniciar sesión    
        </a>
    </div> --}}
</div>
<div class="container-fluid">
  
<div class="row" >
           
        <div class="col-md-5" style="padding-left: 35px;">
            @if(Request::is("login"))
            <h2>
                <a href="#" class="btn_login">
                    ¿Ya tienes una cuenta? Inicia sesión.
                </a>
            </h2> 
            
            @include('msg.alerts')
            @endif
        </div>
     
        <div class="col-md-6">
            <p style="color:#000000;     font-size: 20px;">
                <b>Sistema de atención virtual</b></p>  
        </div>
    </div>
   
     
</div>     
