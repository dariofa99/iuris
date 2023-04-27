@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                  @include('myforms.recepcion.menu_buttons',[
                    'active'=>1,
                  ])
                </div>

                <div class="card-body" id="content_solicitud_espera">

                  <div class="content_message">
                    Paso 1 de 4: Aquí tienes que diligenciar toda la información del formulario para
                    poder acceder a la consulta, si tienes una solicitud pendiente utiliza el cuadro
                    de la derecha “recuperar turno en espera” con el número de solicitud entregado
                    por el sistema.
                  </div>


                @include('myforms.recepcion.frm_solicitud_form')
                </div>
        </div> 
    </div>

      <div class="col-md-4">
            <div class="card">
                <div class="card-header">Recuperar turno en espera.</div>

                <div class="card-body">    
                <form id="myFormSearchSolicitud">    
                         
                        <div class="row">         
                            <div class="col-md-12">
                            <div id="con_inse" class="form-group has-feedback"><label for="number_">Número de solicitud</label>
                            <input id='number_' name='number' required type="text" class="form-control form-control-sm">
                            <span class="nav-icon fa fa-refresh form-control-feedback"></span>
                            <div class="invalid-feedback">
                            
                            </div>
                            </div>
                            </div>    
                        </div>
                        <div class="row">        
                            <div class="col-md-12">
                            <div class="form-group">
                            <button type="submit" class="btn btn-warning btn-sm btn-block">
                            Retomar solicitud</button>
                            </div>
                            </div>    
                        </div>
                </form> 
                <hr>
                  <div class="row">  
  

                            <div class="col-md-12">
                            Ya estoy registrado!   
                            <div class="form-group">
                            <a href="{{url('/')}}" class="btn btn-default btn-sm btn-block">Iniciar sesión</a>
                            </div>
                            </div>    
                </div>
                </div>
        </div> 
    </div>

</div>
@endsection

@push('scripts')

<!-- jQuery 2.2.3 -->
<script src="{{asset('plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
{{-- <!-- Bootstrap 3.3.6 -->
<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
 --}}<!-- iCheck -->

  <!-- NewPush -->
<script>var tokendefault = '';</script>
<script src="{{asset('plugins/new-push/io.js?v=1')}}"></script>
<script src="{{asset('js/newpush.js?v=1')}}"></script>
{{-- <scriptsrc="asset('js/pushconnected.js?v=1')"></script>--}}
{!! Html::script('js/recepcion.js')!!}
<script>
  $(document).ready(function (){
    $('.onlynumber').keyup(function (){
      this.value = (this.value + '').replace(/[^0-9]/g, '');
    });

  $(':text[title]').tooltip({
      placement: "right",
      trigger: "focus"
  });

  });


$(function(){

  $("#myFormSearchSolicitud").on("submit",function(e){
    var request = $(this).serialize();
    getSolicitud(request)
    e.preventDefault();
  });

});

   function getSolicitud(request) {
    var route = "/solicitudes/find/e";
    $.ajax({
      url: route,
      type: 'GET',
      datatype: 'json',
      data: request,
      cache: false,
      beforeSend: function (xhr) {
        xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
        $("#wait").css("display", "block");
      },
      success: function (res) {     
          $("#wait").css("display", "none");
          if(res.status == 200){
            window.location = "/solicitudes/view/"+res.token;
          }else{
            $("#myFormSearchSolicitud .invalid-feedback").text('No se encontró la solicitud')
            $("#myFormSearchSolicitud #con_inse").addClass('has-feedback')
            $("#myFormSearchSolicitud input[name=number]").addClass('is-invalid');
            $("#myFormSearchSolicitud .form-control-feedback").hide();

          }
    
      },
      error: function (xhr, textStatus, thrownError) {
        alert("Hubo un error con el servidor, consulte con el administrador");
        $("#wait").css("display", "none");
      }
    });
  
  }

 
</script>


@endpush