<div class="row">
    <div class="col-md-8">
        <div class="form-group form_store">
            <label for="description">Chat</label>
           {{--<select class="form-control input-select" name="select" id="chatroomconciliacion" data-id="60" style="display: block;">
                <option value="conciliacion-{{$conciliacion->id}}">Todos</option>
                @foreach($conciliacion->usuarios as $key => $user)
                <option value="conciliacion-{{$conciliacion->id}}{{$user->idnumber}}">{{$user->name}} {{$user->lastname}}</option>
                @endforeach
            </select>--}}
            <input type="hidden" id="tokenc" value="">
            <div class="embed-responsive embed-responsive-4by3" style=" height: 400px;" id="chat-conciliacion">
                @include('myforms.conciliaciones.componentes.chat_room_ajax',
                ['chatroom'=>'conciliacion-'.$conciliacion->id])
            </div> 
        </div>    
    </div>
    <div class="col-md-4">

        @php
            $us = [];
            foreach ($conciliacion->usuarios as $key => $usuario) {
                if(currentUserInConciliacion($conciliacion->id,['solicitada'])){                    
                    if($usuario->pivot->tipo_usuario_id != 205 and $usuario->pivot->tipo_usuario_id != 195 and $usuario->pivot->tipo_usuario_id != 196){
                       if($conciliacion->getUser(199)->id != $usuario->id){
                        $us[$usuario->idnumber] = $usuario->name." ".$usuario->lastname;
                       }
                        
                    }
                }elseif(currentUserInConciliacion($conciliacion->id,['solicitante'])){
                    if($usuario->pivot->tipo_usuario_id != 197 and $usuario->pivot->tipo_usuario_id != 198){                        
                        $us[$usuario->idnumber] = $usuario->name." ".$usuario->lastname;
                    }
                }else{
                    $us[$usuario->idnumber] = $usuario->name." ".$usuario->lastname;
                }
            }
        @endphp
        <ul class="ul-users-chat" id="ul-users-chat">
            <li class="btn_open_chat" data-idnumber="{{$conciliacion->id}}" id="btn_open_chat-{{$conciliacion->id}}">
                <small> 
                    Todos{{-- <span style="float: right" class="badge">1</span> --}}
                </small>
            </li>
        @foreach($us as $key => $user)
            @if($key != Auth::user()->idnumber)
            <li class="btn_open_chat" data-idnumber="{{$key}}" id="btn_open_chat-{{$key}}">
            <small> {{$user}}{{-- <span style="float: right" class="badge">1</span> --}}</small>
            </li>  
            @endif
        @endforeach
        </ul>
       
    </div>
</div>
