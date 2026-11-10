@component('components.b4.modal_large')
    @slot('trigger')
        myModal_detalles_recuperar_cuenta
    @endslot

    @slot('title')
        ¡Atención!
    @endslot


    @slot('body')
        <div class="text-center py-2">
            <div class="text-info mb-3" aria-hidden="true">
                <i class="fas fa-info-circle fa-3x">
                    Cuenta ya registrada
                </i>
                
            </div>

            <div class="alert alert-info" role="status">
                <strong>Ya existe una cuenta registrada con tus datos.</strong>
                <p class="mb-0 mt-2">
                    No necesitas crear otra cuenta. Para continuar con tu solicitud, selecciona
                    <strong>Iniciar sesión</strong> e ingresa tu usuario y contraseña.
                </p>
            </div>

            <a href="/login" class="btn btn-primary btn-block">
                <i class="fas fa-sign-in-alt mr-1" aria-hidden="true"></i>
                Iniciar sesión
            </a>

            <div class="border-top mt-4 pt-3">
                <p class="mb-2">
                    ¿No recuerdas tu usuario o contraseña? Selecciona
                    <strong>Recuperar mi cuenta</strong> y sigue las instrucciones para recuperar el acceso.
                </p>
                <a href="/recovery/account" class="btn btn-outline-secondary btn-block">
                    <i class="fas fa-key mr-1" aria-hidden="true"></i>
                    Recuperar mi cuenta
                </a>
            </div>
        </div>
    @endslot

    @slot('footer')
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    @endslot
@endcomponent
<!-- /modal -->
