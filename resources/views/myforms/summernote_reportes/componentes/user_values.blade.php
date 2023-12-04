@php
    $data = pdfReportsDataValues()['users'];

@endphp
<div class="row" style="margin-top: 5px">
    @foreach ($data as $key => $categorie)
        <div class="col-md-12">
            <div class="form-group item_value">
                @php
                    if($categorie['short_name']=='codigo_estudiantil' and ($parte != 'asistente' and $parte != 'conciliador')){
                     $categorie['name'] = "Tarjeta profesional";
                     $categorie['short_name'] = "tarjeta_profesional";
                    }
                @endphp
                 <small data-model="{{ $categorie['model'] }}" data-table="{{ $categorie['table'] }}"
                        data-summernote="{{ $mySummernote }}"
                        data-text="[{{ strtolower(str_replace(' ', '_', quitarAcentos($categorie['short_name']))) }}_{{ $parte }}]"
                        data-short_name="{{ strtolower(str_replace(' ', '_', quitarAcentos($categorie['short_name']))) }}"
                        class="item_con" data-type="{{$tipo_usuario_id}}" data-name="{{ $categorie['name'] }}">
                        {{ $categorie['name'] }} [{{ $parte }}]
                    </small>
                
            </div>
        </div>
    @endforeach

    @include('myforms.summernote_reportes.componentes.categories_ajax', [
        'categories_report' => getReferencesDataBySection('datos_personales', 'users'),
        'model' => 'user',
        'user_type' => $tipo_usuario_id,
        'table' => 'users_aditional_data',
    ])

    @include('myforms.summernote_reportes.componentes.categories_ajax', [
        'categories_report' => getReferencesDataBySection('socio_economica', 'users'),
        'model' => 'user',
        'user_type' => $tipo_usuario_id,
        'table' => 'users_aditional_data',
    ])

    @include('myforms.summernote_reportes.componentes.categories_ajax', [
        'categories_report' => getReferencesDataBySection('enfoque_diferencial', 'users'),
        'model' => 'user',
        'user_type' => $tipo_usuario_id,
        'table' => 'users_aditional_data', 
    ])

</div>
