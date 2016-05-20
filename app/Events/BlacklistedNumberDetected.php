<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BlacklistedNumberDetected extends Event
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
