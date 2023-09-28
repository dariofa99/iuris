@foreach ($notifications as $key => $notification)
<div class="p-3 mt-1 d-flex align-items-center bg-light border-bottom osahan-post-header">
        <div class="dropdown-list-image mr-3">
            {{-- <img class="rounded-circle"
            src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="" />
            --}} 
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
    {{--     <span class="ml-auto mb-auto">
            <div class="btn-group">
                <button type="button" class="btn btn-success btn-sm rounded" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="mdi mdi-dots-vertical"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <button data-id="{{ $notification->id }}" class="dropdown-item btn_notification_delete"
                        type="button"><i class="mdi mdi-delete"></i>Eliminar</button>
                  
                </div>
            </div>
            <br />
            <div class="text-right text-muted pt-1">

            </div>
        </span> --}}
    </div> 
@endforeach




