@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{asset('/plugins/dropzone59/dropzone.css')}}">
@endpush
   
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
    "message"=>"Diligencie el siguiente formulario con la información de la persona que solicita la conciliación, recuerde que si ya tiene una cuenta debe <a href='/login'>iniciar sesión</a> para realizar una nueva solicitud. Tenga en cuenta que solo los campos marcados con (*) son obligatorios.",
    "view"=>"myforms.recepcion.frm_parte_solicitante"
  ],
  1=>[
     "id"=>"btn_registrar_replegal_sol",
     "tipo_usuario"=>195,
     "visible"=>false,
     "title"=>"Legal",
     "message"=>"Diligencia el siguiente formulario con la información del <b>representante legal</b> de la persona que solicita la conciliación. Los campos marcados con (*) son obligatorios.",
     "view"=>"myforms.recepcion.frm_replegal_solicitante"
  ],
  2=>[
     "id"=>"btn_registrar_apod_sol",
     "tipo_usuario"=>196,
     "visible"=>true,
     "title"=>"Apoderado",
     "message"=>"Diligencia el siguiente formulario con la información de la persona que actúa como <b>apoderado</b> de la persona que solicita la conciliación. Los campos marcados con (*) son obligatorios.",
     "view"=>"myforms.recepcion.frm_apoderado_solicitante"
  ],
  3=>[
    "id"=>"btn_registrar_asunto",
    "tipo_usuario"=>000,
     "visible"=>true,
     "title"=>"Asunto",
     "message"=>"Diligencia el siguiente formulario con la información del <b>asunto</b> de la conciliación. Recuerda que la cuantia no debe ser superior a dos salarios mínimos. Los campos marcados con (*) son obligatorios.",
     "view"=>"myforms.recepcion.frm_asunto"
  ],
  4=>[
     "id"=>"btn_parte_convocada",
     "tipo_usuario"=>197,
     "visible"=>true,
     "title"=>"Parte convocada",
     "message"=>"Diligencia el siguiente formulario con la información de la persona <b>convocada</b> a la conciliación. Los campos marcados con (*) son obligatorios.",
     "view"=>"myforms.recepcion.frm_parte_convocada"
  ],
  5=>[
    "id"=>"btn_registrar_replegal_sol",
    "tipo_usuario"=>198,
     "visible"=>false,
     "title"=>"Rep. legal",
     "message"=>"Diligencia el siguiente formulario con la información del <b>representante legal</b> de la persona convocada a la conciliación. Los campos marcados con (*) son obligatorios.",
     "view"=>"myforms.recepcion.frm_replegal_solicitante"
  ],
  6=>[
     "id"=>"btn_solicitar_conciliacion",
     "tipo_usuario"=>000,
     "visible"=>true,
     "title"=>"Asunto a conciliar",
     "message"=>"Resuma los hechos y pretensiones de la conciliación y suba los siguientes archivos en pdf.<br>Copia de documento de identidad - Pruebas que apoyen el proceso.",
     "view"=>"myforms.recepcion.frm_anexos"
  ]
 
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
    <div class="row justify-content-center">
        <div class="col-md-10">

          <div class="card">
            <div class="card-header">
              <br>
             
              @include('myforms.recepcion.menu_buttons',[
                'paso'=>$paso, 
                'pasos'=>$pasos              
              ])
            </div>
           <div class="card-body" id="content_data_conciliaciones">
            <div class="content_message">
              @include('myforms.recepcion.menu_mensaje',[
                 'paso'=>$paso,
                 'pasos'=>$pasos
              ])
            </div>
            @include('msg.alerts')
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
           </div>
           <div class="card-footer">
            <div class="row">

              <div class="col-md-12">
                @if($paso>2)                  
                @php
                $paso_an = $paso;
                  if($paso==7){
                    if(!$pasos[5]['visible']){
                      $paso_an = 6;
                    }
                  }
                @endphp
               <a href="/solicitudes/recepcion/conciliacion/{{$conciliacion->token}}?id={{Request::get('id')}}&paso={{$paso_an-1}}">
                 <i style="cursor: pointer" class="fa fa-chevron-circle-left"  aria-hidden="true"></i>
                </a>
                 @endif

              <button type="button" data-step="{{(intval($paso)+1)}}" class="btn btn-success" data-type="{{$pasos[$paso-1]['tipo_usuario']}}"  id="{{$pasos[$paso-1]['id']}}">
                  Siguiente
              </button>
              @if(isset($conciliacion))
              <a class="btn btn-success" id="btn_no_apoderado" style="display:none" href="/solicitudes/recepcion/conciliacion/{{$conciliacion->token}}?id={{Request::get('id')}}&paso=4">
                Siguiente                
              </a>
              @endif
              <a href="/login" class="btn btn-default">
                Cancelar
              </a>

              </div>
             </div>
           </div>
          </div>
          <hr>
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
    <script src="{{asset('/plugins/dropzone59/dropzone59.js')}}"></script>
    <script src={{asset("js/dropzone_anexos.js")}}></script>
 
    <script type="module"   src={{asset("js/recepcion_conciliacion.js")}}></script>
   
  
@endpush