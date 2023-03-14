
@if(count(getReferencesStaticTableBySection($section,'conciliaciones'))>0)
<div class="row">
@foreach (getReferencesStaticTableBySection($section,'conciliaciones') as $reference)

<div class="col-md-6">
    <div class="form-group">
        <label >
           {{$reference->display_name}}       
        </label>
 
        @if($reference->type_data_id == 169)       
        <select data-name="{{$reference->name}}" data-type="{{$reference->type_data_id}}" name="static_data[]"  data-section="{{$section}}" class="form-control required insert_adv" >
            <option value="">Seleccione...</option>           
            @foreach ($reference->options as $opt)
                <option {{$conciliacion->getStaticDataVal($reference->name,$section,$opt->id) ? "selected" : ""}} value="{{$opt->id}}">{{$opt->value}}</option>
            @endforeach
        </select>
        @elseif($reference->type_data_id == 239)
        <textarea rows="5"  data-name="{{$reference->name}}" data-option="{{$reference->options[0]->id}}" data-type="{{$reference->type_data_id}}"  name="static_data[]"   data-section="{{$section}}" required 
            class="form-control required  insert_adv"
        @if(currentUserInConciliacion($conciliacion->id,['autor','auxiliar'])  
        and ($conciliacion->estado_id==174 || $conciliacion->estado_id==176 || $conciliacion->estado_id==194 ))@endif>@if($conciliacion->getStaticDataVal($reference->name,$section)){{$conciliacion->getStaticDataVal($reference->name,$section)->value}}@endif</textarea>
        
        
        @else

        <input  data-name="{{$reference->name}}" data-option="{{$reference->options[0]->id}}" data-type="{{$reference->type_data_id}}"  name="static_data[]"   data-section="{{$section}}" required  type="text"
        @if($conciliacion->getStaticDataVal($reference->name,$section)) 
        value="{{$conciliacion->getStaticDataVal($reference->name,$section)->value}}" @endif
        @if(currentUserInConciliacion($conciliacion->id,['autor','auxiliar'])  
        and ($conciliacion->estado_id==174 || $conciliacion->estado_id==176 || $conciliacion->estado_id==194 ))
            class="form-control required  insert_adv"
            @else      
        class="form-control required insert_adv"                   
        @endif>
        @endif

    </div>
</div>
@endforeach

</div>
@endif







