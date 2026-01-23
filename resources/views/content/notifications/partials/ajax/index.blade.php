{{-- @foreach ($notifications as $key => $notification)
<div class="p-3 mt-1 d-flex align-items-center bg-light border-bottom osahan-post-header">
        <div class="dropdown-list-image mr-3">
          
            <i
                @if (isset($notification->data['icon']) and $notification->data['icon'] and $notification->data['icon'] != 'undefined') class="{{ $notification->data['icon'] }} mr-2 itemnot" @else class="fas fa-bell itemnot" @endif>
            </i>
        </div>
        <div class="font-weight-bold mr-3">
            <div class="text-truncate">{{ $notification->data['message'] }}</div>
            <div class="small">
                {{ \Carbon\Carbon::parse($notification->data['created_at'])->diffForHumans() }}
                
            </div>
        </div>
 
    </div> 
@endforeach --}}




@foreach ($notifications as $notification)

<div class="notify-card {{ $notification->read_at ? 'read' : 'unread' }}"
     data-id="{{ $notification->id }}">

    {{-- LINK --}}
    <a class="notify-link flex-grow-1"
        @if(isset($notification->data['url']))
            href="{{ $notification->data['url'] }}"
        @endif
    >

        <div class="notify-icon">
            <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }}"></i>
        </div>

        <div class="notify-body">
            <div class="notify-message">
                {{ $notification->data['message'] }}
            </div>

            <small class="notify-time">
                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
            </small>
        </div>

    </a>

    {{-- BOTÓN ELIMINAR --}}
    <button class="notify-delete" title="Eliminar">
        <i class="fas fa-trash"></i>
    </button>

</div>

@endforeach
