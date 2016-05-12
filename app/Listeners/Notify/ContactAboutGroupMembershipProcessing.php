<?php

namespace App\Listeners\Notify;

use App\Events\GroupMembershipsWereProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactAboutGroupMembershipProcessing
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  GroupMembershipsWereProcessed  $event
     * @return void
     */
    public function handle(GroupMembershipsWereProcessed $event)
    {
        //
    }
}
