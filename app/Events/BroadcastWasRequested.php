<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Entities\ShortMessage;
use App\Entities\Group;
use App\Events\Event;

class BroadcastWasRequested extends Event
{
    use SerializesModels;

    public $group;

    public $message;

    public $origin;

    public $token;

    /**
     * BroadcastWasRequested constructor.
     * @param Group $group
     * @param $message
     * @param $origin
     * @param $token
     */
    public function __construct(Group $group, $message, $origin, $token)
    {
        $this->group = $group;
        $this->message = $message;
        $this->origin = $origin;
        $this->token = $token;
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
