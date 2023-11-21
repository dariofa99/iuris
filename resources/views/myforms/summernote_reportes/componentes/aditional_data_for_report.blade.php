@foreach ($data as $reference)
<div class="col-md-12">
    <div class="form-group item_value">
        <small data-model="user" data-table="users" data-summernote="{{ $mySummernote }}" data-short_name="{{$reference->short_name}}" class="item_con"
            user-type="{{ $tipo_usuario_id }}" data-name="{{$reference->short_name}}{{ $parte }}">
            {{$reference->name}} [{{ $parte }}]</small>
    </div>
</div>
@endforeach