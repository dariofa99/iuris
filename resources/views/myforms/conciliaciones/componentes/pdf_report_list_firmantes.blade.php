@foreach($conciliacion->usuarios as $key => $user)
                    <tr>
  
                        <td>{{$user->name}} {{$user->lastname}}</td>
                        <td>
                            <input type="hidden" disabled id="input_tipouser-{{$user->id}}-{{$key}}" type="text" name="tipo_usuario_id[]"
                             value="{{$user->tipo_conciliacion()->where('tipo_usuario_id',$user->pivot->tipo_usuario_id)->first()->id}}">
                            {{$user->tipo_conciliacion()
                            ->where('tipo_usuario_id',$user->pivot->tipo_usuario_id)->first()->ref_nombre}}
                            </td>
                        <td>

                            <select @if(array_key_exists($user->id."-".$user->pivot->tipo_usuario_id, $tipos_estados)  and $tipos_estados[$user->id."-".$user->pivot->tipo_usuario_id] == 1 ) disabled   @endif data-oldnew="{{ in_array($user->id,$ids) ? "old" : "new"}}" class="form-control select_type_firma" data-id="{{$user->id}}" data-userid="{{$user->id}}-{{$key}}">
                                <option value="">No asignado</option>
                            @foreach($types_firma_users as $key_id => $type_firma_user)
                                <option   @if(array_key_exists($user->id."-".$user->pivot->tipo_usuario_id, $tipos)  and $tipos[$user->id."-".$user->pivot->tipo_usuario_id] == $key_id ) selected   @endif value="{{$key_id }}">{{$type_firma_user}} </option>
                            @endforeach
                            </select>    
                             
                            @if(array_key_exists($user->id."-".$user->pivot->tipo_usuario_id, $tipos_estados)  and $tipos_estados[$user->id."-".$user->pivot->tipo_usuario_id] == 1 )
                             <small>
                                <i>
                                    Ya se firmó el documento
                                    @if (auth()->user()->can('revoc_firma_formatos_conciliac') and $tipos[$user->id."-".$user->pivot->tipo_usuario_id]!=209)
                                        <a href="#" class="btn_revock_tipo_firmas" data-id="{{$pivots_id[$user->id."-".$user->pivot->tipo_usuario_id]}}">Revocar firma </a>
                                    @endif 
                                    
                                    
                               </i>    
                            </small> 
                            @endif
                        </td>
                        <td>
                            <input type="hidden" disabled id="input_selustipofirm-{{$user->id}}-{{$key}}" name="tipo_firmante[]">
                            <input type="hidden" disabled id="input_selusfirm-{{$user->id}}-{{$key}}" name="user_id[]">
                            <input @if(array_key_exists($user->id."-".$user->pivot->tipo_usuario_id, $tipos_estados)  and $tipos_estados[$user->id."-".$user->pivot->tipo_usuario_id] == 1 ) disabled   @endif
                             {{ (in_array($user->id,$ids) and (array_key_exists($user->id."-".$user->pivot->tipo_usuario_id, $tipos)  and $tipos[$user->id."-".$user->pivot->tipo_usuario_id] == 209 ))  ? "checked" : "disabled"}} 
                             data-userid="{{$user->id}}-{{$key}}" 
                             id="check_selusfirm-{{$user->id}}-{{$key}}" class="check_selusfirm"
                             data-oldnew="{{ in_array($user->id,$ids) ? "old" : "new"}}" value="{{$user->id}}" type="checkbox" name="user_email_id[]">

                        </td>
                        <td style="display: none" class="volver_enviar_mail">
                            <input class="input_type_user"
                            {{ (in_array($user->id,$ids) and (array_key_exists($user->id."-".$user->pivot->tipo_usuario_id, $tipos) 
                             and $tipos[$user->id."-".$user->pivot->tipo_usuario_id] == 209 ))? "" : "disabled"}} 
                            type="hidden" id="inusre-{{$user->id}}-{{$key}}" type="text" name="type_user_id[]" 
                            value="{{$user->tipo_conciliacion()->where('tipo_usuario_id',$user->pivot->tipo_usuario_id)->first()->id}}">
                            <input data-input_id="{{$user->id}}-{{$key}}" class="check_selusvolverfirm" 
                            {{ (in_array($user->id,$ids) and (array_key_exists($user->id."-".$user->pivot->tipo_usuario_id, $tipos)  and $tipos[$user->id."-".$user->pivot->tipo_usuario_id] == 209 ))? "checked" : "disabled"}} 
                            data-oldnew="{{ in_array($user->id,$ids) ? "old" : "new"}}" value="{{$user->id}}" type="checkbox" name="email_user_id[]">
                        </td>
                        
        </tr>
@endforeach