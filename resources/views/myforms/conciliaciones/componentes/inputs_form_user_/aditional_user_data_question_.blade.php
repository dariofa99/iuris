
@foreach($data as $key => $data_value)

<div class="col-md-6">
    <div class="form-group">
        <label for="">{{$data_value->name}}</label><br>
        <input type="hidden" name="reference_data_id[]" value="{{$data_value->id}}">
        @if($data_value->type_data_id==168)
        <input type="hidden" value="{{ $data_value->options()->first()->id }}" name="value_{{$data_value->id}}[]">
        <input type="text" class="form-control" name="value_text_{{$data_value->id}}[]"
         @if(isset($user) and $user!=null
            and $user->getDataVal($data_value->id,$data_value->options()->first()->id))
        value="{{$user->getDataVal($data_value->id,$data_value->options()->first()->id)->value}}" @endif>
        @elseif($data_value->type_data_id==169)
        @php
        $is_active = false;
        $option_id = 0 ;
        @endphp
        <select name="value_{{$data_value->id}}[]" id="option_id-{{$data_value->id}}"
            class="form-control data_input_select" data-id="{{$data_value->id}}">
            <option value="">Seleccione</option>
            @foreach($data_value->options as $key_2 => $option)
            @php
            if(isset($user) and $user!=null and $user->getDataVal($data_value->id,$option->id) and
            $option->active_other_input){
            $is_active = true ;
            $option_id = $option->id ;
            }
            @endphp
            <option data-active_other="{{$option->active_other_input}}" @if(isset($user) and $user!=null and $user->
                getDataVal($data_value->id,$option->id)) selected @endif value="{{$option->id}}">{{$option->value}}
            </option>
            @endforeach
        </select>

        <label @if(!$is_active) style="display: none" @endif id="lbl_other-{{$data_value->id}}">¿Cuál...?</label>

        <input id="value_other_text-{{$data_value->id}}" @if($is_active) type="text" @else type="hidden" @endif
            @if(isset($user) and $user!=null and $user->getDataVal($data_value->id,$option_id))
        value="{{$user->getDataVal($data_value->id,$option_id)->value_is_other}}" @endif
        name="value_other_text_{{$data_value->id}}[]" class="form-control form-control-sm" placeholder="¿Cuál...?">

        @elseif($data_value->type_data_id==170)
        @foreach($data_value->options as $key_2 => $option)
        <label class="checkbox-inline">
            <input type="checkbox" @if(isset($user) and $user!=null and $user->getDataVal($data_value->id,$option->id))
            checked @endif name="value_{{$data_value->id}}[]" value="{{$option->id}}">{{$option->value}}</label>
        @endforeach
        @endif
    </div>
</div>
@endforeach