<?php

namespace App\Listeners\Notify;

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Events\GroupMembershipsWereProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactAboutGroupMembershipProcessing
{
    use DispatchesJobs;

    /**
     * Handle the event.
     *
     * @param  GroupMembershipsWereProcessed  $event
     * @return void
     */
    public function handle(GroupMembershipsWereProcessed $event)
    {
        $mobile = $event->shortMessage->from;
        $message = "Thank you.";
        $job = new SendShortMessage($mobile, $message);

        $this->dispatch($job);
    }
}
