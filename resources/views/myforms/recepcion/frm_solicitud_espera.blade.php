@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <input type="hidden" id="solicitud_id" value="{{$solicitud->id}}">
        <input type="hidden" id="user_session_idnumber" value="{{$solicitud->idnumber}}">


    <div class="col-md-8 col-md-offset-1">
      <div class="card">
          <div class="card-header">

            @php
            $active = 5;
              if($solicitud->type_status_id == 154 || $solicitud->type_status_id == 155 ){
                $active = 2;
              }elseif($solicitud->type_status_id == 156){
                $active = 3;
              }elseif($solicitud->type_status_id == 171){
                $active = 4;
              }elseif($solicitud->type_status_id == 165 || $solicitud->type_status_id == 158){
                $active = 5;
              }
            @endphp
            @include('myforms.recepcion.menu_buttons',[
              'active'=> $active ,
            ])
          </div>

          <div class="card-body" id="content_solicitud_espera"> 
            <div class="content_message">
              @if($solicitud->type_status_id == 154 || $solicitud->type_status_id == 155)
              Paso 2 de 4: Tienes que esperar tu turno hasta recibir atención, recuerda
              anotar el número de la solicitud para que puedas retomar la consulta en
              caso de algún problema de conexión.
              @elseif($solicitud->type_status_id == 156)
              Paso 3 de 4: Sigue las instrucciones del asesor.
              @elseif($solicitud->type_status_id == 171)
              Paso 4 de 4: Registrate con la opción a la derecha de esta pantalla.
              @elseif($solicitud->type_status_id == 165)
              Paso 4 de 4:Inicia Sesión con la opción a la derecha de esta pantalla.
              @endif 
            </div>
               @include('myforms.recepcion.frm_solicitud_espera_ajax')
          </div>
      </div> 
    </div>

    @if(
    ($user and ($solicitud->type_status_id==154 || $solicitud->type_status_id==155 || $solicitud->type_status_id==156 || $solicitud->type_status_id==165))
    || (!$user and ($solicitud->type_status_id==171))
    )  <div class="col-md-3">
      <div class="card">
          <div class="card-header">
            @if($user)
            Iniciar sesión
            @else
            Registrarse
            @endif
          </div>
          <div class="card-body" id=""> 
            @include('msg.danger')
          
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                  @if($user)
                  @include("myforms.recepcion.frm_form_user_login")
                @else
                @include("myforms.recepcion.frm_form_user_register")
                @endif
                </div>
            </div>
       
          </div>
      </div> 
  </div>
@endif

</div>
@endsection
{{-- @include('myforms.recepcion.frm_modal_login')
 --}}
@push('scripts')

<!-- jQuery 2.2.3 -->
<script src="{{asset('plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
{{-- <!-- Bootstrap 3.3.6 -->
<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
 --}}<!-- iCheck -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{asset('js/timer.js')}}"></script>
  

<script src="{{asset('js/recepcion.js')}}"></script>
<!-- NewPush -->
<script>var tokendefault = '';</script>
<script src="{{asset('plugins/new-push/io.js?v=1')}}"></script>
<script src="{{asset('js/newpush.js?v=1')}}"></script>
{{-- <scriptsrc="asset('js/pushconnected.js?v=1')"></script>--}} 

@endpush