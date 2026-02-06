@foreach ($data as $key => $reference)
    <div class="col-md-{{ isset($col) ? $col : '6' }} {{ isset($discaform) ? $discaform : '' }}" style="display: {{(((isset($user) and isset($discaform)) and $user->pbepersondiscap == 0) || (!isset($user) and isset($discaform))) ? "none":"block" }}">
        <div class="form-group">
            <label>
                {{ $reference->name }} <span class="text-danger"> {{ $reference->required ? ' *' : '' }}</span>
            </label>

            @if ($reference->type_data_id == 169)
                @php
                    $is_active = false;
                    $option_id = 0;
                    $other_input_label = "¿Cuál...?";
                @endphp
                <select {{ $reference->required ? 'required' : '' }} {{ isset($disabled) ? $disabled : '' }} id="option_id-{{ $reference->id }}"
                    data-name="{{ $reference->name }}" data-type="{{ $reference->type_data_id }}" name="static_data[]"
                    data-section="{{ $reference->section }}"
                    class="form-control form-control-sm input_user_ad data_input_select {{$reference->required ? 'required' : ''}}" data-id="{{ $reference->id }}">
                    <option value="">Seleccione...</option>
                    @foreach ($reference->options()->where('status',1)->get() as $opt)
                        @php
                            if (
                                isset($user) and
                                $user != null and
                                $user->getDataVal($reference->id, $opt->id) and
                                $opt->active_other_input
                            ) {
                                $is_active = true;
                                $option_id = $opt->id;
                            }
                            if ($opt->active_other_input) {
                                $other_input_label = $opt->other_input_label;
                            }
                        @endphp
                        <option data-active_other="{{ $opt->active_other_input }}"
                            {{ (isset($user) and $user->getDataVal($reference->id, $opt->id)) ? 'selected' : '' }}
                            value="{{ $opt->id }}">{{ $opt->value }}</option>
                    @endforeach
                </select>
                @include('myforms.components_user.input_other_aditional_data')
            @elseif($reference->type_data_id == 170)
                @php
                    $is_active = false;
                    $option_id = 0;
                    $other_input_label = "¿Cuál...?";
                @endphp
               
                @foreach ($reference->options()->where('status',1)->get() as $opt)
                    @php
                        if (
                            isset($user) and
                            $user != null and
                            $user->getDataVal($reference->id, $opt->id) and
                            $opt->active_other_input
                        ) {
                            $is_active = true;
                            $option_id = $opt->id;
                        }
                        if ($opt->active_other_input) {
                            $other_input_label = $opt->other_input_label;
                        }
                    @endphp
                   <br> 
                   <input {{ $reference->required ? 'required' : '' }} class="input_user_ad {{$reference->required ? 'required' : ''}}" {{isset($disabled) ? $disabled : ''}} 
                   id="option_id-{{$opt->id}}" data-name="{{$reference->name}}" 
                   data-type="{{$reference->type_data_id}}" name="static_data-{{$reference->id}}" 
                   data-active_other="{{$opt->active_other_input}}" 
                   data-section="{{$reference->section}}" data-id="{{$reference->id}}" 
                   value="{{ $opt->value }}"
                   {{ (isset($user) and $user->getDataVal($reference->id, $opt->id)) ? 'checked' : '' }}
                   type="checkbox" data-option="{{ $opt->id }}"> 
                   {{$opt->value}} 
                @endforeach        
                @include('myforms.components_user.input_other_aditional_data')
     
            @else
            @if(isset($reference->options[0]))
                <input {{ $reference->required ? 'required' : '' }} {{ isset($disabled) ? $disabled : '' }} data-reference_id="{{ $reference->id }}"
                    data-name="{{ $reference->name }}" data-option="{{ $reference->options[0]->id }}"
                    data-type="{{ $reference->type_data_id }}" name="static_data[]"
                    data-section="{{ $reference->section }}" type="text"
                    @if (isset($user) and isset($reference->options[0]) and $reference->options[0] and $user->getDataVal($reference->id, $reference->options[0]->id)) value="{{ $user->getDataVal($reference->id, $reference->options[0]->id)->value }}" @endif
                    class="form-control form-control-sm input_user_ad {{$reference->required ? 'required' : ''}}">
            @else
            <label>Error en la opción</label>
            @endif
            @endif

        </div>
    </div> 
@endforeach
