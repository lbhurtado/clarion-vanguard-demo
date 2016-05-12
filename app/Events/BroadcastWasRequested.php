<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Entities\ShortMessage;
use App\Events\Event;

class BroadcastWasRequested extends Event
{
    use SerializesModels;

    public $shortMessage;

    /**
     * @param ShortMessage $shortMessage
     */
    public function __construct(ShortMessage $shortMessage)
    {
        $this->shortMessage = $shortMessage;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
