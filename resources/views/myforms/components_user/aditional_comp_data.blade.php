
<div class="row"  >   
@if(count($rdatos_personales)>0)

  <div id="content_sin_secc_component">
        @include('myforms.components_user.aditional_data',
        [
            "data"=>$rdatos_personales
        ])
    </div> 
    
@endif
@if(count($rdata_sin_secc)>0)

    <div id="content_sin_secc_component">
        @include('myforms.components_user.aditional_data',
        [
            "data"=>$rdata_sin_secc
        ])
    </div> 

@endif
</div>
@if(count($rdata_info_soc_ec)>0)
<div class="col-md-12" align="center">
    <label>Información socio-económica</label>		   
</div>
<div class="row"  >   
 
    <div id="content_sin_secc_component">
        @include('myforms.components_user.aditional_data',
        [
            "data"=>$rdata_info_soc_ec
        ])
    </div> 
</div>
@endif

@if(count($rdata_enf_dif)>0)
<div class="col-md-12" align="center">
    <label>Enfoque diferencial</label>		   
</div>
<div class="row"  >

    <div id="content_efqdiferencial_component">
    @include('myforms.components_user.aditional_data',
    [
        "data"=>$rdata_enf_dif
    ])
    </div>
</div>
@endif
@if(count($rdata_discap)>0)
<div class="col-md-12" align="center">
    <label>Discapacidad</label>		   
</div>
<div class="row" >
   

    <div id="content_disc_component">
        @include('myforms.components_user.aditional_data',
        [
            "data"=>$rdata_discap
        ])
    </div>
</div>
@endif
@if(count($rdata_gretnc)>0)
<div class="col-md-12" align="center">
    <label>Grupo étnico</label>		   
</div>
<div class="row"  >

    <div id="content_gpetnico_component">
        @include('myforms.components_user.aditional_data',
        [
            "data"=>$rdata_gretnc
        ])
    </div> 

</div>
@endif

