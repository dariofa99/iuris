<div class="row" >
    <div class="col-md-2">
        @if(auth()->user()->can('crea_comentarios_conciliacion'))
        <input type="button" id="btn_agregar_comentario" value="Agregar comentario" class="btn btn-primary btn-sm btn-block">
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default" style="margin-bottom: 25px;">
            <div class="panel-body bodyuser_not">
                <label>Seleccione usuarios</label>
           
                <table class="table">                   
                    <tbody>
                        @php
                        $estudiantes=[];                        
                            foreach ($conciliacion->usuarios as $key => $usert) {                           
                                $estudiantes[$usert->id] = $usert;
                            }                          
                        @endphp
        
                        @foreach($estudiantes as $key => $user)
                        <tr id="user_{{$user->id}}" style="cursor:pointer" class="fila_usuarios_not" data-email="{{$user->email}}" data-id="{{$user->id}}">
                          <td>
                            <div class="pull-left image_us_not">
        
                                <img src="{{ is_file(public_path('thumbnails/'.$user->image)) ? asset('thumbnails/'.$user->image ) : asset('thumbnails/default.jpg' )}}" class="img-circle" alt="User Image">
                                  
                               </div>
                          </td>
                            <td>{{$user->name}} {{$user->lastname}}</td>
                          
                            <td>                   
                               @php
                                $roles_cadena = '';
                               @endphp
                                @foreach($user->tipo_conciliacion()->where([
                                    'conciliacion_has_user.conciliacion_id'=>$conciliacion->id
                                ])->get() as $type)
                               @php
                                $roles_cadena .= $type->ref_nombre."/"
                               @endphp                      
                                @endforeach
                                <small> {{trim($roles_cadena, '/')}}</small>
                              
                            </td>
                            
                            
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

       
    </div>
    <div class="col-md-8">
        <div class="panel panel-default" style="margin-bottom: 25px;">
            <div class="panel-body">
                <label> Seleccione un formato </label>
                <select id="categoria_notifica__id"  class="form-control form-control-sm">  
                    <option value="">Seleccione...</option>            
                                       
                </select>
         
                
              
                <div class="row" id="row_mail_not">                    
                    
                </div>     
                <div id="content_notificacion_correo" class="summernote">
                        
                </div>
                <div>
                    <button disabled id="btn_env_not" class="btn btn-primary">Enviar notificación</button>
                </div>
            </div>
        </div>

          
           
    </div>
</div>
<div class="row">
    <div class="col-md-12 table-responsive no-padding">
        <table class="table" id="table_list_comentarios">
            <thead>
                <th>
                    Comentario
                </th>
                <th>
                    Creado por
                </th>
                <th>
                    Fecha creación
                </th>
                <th>
                    Acciones
                </th>
            </thead>
            <tbody>
               @include('myforms.conciliaciones.componentes.solicitud_comentarios_ajax')
            </tbody>
        </table>
    </div>
</div>