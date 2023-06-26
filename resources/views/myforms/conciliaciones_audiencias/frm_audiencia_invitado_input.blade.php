@extends('layouts.app')
@push('styles')
{!! Html::style('/css/jitsi.css?v=2')!!}
<!-- aqui van los estilos de cada vista -->
<style>
            .container-meet {
                /*position: relative;
                border:1px red  solid;*/
                width: 100%;
                height:600px;
                margin-bottom: 30px;
                text-align: center;
            }
            .toolbox {
               /* position: absolute;*/
                bottom: 0px;
                /*border:1px black solid;*/
                width: 100%;
                height:50px;
                background-color: rgb(71, 71, 71);
            }
            #jitsi-meet-conf-container{
                width: 100%;
                height:570px;
            }
            hr {
                margin-top: 5px !important;
                margin-bottom: 15px !important;
            }
            .alert, .thumbnail {
                margin-bottom: 5px !important;
            }
        </style>
@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card" style="margin-bottom: 25px;">
                <div class="card-header"><b>Bienvenido(a) a la audiencia de conciliación.</b></div>
                <div class="card-body">
                    <label>Para ingresar debes escribir en el campo de texto tu número de cédula, recuerda que solo pueden ingresar las personas autorizadas.</label>
                    <label>Si tienes inconvenientes para ingresar comunicate al celular: 3106038006</label>
                    <form method="POST" action="/audiencia/{{$code}}" id="myLoginForm2">
                        {{ csrf_field() }}
                        @include('msg.danger')
                        <hr>
                        <div class="form-group has-feedback">
                            <label for="idnumber">Número de documento</label>
                            <input id="idnumber" name="idnumber" required="required" type="text" data-toggle="tooltip" title="" placeholder="Tu número de cédula" maxlength="12" class="form-control form-control-sm onlynumber" data-original-title="Solo números">
                            <input id="id_sede" name="id_sede" type="hidden" >
                            <span class="nav-icon fa fa-id-card form-control-feedback"></span>
                        </div>

                        <div class="form-group mb-0 row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-warning">
                                    Enviar
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>


@endsection
@push('scripts')
<!-- aqui van los scripts de cada vista -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script type="text/javascript">
$(document).ready(function (){
    $('.onlynumber').keyup(function (){
      this.value = (this.value + '').replace(/[^0-9]/g, '');
    });

  $(':text[title]').tooltip({
      placement: "right",
      trigger: "focus"
  });

  });
</script>
@endpush