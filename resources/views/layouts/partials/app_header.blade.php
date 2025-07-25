


<div class="row" style="background-color: #222d32; opacity: 1; margin-right: 0px;">
    <div class="col-md-3 image d-none d-sm-inline-block" style="padding-left: 50px;">
        <a href="/">
            <img src="{{ asset('dist/img/udenarbl.png') }}" class="img" style="width: 250px;margin:10px;"
                alt="User Image">
        </a>
    </div>
    <div class="col-md-7" style="padding-top: 25px; text-align: center; font-size: 17px;">
        <p style="color:#ffffff;     font-size: 20px; font-weight: 900;"><b>Consultorios Jurídicos y Centro de
                Conciliación<br>"Eduardo Alvarado Hurtado"</b></p>
    </div>
    {{--   <div class="col-md-2" style="padding: 25px;">
        <a href="/">
            Iniciar sesión    
        </a>
    </div> --}}
</div>
<div class="container-fluid">

    <div class="row">

        <div class="col-md-5" style="padding-left: 35px;">
            @if (Request::is('login'))
                <h2>
                    <button class="btn_login btn-solicitud">
                      <i class="fas fa-sign-in-alt"></i>  ¿Ya tienes una cuenta? Inicia sesión.
                    </button>
                </h2>

                @include('msg.alerts')
            @endif
        </div>

        <div class="col-md-4">
            <p style="color:#000000;     font-size: 24px;">
                <b>Sistema de atención virtual</b>
            </p>
        </div>

        <div class="col-md-3 d-flex justify-content-end">
             @if (Request::is('login'))
            <button id="btn_solicitud_atencion_virtual" class="btn-solicitud mt-2 ">
                <i class="fas fa-file-alt"></i> Solicitud de atención virtual
            </button>
            @endif
        </div>

    </div>


</div>
