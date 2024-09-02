<div class="row">
    
    @include('myforms.conciliaciones.componentes.aditional_data',[
        "data"=>getReferencesDataBySection($section,'conciliaciones'),
        'required'=>'required'
    ])
</div>
