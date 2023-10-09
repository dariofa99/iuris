
           <li class="dropdown messages-menu">
            <a id="{{(count($user->unreadNotifications)>0 ? 'btn_read_notifications':'btn_read' )}}" href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span id="lbl_notify_count" class="label label-danger" style="display:{{(count($user->unreadNotifications)<=0 ? 'none':'block' )}}">
              {{count($user->unreadNotifications)}}
              </span>
            </a>






          <ul class="dropdown-menu">
              <li class="header">Notificaciones</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                       @foreach ($user->Notifications as $notification)
                       <li class="lbl_notification {{($notification->read_at== '' ? 'lbl_notificationunread' : '')}}"><!-- start message -->
                    <a href="{{$notification->data["link_to"]}}">
                   {{--    <div class="pull-left">
                       
                     
                        {!! HTML::image('thumbnails/'.currentUser()->image,'User Image', array('class' => 'img-circle')) !!}


                      </div> --}}
                     <h4>
                        {{$notification->data["type_notification"]}}
                        <small><i class="fa fa-clock-o"> </i> {{TiempoTrans($notification->created_at)}}</small>
                      </h4>
                      <p> {{substr($notification->data["mensaje"], 0, 100) }} </p> 
                      
                    </a>
                  </li>
                  <!-- end message -->
                  
                @endforeach

               
                </ul>
              </li>
              {{-- <li class="footer"><a href="#">See All Messages</a></li> --}}
            </ul>


          </li> 
          <li>
          
          {!! link_to_route('logout.index', $title = 'Cerrar sesión', $parameters = $user->id) !!}
               
          </li>
             <!-- User Account: style can be found in dropdown.less -->
       {{--    <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">              
              <img src="{{asset('thumbnails/'.$user->image)}}" alt="User Image" class="user-image">             
              <span class="hidden-xs">{{  $user->name  }}</span>
            </a>
            <ul class="dropdown-menu">
               <li class="user-header">
               <img src="{{asset('thumbnails/'.$user->image)}}" alt="User Image" class="img-circle">
                 <p>
                  {{  $user->name  }} 
                  <small>Miembro desde {{   TiempoTrans(  $user->created_at  )   }} </small>
                </p>
              </li>
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="expedientes">Mis Casos</a>
                  </div>
                </div>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                 {!! link_to_route('users.edit', $title = 'Perfil', $parameters = $user->id, $attributes = ['class'=>'btn btn-default btn-flat']) !!}
                </div>
                <div class="pull-right">
                {!! link_to_route('logout.index', $title = 'Salir', $parameters = $user->id, $attributes = ['class'=>'btn btn-default btn-flat']) !!}
                </div>
              </li>
            </ul>
          </li> --}}
          <!-- Control Sidebar Toggle Button -->
          <li>
             <!-- <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>   decomentar para habilitar barra derecha --> 
          </li>