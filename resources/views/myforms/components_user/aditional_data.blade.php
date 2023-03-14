<div class="content_aditional_data">
@foreach($data as $key => $reference)

<div class="col-md-6">
    <div class="form-group">
        <label >
           {{$reference->name}}       
        </label>
 
        @if($reference->type_data_id == 169)   
            @php
            $is_active = false;
            $option_id = 0 ;
            @endphp    
        <select id="option_id-{{$reference->id}}" data-name="{{$reference->name}}" data-type="{{$reference->type_data_id}}" name="static_data[]"  data-section="{{$reference->section}}" class="form-control insert_adv data_input_select" data-id="{{$reference->id}}">
            <option value="">Seleccione...</option>            
            @foreach ($reference->options as $opt)
                @php
                if(isset($user) and $user!=null and $user->getDataVal($reference->id,$opt->id) and
                $opt->active_other_input){
                $is_active = true ;
                $option_id = $opt->id ;
                }
            @endphp
                <option data-active_other="{{$opt->active_other_input}}" {{ (isset($user) and $user->getDataVal($reference->id,$opt->id)) ? "selected" : ""}} value="{{$opt->id}}">{{$opt->value}}</option>
            @endforeach
        </select>

        <label @if(!$is_active) style="display: none" @endif id="lbl_other-{{$reference->id}}">¿Cuál...?</label>

        <input id="value_other_text-{{$reference->id}}" @if($is_active) type="text" @else type="hidden" @endif
            @if(isset($user) and $user!=null and $user->getDataVal($reference->id,$option_id))
        value="{{$user->getDataVal($reference->id,$option_id)->value_is_other}}" @endif
        class="form-control form-control-sm" placeholder="¿Cuál...?">


    @else

      <input  data-name="{{$reference->name}}" data-option="{{$reference->options[0]->id}}"
       data-type="{{$reference->type_data_id}}"  name="static_data[]"  
        data-section="{{$reference->section}}" required  type="text"
        @if(isset($user)  and $reference->options[0] and $user->getDataVal($reference->id,$reference->options[0]->id)) 
        value="{{$user->getDataVal($reference->id,$reference->options[0]->id)->value}}"
        @endif             
        class="form-control insert_adv"> 

    @endif

    </div>
</div>
@endforeach    
</div>