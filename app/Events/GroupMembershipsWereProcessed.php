<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Entities\ShortMessage;
use App\Entities\Contact;
use App\Entities\Group;
use App\Events\Event;

class GroupMembershipsWereProcessed extends Event
{
    use SerializesModels;

    public $group;

    public $contact;

    public $new;

    /**
     * @param Group $group
     * @param Contact $contact
     * @param bool|true $new
     */
    public function __construct(Group $group, Contact $contact, $new = true)
    {
        $this->group = $group;
        $this->contact = $contact;
        $this->new = $new;
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
