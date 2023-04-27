<div class="row">
    <div class="col-md-12">
        <h4 align="center">
            <strong>INFORMACIÓN SOCIOECONÓMICA</strong>
        </h4>
    </div>
</div>

<div class="row">

 


    <div class="col-md-3">
        <div class="form-group">
			<label>
                Estado civil
            </label>
             <select disabled data-name="estado_civil"  data-section="{{$section}}" @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
                        disabled 
                        class="form-control"
                        @else 
                            class="form-control  insert_adv"
                        @endif>
                        <option value="">Seleccione...</option>

                        @foreach($estcivil as $key => $estado_civil)
                            <option data-option_id="1" {{$user->estadocivil_id == $estado_civil->id ? 'selected' : '' }}  value="{{$estado_civil->id}}">{{$estado_civil->ref_nombre}}</option>
                        @endforeach
            </select >
                
            
		</div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
			<label >
               Estrato</label>
                <select disabled data-name="estrato"  data-section="{{$section}}" @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
                    disabled 
                    class="form-control"
                    @else 
                        class="form-control  insert_adv"
                    @endif>
                    <option value="">Seleccione...</option>

                    @foreach($estrato as $key => $estra)
                        <option data-option_id="1" {{$user->estrato_id == $estra->id ? 'selected' : '' }}  value="{{$estra->id}}">{{$estra->ref_nombre}}</option>
                    @endforeach
        </select >

		</div>
    </div>

    @foreach($conciliacion->getUserForm('solicitante','socio_economica') as $key => $question)
    
    <div class="col-md-3">
        <div class="form-group">
            <label > {{$question->name}}</label>
            <input data-name="{{$question->short_name}}"  data-section="{{$question->section}}" required  type="text"
            @if($user->getDataValWShort($question->short_name))
            value="{{$user->getDataValWShort($question->short_name)->value}}" @endif
            @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
             disabled 
             class="form-control form-control-sm"
            @else 
            class="form-control form-control-sm insert_adv"
             @endif disabled>
    
        </div>
    </div>
    @endforeach



  {{--   <div class="col-md-3">
        <div class="form-group">
			<label>
                No. Personas a cargo
            </label>  

                <input data-name="numero_personas_cargo"  data-section="{{$section}}" required  type="text"
                @if($user->getDataValWShort('numero_personas_cargo'))
                value="{{$user->getDataValWShort('numero_personas_cargo')->value}}" @endif
                @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
                disabled 
                class="form-control form-control-sm"
                @else 
                class="form-control form-control-sm insert_adv"
                @endif>
            
		</div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
			<label>
                Afiliado a SISBEN
            </label>
           
            <input data-name="numero_personas_cargo"  data-section="{{$section}}" required  type="text"
            @if($user->getDataValWShort('afiliado_regimen_sisben'))
            value="{{$user->getDataValWShort('afiliado_regimen_sisben')->value}}" @endif
            @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
            disabled 
            class="form-control form-control-sm"
            @else 
            class="form-control form-control-sm insert_adv"
            @endif>
            
		</div>
    </div> --}}


</div>

{{-- <div class="row">
    <div class="col-md-3">
        <div class="form-group">
			<label >
            Ingresos mensuales    
            </label>
            <input data-name="ingresos_mensuales"  data-section="{{$section}}" required  type="text"
            @if($user->getDataValWShort('ingresos_mensuales'))
            value="{{$user->getDataValWShort('ingresos_mensuales')->value}}" @endif
            @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
            disabled 
            class="form-control form-control-sm"
            @else 
            class="form-control form-control-sm insert_adv"
            @endif>

		</div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
			<label >
               Egresos mensuales
               </label>
                <input data-name="egresos_mensuales"  data-section="{{$section}}" required  type="text"
                @if($user->getDataValWShort('egresos_mensuales'))
                value="{{$user->getDataValWShort('egresos_mensuales')->value}}" @endif
                @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
                disabled 
                class="form-control form-control-sm"
                @else 
                class="form-control form-control-sm insert_adv"
                @endif>
    

		</div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
			<label>
                Tipo de vivienda
            </label>
           
            <input data-name="tipo_vivienda"  data-section="{{$section}}" required  type="text"
            @if($user->getDataValWShort('tipo_vivienda'))
            value="{{$user->getDataValWShort('tipo_vivienda')->value}}" @endif
            @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
            disabled 
            class="form-control form-control-sm"
            @else 
            class="form-control form-control-sm insert_adv"
            @endif>
            
		</div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
			<label >
                Nivel de escolaridad</label>
                <input data-name="nivel_escolaridad"  data-section="{{$section}}" required  type="text"
                @if($user->getDataValWShort('nivel_escolaridad'))
                value="{{$user->getDataValWShort('nivel_escolaridad')->value}}" @endif
                @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
                disabled 
                class="form-control form-control-sm"
                @else 
                class="form-control form-control-sm insert_adv"
                @endif>

		</div>
    </div>
</div> --}}