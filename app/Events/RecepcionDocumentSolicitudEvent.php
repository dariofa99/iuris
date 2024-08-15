<?php

namespace App\Events;

use App\Solicitud;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RecepcionDocumentSolicitudEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $solicitud;
    private $view;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Solicitud $solicitud, $view)
    {
        $this->solicitud = $solicitud;
        $this->view = $view;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('recepcionDocumentSolicitud');
    }

    public function broadcastAs()
    {
        return 'recepcion-documentSolicitud';
    }

    public function broadcastWith()
    {
        return ['data' => [
            "token" => $this->solicitud->token,
            "number" => $this->solicitud->number,
            "view" => $this->view
        ]];
    }
}
