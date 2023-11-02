
    @foreach ($categories_report as $categorie)
     <div class="form-group item_value">
        <small data-table="{{$categorie->table}}" data-summernote="{{$mySummernote}}" 
          data-short_name="{{strtolower(str_replace(' ', '_', quitarAcentos($categorie->name)))}}" class="item_con" user-type="{{$user_type}}" 
          data-name="{{$categorie->short_name}}">{{$categorie->name}}
        </small> 
    </div> 
    @endforeach    
