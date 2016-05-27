<?php

namespace App\Listeners\Relay;

use App\Events\SubscriptionMembershipsWereProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToOthersAboutSubscriptionMembershipProcessing
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
     * @param  SubscriptionMembershipsWereProcessed  $event
     * @return void
     */
    public function handle(SubscriptionMembershipsWereProcessed $event)
    {
        //
    }
}
