<?php

namespace App\Events;

use App\Solicitud;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SolicitudEstadosCambio implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $solicitud;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Solicitud $solicitud)
    {
        $this->solicitud = $solicitud;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('cambiosEstadoSolicitud');
    }

    public function broadcastAs()
    {
        return 'solicitud-estados';
    }

    public function broadcastWith()
    {
        return ['data' => [
            "token" => $this->solicitud->token,
            "type_status_id" => $this->solicitud->type_status_id
        ]];
    }
}
