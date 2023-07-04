<ul class="navbar-nav ml-auto">
     
  @can('ver_conectados_chat')
  <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
          <span class="badge badge-danger navbar-badge lbl_chatCountUsers">0</span>
        </a> 
       
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="list_users_login">     

      {{--     <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-green"><i class="fas fa-circle"></i></span>
                </h3>
                
              </div>
            </div>
            <!-- Message End -->
          </a> --}}

          <div class="dropdown-divider"></div>


          <div class="dropdown-divider"></div>
          {{-- <a href="#" class="dropdown-item dropdown-footer">Cargando usuarios...</a> --}}
        </div>        
      </li>
      @endcan
      <!-- Notifications Dropdown Menu -->
   <li class="nav-item dropdown">
        <a class="nav-link btn_unread_notifications" id="btn_unread_notifications" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge" id="bgnumnotifications">
            {{count(auth()->user()->unreadNotifications )}}
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">
           Notificaciones</span>

            <div class="notification_content">
             {{--  @foreach(auth()->user()->notifications()->limit(7)->get() as $key => $notification)
         
              <div class="dropdown-divider"></div>

              <a class="{{$notification->read_at != null ? 'btn_read_noti':'btn_not_not'}} "  @if(isset($notification->data['url']) and $notification->data['url'] and $notification->data['url']!='undefined') href="{{$notification->data['url']}}"  @endif class="dropdown-item">
                <i @if(isset($notification->data['icon']) and $notification->data['icon']
                 and $notification->data['icon']!='undefined')
                   class="{{$notification->data['icon']}} mr-2 itemnot" @else class="fas fa-bell itemnot" @endif>                  
                  </i>
                     <small class="itemnot">{{$notification->data['message']}}</small>       
       
                <span class="itemnot float-right text-muted text-sm">{{$notification->data['created_at']}}</span>
              
              </a> 
         
             
              @endforeach --}}
            </div>

       
         
       
          <div class="dropdown-divider"></div>
            <a href="/admin/users/view/notifications" class="dropdown-item dropdown-footer">Ver todas</a> 
        </div>
      </li>
      <!-- 
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"><i
            class="fas fa-th-large"></i></a>
      </li>
       -->
</ul>