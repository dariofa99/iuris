<div class="row">

    @include('myforms.categorias.refs_aditional_data', [
        'data' => getReferencesDataBySection($section, 'conciliaciones'),
        'col' => 6,
        'model' => $conciliacion,
    ])

</div>
