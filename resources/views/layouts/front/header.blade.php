<header class="main-header">

    <!-- Logo -->
    <a href="{{ url('/oficina/solicitante')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>LT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Iuris</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav" id="menu-notification">


  <!-- aqui empiezan las notificaciones se puede descomentar...........................................  -->
             <!-- Messages: style can be found in dropdown.less-->
            
              @include('layouts.front.notifications',[
              'user'=>Auth::user()
            ])
    
 <!-- Messages fin:-->

          <!-- Notifications: style can be found in dropdown.less -->
          
         {{--  @if(count(Auth::user()->getNotificaciones('casos_solicitados'))>0)
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">{{count(Auth::user()->getNotificaciones('casos_solicitados'))}}</span>
            </a>
            <ul class="dropdown-menu">
            <!--  <li class="header">Tienes {{count(Auth::user()->getNotificaciones('casos_solicitados'))}} solicitudes de casos</li> -->
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
               @foreach (Auth::user()->getNotificaciones('casos_solicitados') as $notificacion)
                    <li >
                    <a href="/expedientes/{{$notificacion->asignacion_estudiante->asigexp_id}}/edit">
                      <i class="fa fa-folder text-aqua"></i>
                      Tienes una solicitud de {{$notificacion->docente->name}} {{$notificacion->docente->lastname}}                    
                    </a>
                  </li>
                @endforeach 

                
                 
                </ul> 
              </li>
              {{-- <li class="footer"><a href="#">View all</a></li> 
            </ul>
          </li>
          @endif --}}

           <!-- Notifications: Citaciones -->
          
         {{-- 
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"  @if(count(Auth::user()->unreadNotifications)>0) id="btn_read_notifications" @endif>
              <i class="fa fa-bell-o"></i>
              @if(count(Auth::user()->unreadNotifications)>0)
               <span  class="label label-danger">{{count(Auth::user()->unreadNotifications)}}</span>
              @endif
            </a>
            <ul class="dropdown-menu">
            <!--  <li class="header">Tienes {{count(Auth::user()->unreadNotifications)}} solicitudes de casos</li> -->
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                @foreach (Auth::user()->Notifications as $notification)
                    <li class="lbl_notification {{($notification->read_at== '' ? 'lbl_notificationunread' : '')}}">
                    
                    <a href="{{$notification->data["link_to"]}} " >                     
                    {{$notification->data["mensaje"]}}                    
                    </a>

                  </li>

                
                  
                @endforeach

  
                 
                </ul> 
              </li>
              {{-- <li class="footer"><a href="#">View all</a></li> 
            </ul>
          </li> --}}
         


          <!-- Tasks: style can be found in dropdown.less -->
      {{--     <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>  --}}

          <!-- aqui finalizan las notificiones ............................................................-->






          
        
        </ul>
      </div>
    </nav>
  </header>
