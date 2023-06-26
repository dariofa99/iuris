@foreach($labels as $key => $value)
<div class="col-md-12">
    <div class="form-group item_value">
        <small data-table="aditional_data" data-summernote="{{$mySummernote}}"   data-short_name="{{$value->short_name}}" class="item_con" user-type="{{$tipo_usuario_id}}" data-name="{{$value->short_name}}_{{$parte}}">
            {{$value->name}} [{{$parte}}]
        </small>     
    </div>
</div> 
@endforeach