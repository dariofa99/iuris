	<div class="col-md-12 div_salas_alternas" id="sala_alterna_num_{{$cont}}" data-numusers="{{$numusers}}">
           <h5 class="box-title">
                <label>
                   Sala alterna {{$cont}}
                </label>
                <a href="#" class="btn_change_doc_exp btn_salas_alternas_options" data-function="entry" >Entrar</a>
                <a href="#" class="btn_change_doc_exp btn_salas_alternas_options" data-function="copy" data-link="" >Copiar link</a>
                @if($cont > 1)
                <a href="#" class="btn_change_doc_exp btn_salas_alternas_options" data-function="delete" data-info="{{$cont}}" id="btn_eliminar_sala_alterna_{{$cont}}">Eliminar</a>
                @endif
           </h5>

           <table class="table" id="table_list_user_salasalternas{{$cont}}">
               <thead>
                   <th>
                   </th>
                   <th>
                       Nombres
                   </th>
                   <th>
                       Correo
                   </th>
                   <th>
                   Tipo
                   </th>
               </thead>
               <tbody>
               @foreach($conciliacion->usuarios as $key => $user)
                   <tr class="salas_alternas_user_{{$user->idnumber}}" id="trsala_alterna_{{$cont}}_{{$user->idnumber}}">
                       <td><input type="checkbox" class="salas_alternas_check_user salas_alternas_check_user_{{$user->idnumber}}" id="sala_alterna_{{$cont}}_{{$user->idnumber}}" value="{{$user->idnumber}}" data-room="sala_alterna_{{$cont}}" ></td>
                       <td>{{$user->name}} {{$user->lastname}}</td>
                       <td>{{$user->email}}</td>
                       <td>{{$user->tipo_conciliacion()->where('tipo_usuario_id',$user->pivot->tipo_usuario_id)->first()->ref_nombre}}</td>
                   </tr>
               @endforeach
               </tbody>
           </table>
	</div>
