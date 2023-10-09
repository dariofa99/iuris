<table style="text-align:right;width:100%;font-size:14px;">
                 {{--    <tr>
                        <td>

                            <label>No de Expedientes <span class="badge bg-blue" id="badgeCount">
                                    {{ number_format(000, 0, '.', '.') }} </span></label>

                        </td>
                    </tr> --}}
                    <tr>
                        <td>

                            @if (count($count_colors) > 0 and $count_colors != '')
                                <div>
                                    <label>Asesorías asignadas</label><br>
                                    <span class="badge btn_search_color" id="verde"
                                        style="border:1px solid #2ECC71">{{ $count_colors[0]->verde === null ? 0 : $count_colors[0]->verde }}</span>
                                    <span class="badge btn_search_color" id="amarillo"
                                        style="border:1px solid #F4D03F">{{ $count_colors[0]->amarillo === null ? 0 : $count_colors[0]->amarillo }}</span>
                                    <span class="badge btn_search_color" id="rojo"
                                        style="border:1px solid #CB4335">{{ $count_colors[0]->rojo === null ? 0 : $count_colors[0]->rojo }}</span>
                                    <span class="badge btn_search_color" id="gris"
                                        style="border:1px solid gray">{{ $count_colors[0]->gris === null ? 0 : $count_colors[0]->gris }}</span>
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>
            