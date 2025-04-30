@if (getAditionalDataByShortName($shortname, $table))
    @php
        $aditional_data = getAditionalDataByShortName($shortname, $table);
        $options = getAditionalDataByShortName($shortname, $table)->options;
    @endphp

    @php
        $is_active = false;
        $option_id = 0;
    @endphp

    @foreach ($options as $option)
        @php
            if (
                isset($user) and
                $user != null and
                $user->getDataVal($aditional_data->id, $option->id) and
                $option->active_other_input
            ) {
                $is_active = true;
                $option_id = $option->id;
            }
        @endphp

        <input
            {{ $user->getDataVal($aditional_data->id, $option->id) ? 'checked' : '' }}
            type="radio" name="n{{ $shortname }}" value="{{ $option->value }}">



        {{ $option->value }}

        {{--  {{$parte_solicitante->getDataValWShort($shortname)->id}}

                        {{$option->id}} --}}
    @endforeach
@else
    Sin datos
@endif



