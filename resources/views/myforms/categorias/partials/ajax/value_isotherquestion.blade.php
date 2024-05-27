     <label @if (!$is_active) style="display: none" @endif
                id="lbl_other-{{ $reference->id }}">{{-- ¿Cuál o por qué...? --}}</label>

            <input {{ isset($disabled) ? $disabled : '' }} id="value_other_text-{{ $reference->id }}"
                @if ($is_active) type="text" @else type="hidden" @endif
                @if (isset($model) and $model != null and $model->getDataVal($reference->id, $option_id)) value="{{ $model->getDataVal($reference->id, $option_id)->value_is_other }}" @endif
                class="form-control form-control-sm" placeholder="¿Cuál o por qué...?">
  