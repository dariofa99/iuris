@php
$data = pdfReportsDataValues()['conciliaciones'];

@endphp
  <div class="row">
    @foreach ($data as $key => $categorie)
    <div class="col-md-12">
        <div class="form-group item_value">
            
         
                <small data-model="{{ $categorie['model'] }}" data-table="{{ $categorie['table'] }}"
                    data-summernote="{{ $mySummernote }}"
                    data-text="[{{ strtolower(str_replace(' ', '_', quitarAcentos($categorie['short_name']))) }}_{{ $parte }}]"
                    data-short_name="{{ strtolower(str_replace(' ', '_', quitarAcentos($categorie['short_name']))) }}"
                    class="item_con" data-type="{{$tipo_usuario_id}}"
                    data-name="{{ $categorie['name'] }}">
                    {{ $categorie['name'] }} [{{ $parte }}]
                </small>
        </div>
    </div>
    @endforeach
      @include('myforms.summernote_reportes.componentes.categories_ajax', [
          'categories_report' => getReferencesDataBySection('asunto', 'conciliaciones'),
          'required' => 'required',
          'model' => 'conciliacion',
          'user_type' => 'conc_aditional_data',
          'table' => 'conc_aditional_data',
      ])
  </div>
