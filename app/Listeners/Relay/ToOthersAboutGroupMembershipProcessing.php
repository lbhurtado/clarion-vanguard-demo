<?php

namespace App\Listeners\Relay;

use App\Events\GroupMembershipsWereProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToOthersAboutGroupMembershipProcessing
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
