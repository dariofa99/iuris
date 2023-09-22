<div class="row" align="center">
    <div class="col-md-12">
        <h4> <strong>ELEMENTOS JURÍDICOS</strong> </h4>      
    </div>
</div>

{{-- {{dd($conciliacion->getStaticDataVal('fecha',$section))}} --}}

<hr>
<div class="row">
<div class="col-md-12">
    <div class="form-group">  
        <label style="display: block; margin-bottom:10px"> Hechos   
    @if(((currentUser()->hasRole('diradmin') || currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai')))
            || ((currentUserInConciliacion($conciliacion->id,['autor','auxiliar','conciliador']) || currentUser()->hasRole('amatai'))))
             @if($conciliacion->estado_id==174 || $conciliacion->estado_id==176 || $conciliacion->estado_id==194 || ($conciliacion->estado_id==240 and currentUserInConciliacion($conciliacion->id,['autor'])))
         <button type="button" data-tipo="206" class="btn btn-primary btn-sm float-right btn_add_conc_he_con">Agregar hecho</button>
         @endif
    @endif
    </label>
    <div id="content_hechos_pretensiones-206" class="content_hechos_pretensiones">
        @include('myforms.conciliaciones.componentes.hechos_pretenciones_ajax',[
            'tipo_id'=>206
        ]) 
    </div>
    </div>
</div> 
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <div class="form-group" >
            <label style="display: block; margin-bottom:10px">Pretensiones
                @if(((currentUser()->hasRole('diradmin') || currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai')))
            || ((currentUserInConciliacion($conciliacion->id,['autor','auxiliar','conciliador']))))
             @if($conciliacion->estado_id==174 || $conciliacion->estado_id==176 || $conciliacion->estado_id==194 || ($conciliacion->estado_id==240 and currentUserInConciliacion($conciliacion->id,['autor'])))
                <button type="button" data-tipo="207" class="btn btn-primary btn-sm float-right btn_add_conc_he_con"> Agregar pretensión</button>       
            @endif
            @endif
            </label>
            <div id="content_hechos_pretensiones-207" class="content_hechos_pretensiones">
                @include('myforms.conciliaciones.componentes.hechos_pretenciones_ajax',[
                    'tipo_id'=>207
                ]) 
            </div>
           
        </div>
    </div>
</div>
<hr>
 
