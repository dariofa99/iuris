<input type="hidden" id="prdfecha_inicio" value="{{$periodo->prdfecha_inicio}}" >
<div class="row">
    <div class="col-md-3">
        <h4 class="box-title">
        <label>
            Fecha Audiencia 
        </label>
        @if ($audiencia != "")
       
        <span class="edit_audiencia_existe">
            <h4>{{ \Carbon\Carbon::parse($audiencia->fecha)->dayName }}, {{ \Carbon\Carbon::parse($audiencia->fecha)->format("d") }} de {{ \Carbon\Carbon::parse($audiencia->fecha)->monthName }} {{ \Carbon\Carbon::parse($audiencia->fecha)->format("Y") }}</h4>
        </span>
        @endif
            <input id="audiencia_fecha" value="{{$audiencia!='' ? $audiencia->fecha : '' }}" class="form-control form-control-sm edit_audiencia" data-name="fecha" required="" type="date" min="{{date('Y-m-d')}}" style="max-width:180px; @if ($audiencia != '') display:none; @endif ">
        </h4>
    </div>

    <div class="col-md-3">
        <h4 class="box-title">
        <label>
            Hora audiencia
        </label>      
        <div class="bootstrap-timepicker" style=" @if ($audiencia != '') display:block; @endif">
            <div class="form-group">
				<div class="input-group">
					<input type="time" id="audiencia_hora" {{$audiencia!=null?'disabled':''}} value="{{$audiencia!=null?$audiencia->hora:''}}" class="form-control form-control-sm {{$audiencia==null?:'input_time'}}">					
				</div>
                <!-- /.input group -->
            </div>
            <!-- /.form group -->
        </div>
        </h4>
    </div>

    <div class="col-md-3">
        <h4 class="box-title">
        <label>
            Color día
        </label>
        <div>
            <label id="audiencia_label_color_day" class="label dis-block color-amarillo audiencia_label_color_day" style="background-color: #ffffff">
              <span style="color: rgb(53, 52, 52) !important">
                {{$audiencia!=null?:'Seleccione una fecha'}}
                </span> 
            </label>
        </div>
        </h4>
    </div>
    <div class="col-md-2">
        <h4 class="box-title">
            <label >
            &nbsp;
            </label>
          
            @if ($audiencia != "" and currentUser()->can('editar_audiencia_conciliacion'))

            <input type="button" value="Editar" class="btn btn-warning btn-block btn-sm" id="btm_edit_date_audiencia" data-id="{{$conciliacion->id}}">
            @endif 
            <input type="button" value="Guardar" class="btn btn-primary btn-block btn-sm edit_audiencia" id="btm_save_date_audiencia" data-id="{{$conciliacion->id}}" style="@if ($audiencia != '') display:none @endif">
            <input type="button" value="Cancelar" class="btn btn-danger btn-block btn-sm edit_audiencia" id="btm_cancel_date_audiencia" data-id="{{$conciliacion->id}}" style="display:none">
           
            
        </h5>
    </div>
</div>
<hr> 

@if (currentUser()->can('asig_reparto'))

   @if($audiencia != '')        
 <div class="edit_audiencia" style="@if ($audiencia != '') display:block @endif" >
    <h4>Reparto</h4>
   <hr>
    <div class="row"  style="height: 300px; overflow-x: auto;display:{{($audiencia != "")? 'block':'none'}}" id="list_turno_estudiantes_conciliacion">
         @include('myforms.conciliaciones.componentes.list_turno_estudiante') 
    </div>  
<hr>
</div>
@else

    <div class="alert alert-info">
      
        <h4>
            <i class="far fa-calendar-alt"></i> 
            Asigne fecha de audiencia para asignar reparto!
        </h4> 
    </div>


@endif 
@endif 

@if ($audiencia != "")
<input type="hidden" id="conciliacion_audiencia_id" value="{{$audiencia->fecha}}">

