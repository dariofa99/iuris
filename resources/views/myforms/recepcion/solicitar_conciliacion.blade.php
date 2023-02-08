@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              @include('myforms.recepcion.menu_buttons',[
                'active'=>1,
              ])

            </div>

            <div class="card-body">
              <div class="content_message">
                Paso 1 de 2: Aquí tienes que diligenciar toda la información del formulario para
                poder acceder a la consulta, si tienes una solicitud pendiente inicia sesión para ver los detalles.
              </div>
                @include('myforms.recepcion.frm_user_register_conciliacion')
             
                <a href="/login" style="color: black;border-bottom:1px solid black;">Iniciar sesión</a>
            </div>
          </div>
          </div>
        </div>
</div>

@endsection
