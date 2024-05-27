<div class="col-md-{{ isset($col) ? $col : '6' }} {{ isset($discaform) ? $discaform : '' }}"
    style="display: {{ (isset($model) and isset($discaform) and $model->pbepersondiscap == 0) ? 'none' : 'block' }}">
    <div class="form-group">
        <label>
            {{ $reference->name }}
        </label>

        @if ($reference->type_data_id == 251)
            @php
                $is_active = false;
                $option_id = 0;
            @endphp
            <select {{ isset($disabled) ? $disabled : '' }} id="option_id-{{ $reference->id }}"
                data-name="{{ $reference->name }}" data-type="{{ $reference->type_data_id }}" name="static_data[]"
                data-section="{{ $reference->section }}"
                class="form-control form-control-sm input_user_ad data_input_select" data-id="{{ $reference->id }}">
                <option value="">Seleccione...</option>
                @foreach ($reference->options as $opt)
                    @php
                        if (
                            isset($model) and
                            $model != null and
                            $model->getDataVal($reference->id, $opt->id) and
                            $opt->active_other_input
                        ) {
                            $is_active = true;
                            $option_id = $opt->id;
                        }
                    @endphp
                    <option data-active_other="{{ $opt->active_other_input }}"
                        {{ (isset($model) and $model->getDataVal($reference->id, $opt->id)) ? 'selected' : '' }}
                        value="{{ $opt->id }}">{{ $opt->value }}</option>
                @endforeach
            </select>
            @include('myforms.categorias.partials.ajax.value_isotherquestion')
        @elseif($reference->type_data_id == 170)
            @php
                $is_active = false;
                $option_id = 0;
            @endphp

            @foreach ($reference->options as $opt)
                @php
                    if (
                        isset($model) and
                        $model != null and
                        $model->getDataVal($reference->id, $opt->id) and
                        $opt->active_other_input
                    ) {
                        $is_active = true;
                        $option_id = $opt->id;
                    }
                @endphp
                <br>
                <input class="input_user_ad" {{ isset($disabled) ? $disabled : '' }}
                    id="option_id-{{ $opt->id }}" data-name="{{ $reference->name }}"
                    data-type="{{ $reference->type_data_id }}" name="static_data-{{ $reference->id }}"
                    data-active_other="{{ $opt->active_other_input }}" data-section="{{ $reference->section }}"
                    data-id="{{ $reference->id }}" value="{{ $opt->value }}"
                    {{ (isset($model) and $model->getDataVal($reference->id, $opt->id)) ? 'checked' : '' }}
                    type="checkbox" data-option="{{ $opt->id }}">
                {{ $opt->value }}
            @endforeach

            @include('myforms.categorias.partials.ajax.value_isotherquestion')
        @elseif($reference->type_data_id == 169)
            @php
                $is_active = false;
                $option_id = 0;
            @endphp

            @foreach ($reference->options as $opt)
                @php
                    if (
                        isset($model) and
                        $model != null and
                        $model->getDataVal($reference->id, $opt->id) and
                        $opt->active_other_input
                    ) {
                        $is_active = true;
                        $option_id = $opt->id;
                    }
                @endphp
                <br>
                <input class="input_user_ad" {{ isset($disabled) ? $disabled : '' }}
                    id="option_id-{{ $opt->id }}" data-name="{{ $reference->name }}"
                    data-type="{{ $reference->type_data_id }}" name="static_data-{{ $reference->id }}"
                    data-active_other="{{ $opt->active_other_input }}" data-section="{{ $reference->section }}"
                    data-id="{{ $reference->id }}" value="{{ $opt->value }}"
                    {{ (isset($model) and $model->getDataVal($reference->id, $opt->id)) ? 'checked' : '' }}
                    type="radio" data-option="{{ $opt->id }}">
                {{ $opt->value }}
            @endforeach

            @include('myforms.categorias.partials.ajax.value_isotherquestion')
        @else
            <input {{ isset($disabled) ? $disabled : '' }} data-reference_id="{{ $reference->id }}"
                data-name="{{ $reference->name }}" data-option="{{ $reference->options[0]->id }}"
                data-type="{{ $reference->type_data_id }}" name="static_data[]"
                data-section="{{ $reference->section }}" type="text"
                @if (isset($model) and $reference->options[0] and $model->getDataVal($reference->id, $reference->options[0]->id)) value="{{ $model->getDataVal($reference->id, $reference->options[0]->id)->value }}" @endif
                class="form-control form-control-sm input_user_ad">
        @endif

    </div>
</div>
