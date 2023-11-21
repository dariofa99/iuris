@foreach ($categories_report as $categorie)
    <div class="col-md-12">
        <div class="form-group item_value">
            <small data-model="{{ $model }}" data-table="{{ $categorie->table }}"
                data-summernote="{{ $mySummernote }}"
                data-short_name="{{ strtolower(str_replace(' ', '_', quitarAcentos($categorie->name))) }}"
                class="item_con" data-type="{{ $user_type }}"
                data-name="{{ $categorie->short_name }}">{{ $categorie->name }} [{{ $parte }}]
            </small>
        </div>
    </div>
@endforeach
