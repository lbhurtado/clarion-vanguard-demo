<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Events\Event;

class WhitelistedNumberNotDetected extends Event
{
    use SerializesModels;

    public $attributes;

    public function __construct($attributes)
    {
        $this->attributes = $attributes;
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
