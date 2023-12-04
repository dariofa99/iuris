@foreach ($categories_report as $categorie)

    <div class="col-md-12">
        <div class="form-group item_value">
            <small data-model="{{ $model }}" data-table="{{ isset($table) ? $table : $categorie->table }}"
                data-summernote="{{ $mySummernote }}"
                data-text="[{{ strtolower(str_replace(' ', '_', quitarAcentos($categorie->short_name))) }}_{{ $parte }}]"
                data-short_name="{{ strtolower(str_replace(' ', '_', quitarAcentos($categorie->short_name))) }}"
                class="item_con" data-type="{{ $user_type }}"
                data-name="{{ $categorie->name }}">{{ $categorie->name }} [{{ $parte }}]
            </small>
        </div>
    </div>
@endforeach
