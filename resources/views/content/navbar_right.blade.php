<ul class="navbar-nav ml-auto">

    @can('ver_conectados_chat')
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
                <span class="badge badge-danger navbar-badge lbl_chatCountUsers">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="list_users_login">
                <div class="dropdown-divider"></div>
                <div class="dropdown-divider"></div>
            </div>
        </li>
    @endcan
    <li class="nav-item dropdown">
        <a class="nav-link btn_unread_notifications" id="btn_unread_notifications" data-toggle="dropdown"
            href="#">
            <i class="far fa-bell"></i>
            @if (count(auth()->user()->unreadNotifications()->whereDate('created_at', '>=', '2023-09-14 21:00:00')->get()))
                <span class="badge badge-warning navbar-badge" id="bgnumnotifications">
                    {{ count(auth()->user()->unreadNotifications()->whereDate('created_at', '>=', '2023-09-14 21:00:00')->get()) }}
                </span>
            @endif
        </a>
        {{--      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="min-width:320px !important">
            <span class="dropdown-item dropdown-header">
                Notificaciones</span>
            <div class="notification_content">
                @foreach (auth()->user()->notifications()->whereDate('created_at', '>=', '2023-09-14 21:00:00')->limit(7)->get() as $key => $notification)                    
                    <div class="dropdown-divider"></div>
                    <a class="{{ $notification->read_at != null ? 'btn_read_noti' : 'btn_not_not' }} "
                        @if (isset($notification->data['url']) and $notification->data['url'] and $notification->data['url'] != 'undefined') href="{{ $notification->data['url'] }}" @endif
                        class="dropdown-item">
                        <i
                            @if (isset($notification->data['icon']) and $notification->data['icon'] and $notification->data['icon'] != 'undefined') class="{{ $notification->data['icon'] }} mr-2 itemnot" @else class="fas fa-bell itemnot" @endif>
                        </i>
                        <small class="itemnot">{{ $notification->data['message'] }}</small>
                        <span
                            class="itemnot float-right text-muted text-sm">{{ $notification->data['created_at'] }}</span>
                    </a>
                @endforeach
            </div>
            <div class="dropdown-divider"></div>
            <a href="/admin/users/view/notifications" class="dropdown-item dropdown-footer">Ver todas</a>
        </div> --}}
        <div class="dropdown-menu dropdown-menu-right glass-dropdown">

            <div class="glass-header">
                <span>🔔 Notificaciones </span>
                <span class="glass-badge">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            </div>

            <div class="glass-list">

                @foreach (auth()->user()->notifications()->limit(7)->get() as $notification)
                    <a class="glass-item {{ $notification->read_at ? 'read' : 'unread' }}"
                        @if (isset($notification->data['url']) && $notification->data['url'] != 'undefined') href="{{ $notification->data['url'] }}" @endif>

                        <div class="glass-icon">
                            <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }}"></i>
                        </div>

                        <div class="glass-content">
                            <div class="glass-text">
                                {{ $notification->data['message'] }}
                            </div>

                            <small class="glass-time">
                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                            </small>
                        </div>

                    </a>
                @endforeach

            </div>

            <div class="glass-footer">
                <a href="/admin/users/view/notifications">Ver todas</a>
            </div>

        </div>

    </li>
</ul>
