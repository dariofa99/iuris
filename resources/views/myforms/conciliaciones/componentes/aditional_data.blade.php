@foreach ($data as $key => $reference)
    <div class="col-md-{{ isset($col) ? $col : '6' }}">
        <div class="form-group">
            <label>
                {{ $reference->name }}{!! isset($required) ? '<span class="ast_required">*</span>' : '' !!}
            </label>

            @if ($reference->type_data_id == 169)
                @php
                    $is_active = false;
                    $option_id = 0;
                @endphp

                <select {{ isset($disabled) ? $disabled : '' }} id="option_id-{{ $reference->id }}"
                    data-name="{{ $reference->name }}" data-type="{{ $reference->type_data_id }}" name="static_data[]"
                    data-section="{{ $reference->section }}"
                    class="form-control form-control-sm input_user_ad data_input_select {{ isset($required) ? $required : '' }}"
                    data-id="{{ $reference->id }}">
                    <option value="">Seleccione...</option>
                    @foreach ($reference->options as $opt)
                        @php
                            if (isset($conciliacion) and $conciliacion != null and $conciliacion->getDataVal($reference->id, $opt->id) and $opt->active_other_input) {
                                $is_active = true;
                                $option_id = $opt->id;
                            }
                        @endphp
                        <option data-active_other="{{ $opt->active_other_input }}"
                            {{ (isset($conciliacion) and $conciliacion->getDataVal($reference->id, $opt->id)) ? 'selected' : '' }}
                            value="{{ $opt->id }}">{{ $opt->value }}</option>
                    @endforeach
                </select>

                <label @if (!$is_active) style="display: none" @endif
                    id="lbl_other-{{ $reference->id }}">¿Cuál...?</label>

                <input {{ isset($disabled) ? $disabled : '' }} id="value_other_text-{{ $reference->id }}"
                    @if ($is_active) type="text" @else type="hidden" @endif
                    @if (isset($conciliacion) and $conciliacion != null and $conciliacion->getDataVal($reference->id, $option_id)) value="{{ $conciliacion->getDataVal($reference->id, $option_id)->value_is_other }}" @endif
                    class="form-control form-control-sm {{ isset($required) ? $required : '' }}"
                    placeholder="¿Cuál...?">
            @elseif($reference->type_data_id == 239)
                <textarea rows="5" {{ isset($disabled) ? $disabled : '' }} data-reference_id="{{ $reference->id }}"
                    data-name="{{ $reference->name }}" data-option="{{ $reference->options[0]->id }}"
                    data-type="{{ $reference->type_data_id }}" name="static_data[]" data-section="{{ $reference->section }}"
                    class="form-control form-control-sm input_user_ad {{ isset($required) ? $required : '' }}">@if (isset($conciliacion) and
                            $reference->options[0] and
                            $conciliacion->getDataVal($reference->id, $reference->options[0]->id))
                    {{ $conciliacion->getDataVal($reference->id, $reference->options[0]->id)->value }}@endif</textarea>
            @else
                <input {{ isset($disabled) ? $disabled : '' }} data-reference_id="{{ $reference->id }}"
                    data-name="{{ $reference->name }}" data-option="{{ $reference->options[0]->id }}"
                    data-type="{{ $reference->type_data_id }}" name="static_data[]"
                    data-section="{{ $reference->section }}" type="text"
                    @if (isset($conciliacion) and
                            $reference->options[0] and
                            $conciliacion->getDataVal($reference->id, $reference->options[0]->id)) value="{{ $conciliacion->getDataVal($reference->id, $reference->options[0]->id)->value }}" @endif
                    class="form-control form-control-sm input_user_ad {{ isset($required) ? $required : '' }}">
            @endif

        </div>
    </div>
@endforeach
