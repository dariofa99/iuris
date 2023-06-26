<div class="pull-right" style="font-size:14px">
<label>Estudiante:&nbsp;</label>{{ $expediente->estudiante->name  }} {{ $expediente->estudiante->lastname  }}<br>
<a style="cursor: pointer;" data-toggle="modal" data-target="#myModalest-casosolicitante">Ver más</a>
@component('components.modal_dynamic')

	@slot('trigger')
		myModalest-casosolicitante
	@endslot
	@slot('size')
	modal-dialog modal-md
	@endslot

	@slot('title')
		Información estudiante
	@endslot

 
	@slot('body')


@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')
<table class="table table-hover">

                                <tr>
                                  <th>FECHA CREACIÓN EXPEDIENTE:</th>
                                   <td> {{  $expediente->expfecha  }}
                                   </td>
                                </tr>

                                <tr>
                                  <th>ÚLTIMA ACTUALIZACIÓN:</th>
                                   <td> {{  $expediente->updated_at  }}
                                   </td>
                                </tr>
                                <tr>
                                  <th></th>
                                   <td>
                                   </td>
                                </tr>
                                <tr>
                                  
                                  <td colspan="2" style="text-align: center;">
                                  <img src="{{ '../thumbnails/'.$expediente->estudiante->image }}" style="border-radius: 10px;-webkit-box-shadow: -9px 10px 9px 0px rgba(0,0,0,0.75);-moz-box-shadow: -9px 10px 9px 0px rgba(0,0,0,0.75);box-shadow: -9px 10px 9px 0px rgba(0,0,0,0.75); width: 180px;"  alt="User Image">
                                  </td>
                               </tr>
                                 <tr>
                                  <th>ESTUDIANTE:</th>
                                   <td> {{  FullName($expediente->estudiante->name, $expediente->estudiante->lastname )  }}
                                   </td>
                                </tr>
                                <tr>
                                  <th>CURSO:</th>
                                   <td> {{  $expediente->estudiante->curso->ref_nombre  }}
                                   </td>
                                </tr>

                               
                                <tr>
                                  <th>DUDAS ADICIONALES DE SU CASO SOLO:</th> 
                                   <td>
                                    @if($expediente->estudiante->turno)             
                                      {{ $expediente->getMjs($expediente->estudiante->turno->horario->ref_value) }} 
                                      @endif
                               
                                   </td>
                                </tr>
                              
                              </table>

	@endslot
@endcomponent
<!-- /modal -->





</div>