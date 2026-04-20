

@foreach ($data as $key => $reference)
    <tr>
        <td width="50%">
            <label>
                {{ $reference->name }}
            </label>
        </td>
        <td>
            @if ($reference->type_data_id == 169 || $reference->type_data_id == 170)
                @php
                    $is_active = false;
                    $option_id = 0;
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
                    @endphp
                
                    <input class="input_user_ad" {{ isset($disabled) ? $disabled : '' }}
                        id="option_id-{{ $opt->id }}" data-name="{{ $reference->name }}"
                        data-type="{{ $reference->type_data_id }}" name="static_data-{{ $reference->id }}"
                        data-active_other="{{ $opt->active_other_input }}" data-section="{{ $reference->section }}"
                        data-id="{{ $reference->id }}" value="{{ $opt->value }}"
                        {{ (isset($user) and $user->getDataVal($reference->id, $opt->id)) ? 'checked' : '' }}
                        type="radio" data-option="{{ $opt->id }}">
                        {{ $opt->value }}<br>
                    
                @endforeach

                <label @if (!$is_active) style="display: none" @endif
                    id="lbl_other-{{ $reference->id }}">¿Cuál...?</label><br>
                @if ($is_active)
                    <label>
                        @if (isset($user) and $user != null and $user->getDataVal($reference->id, $option_id))
                            {{ $user->getDataVal($reference->id, $option_id)->value_is_other }}
                        @endif
                    </label>
                @endif
            @else
                
                @if (isset($reference->options[0]))
                    @if (isset($user) and $user != null and $user->getDataVal($reference->id, $reference->options[0]->id))
                        {{ $user->getDataVal($reference->id, $reference->options[0]->id)->value }}
                    @endif
                @else
                    <label>Error en la opción</label>
                @endif
            @endif
        </td>
    </tr>




@endforeach
