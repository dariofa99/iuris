
<br><label @if (!$is_active) style="display: none" @endif
                    id="lbl_other-{{ $reference->id }}">{{ $other_input_label }}</label>
                <input required {{ isset($disabled) ? $disabled : '' }} id="value_other_text-{{ $reference->id }}"
                    @if ($is_active) type="text" @else type="hidden" @endif
                    @if (isset($user) and $user != null and $user->getDataVal($reference->id, $option_id)) value="{{ $user->getDataVal($reference->id, $option_id)->value_is_other }}" @endif
                    class="form-control form-control-sm" placeholder="{{ $other_input_label }}"> 
