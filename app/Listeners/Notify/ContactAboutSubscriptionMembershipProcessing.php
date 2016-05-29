<?php

namespace App\Listeners\Notify;

use App\Events\SubscriptionMembershipsWereProcessed;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ContactAboutSubscriptionMembershipProcessing
{
    use DispatchesJobs;

    /**
     * Handle the event.
     *
     * @param  SubscriptionMembershipsWereProcessed  $event
     * @return void
     */
    public function handle(SubscriptionMembershipsWereProcessed $event)
    {
//        $job = new SendShortMessage($this->mobile, $info->description);
//        $this->dispatch($job);
    }
}
