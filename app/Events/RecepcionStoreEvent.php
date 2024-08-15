<?php

namespace App\Events;

use App\Solicitud;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RecepcionStoreEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $view;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($view)
    {
        $this->view = $view;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('recepcionStoreEvent');
    }

    public function broadcastAs()
    {
        return 'recepcion-storeEvent';
    }

    public function broadcastWith()
    {
        return ['data' => [
            "view" => $this->view
        ]];
    }
}