@if ((currentUser()->can('act_conciliacion') || 
(currentUserInConciliacion($conciliacion->id,['conciliador','auxiliar']) and 
($conciliacion->getUser(203)->pivot->user_id == auth()->user()->id
             and $conciliacion->getUser(203)->pivot->estado_id == 229))))
<div class="row" >
    <div class="input-group margin" id="content-text-stream-audiencia">
        <input type="text" id="text-stream-audiencia" class="form-control"  value="{{URL::to('/')}}/audiencia/{{$audiencia->access_code}}" readonly>
        <span class="input-group-btn">
            <button type="button" id="copy-stream-audiencia" class="btn btn-info btn-flat" data-frame="{{URL::to('/')}}/audiencia/{{$audiencia->access_code}}">Copiar link</button>
        </span>
    </div>
</div>
<div class="row" >
    <div class="col-md-2">
        <input type="button" value="Iniciar videollamada" class="btn btn-primary btn-block btn-sm" id="btn_iniciar_videollamada">
    </div>
    <div class="col-md-2">
        <input type="button" value="Crear salas alternas" class="btn btn-primary btn-block btn-sm" id="btn_invitacion_sala_alterna"  data-id="{{$conciliacion->id}}">
    </div>
    <div class="col-md-2">
        <input type="button" value="Acceder a sala alterna" class="btn btn-primary btn-block btn-sm" id="btm_access_room_alter{{ $conciliacion->id }}" @if($sala_alterna_url == "") style="display:none" @else onclick="openPopUpSalas('{{ $sala_alterna_url }}');" @endif>
    </div>

</div>
@endif
<div class="row">
    <div class="col-md-12">

        <div id='joinMsg'></div>
        <div id="stamby_audiencia" style="height: 620px;width: 100%;background-color: rgba(25, 25, 25, 0.93);text-align: -webkit-center;padding-top: 100px; margin-bottom: 10px; display:none;">
            <button id="btnvolver_audiencia" title="Volver a la audiencia" class="btn btn-primary btn-block btn-sm" style="width: 150px;">Volver a la audiencia</button>
        </div>
        <div id='container-meet' class="container-meet" style="display:none;">
            <div id='jitsi-meet-conf-container'></div>
            <div id="toolbox" class="toolbox" style="display:none;">
                <button id='btnCustomMic' class="boton-redondo jitsi-mic" ><i class="fa fa-microphone" aria-hidden="true"></i></button>
                <button id='btnCustomCamera' class="boton-redondo jitsi-cam">Cam</button>
                <button id='btnCustomTileView' class="boton-redondo"  title="Cambiar vista"><i class="fa fa-th-large" aria-hidden="true"></i></button>
                <button id='btnScreenShareCustom' class="boton-redondo" title="Compartir pantalla"><i class="fa fa-desktop" aria-hidden="true"></i></button>
                <button id='btnHangup' class="boton-redondo jitsi-exit" title="Colgar">Colgar</button>
            </div>

        </div>
    </div>
</div>

<div class="iniciar_videollamada" style="display:none;">
    {{-- @include('myforms.conciliaciones.conciliacion_audiencia_chat') --}}
</div>

@if(((currentUser()->can('act_conciliacion')))
|| (currentUserInConciliacion($conciliacion->id,['auxiliar','conciliador'])))
@if($conciliacion->estado_id==181)
<hr>
<div class="row">
    <div class="col-md-12">
        <div class="form-group" >
              <label style="display: block; margin-bottom:10px">Acuerdos
                   <button type="button" data-tipo="208" class="btn btn-primary btn-sm pull-right btn_add_conc_he_con"> Agregar Acuerdo</button>       
              </label>
            <div id="content_hechos_pretensiones-208" class="content_hechos_pretensiones">
                @include('myforms.conciliaciones.componentes.hechos_pretenciones_ajax',[
                    'tipo_id'=>208
                ]) 
            </div>           
        </div>
    </div>
</div>
@endif
@endif

@endif