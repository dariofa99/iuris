<?php

namespace App\Events;

use Facades\App\AuditLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\UserAditionalData as Item;
use Illuminate\Support\Facades\Log;


class UserAditionalDataEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('channel-name');
    }

    public function itemCreated(Item $item)
    {
        AuditLog::setEvent('created')
        ->setModelDescription(json_encode($item))
        ->setTable($item->getTable())
        ->store();
        Log::info("Item Created Event Fire: ".$item);
    }



    /**

     * Get the channels the event should broadcast on.

     *

     * @return \Illuminate\Broadcasting\Channel|array

     */

    public function itemUpdated(Item $item)

    {
        AuditLog::setEvent('updated')
        ->setItem($item)
        ->setModelDescription(json_encode($item->getChanges()))
        ->setTable($item->getTable())
        ->store();
        Log::info("Item Updated Event Fire: ".$item);

    }



    /**

     * Get the channels the event should broadcast on.

     *

     * @return \Illuminate\Broadcasting\Channel|array

     */

    public function itemDeleted(Item $item)

    {
        AuditLog::setEvent('deleted')
        ->setModelDescription(json_encode($item))
        ->setTable($item->getTable())->store();
        Log::info("Item Deleted Event Fire: ".$item);

    }

}
