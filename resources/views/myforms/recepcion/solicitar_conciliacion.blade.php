@extends('layouts.app')

@push('styles')
    <style>


    </style>
@section('content')
@php
$paso = Request::has('paso') ? Request::get('paso') : 1;
$num_pasos = 4;
$pasos = [
  0=>[
    "id"=>"btn_registrar_conc",
    "tipo_usuario"=>205,
    "visible"=>true,
    "title"=>"Solicitud",
    "message"=>"Paso $paso:  Aquí tienes que diligenciar toda la información de la persona que solicita la conciliación, recuerda que si ya tienes una cuenta debes <a href='/login'>iniciar sesión</a> para realizar una nueva solicitud.",
    "view"=>"myforms.recepcion.frm_parte_solicitante"
  ],
  1=>[
     "id"=>"btn_registrar_replegal_sol",
     "tipo_usuario"=>195,
     "visible"=>false,
     "title"=>"Legal",
     "message"=>"Paso $paso:  Aquí tienes que es el rep legal",
     "view"=>"myforms.recepcion.frm_replegal_solicitante"
  ],
  2=>[
     "id"=>"btn_registrar_apod_sol",
     "tipo_usuario"=>196,
     "visible"=>true,
     "title"=>"Apoderado",
     "message"=>"Paso $paso:  Aquí tienes que es el apoderado",
     "view"=>"myforms.recepcion.frm_apoderado_solicitante"
  ],
  3=>[
    "id"=>"btn_registrar_asunto",
    "tipo_usuario"=>000,
     "visible"=>true,
     "title"=>"Asunto",
     "message"=>"Paso $paso:  Aquí tienes que es la informacion del asunto",
     "view"=>"myforms.recepcion.frm_asunto"
  ],
  4=>[
     "id"=>"btn_parte_convocada",
     "tipo_usuario"=>197,
     "visible"=>true,
     "title"=>"Parte convocada",
     "message"=>"Paso $paso:  Aquí tienes que es la informacion de la parte convocada",
     "view"=>"myforms.recepcion.frm_parte_convocada"
  ],
  5=>[
    "id"=>"btn_registrar_replegal_sol",
    "tipo_usuario"=>198,
     "visible"=>false,
     "title"=>"Rep. legal",
     "message"=>"Paso $paso:  Aquí tienes que es la informacion del rep legal de la parte solicitada",
     "view"=>"myforms.recepcion.frm_replegal_solicitante"
  ],
  6=>[
     "id"=>"btn_solicitar_conciliacion",
     "tipo_usuario"=>000,
     "visible"=>true,
     "title"=>"Asunto a conciliar",
     "message"=>"Paso $paso:  Aquí tienes que es la informacion del asunto",
     "view"=>"myforms.recepcion.frm_anexos"
  ],
]; 

  if(isset($conciliacion)){  
    if($paso>="2")  {
      
      $user = $conciliacion->getUser(205);//solicitante
      if($user->tipopers_id == 238){  
        //$before = $pasos[($paso - 1)];
        $pasos[1]['visible'] = true;      
      }
    }
   
    if($paso>="6")  {
     
      $user = $conciliacion->getUser(197);//solicitado
      
      if($user->tipopers_id == 238){ 
        
        $pasos[5]['visible'] = true;      
      }
    }
  
  }
  $num_pasos = count($pasos);
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

          <div class="panel panel-default">
            <div class="panel-heading">
              <br>
              @include('myforms.recepcion.menu_buttons',[
                'paso'=>$paso, 
                'pasos'=>$pasos              
              ])
            </div>
            <div class="panel-body">
              <div class="content_message">
                @include('myforms.recepcion.menu_mensaje',[
                   'paso'=>$paso,
                   'pasos'=>$pasos
                ])
              </div>
              @if($paso==1)             
               @include('myforms.recepcion.frm_parte_solicitante')
              @else
               @if(isset($conciliacion))
                <input type="hidden" value="{{$conciliacion->id}}" name="conciliacion_id" id="conciliacion_id">
               @endif
               @include($pasos[intval($paso)-1]['view'],[
                  'conciliacion'=>$conciliacion,
                  'tipo_usuario_id'=>$pasos[$paso-1]['tipo_usuario']
               ])

               
              @endif
               <div class="row">

                <div class="col-md-12">
                  @if($paso>2)
                 <a href="/solicitudes/recepcion/conciliacion/{{$conciliacion->token}}?id={{Request::get('id')}}&paso={{$paso-1}}">
                   <i style="cursor: pointer" class="fa fa-chevron-circle-left"  aria-hidden="true"></i>
                  </a>
                   @endif

                <button type="button" data-step="{{(intval($paso)+1)}}" class="btn btn-success" data-type="{{$pasos[$paso-1]['tipo_usuario']}}"  id="{{$pasos[$paso-1]['id']}}">
                    Siguiente
                </button>

                <a href="/login" class="btn btn-default">
                  Cancelar
                </a>

                </div>
               </div>
            </div>
          </div>


          </div>
        </div>
</div>
@include('myforms.conciliaciones.componentes.modal_create_hechos_pretenciones')
@include('myforms.conciliaciones.componentes.modal_create_document')
   
@endsection
@push('scripts')
    {{-- <script type="module" src={{asset("js/conciliaciones.js")}}></script>
    <script type="module" src={{asset("js/users.js")}}></script>
    --}} 
    <script type="module"   src={{asset("js/recepcion_conciliacion.js")}}></script>


@endpush