<?php

namespace App\Listeners\Notify;

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Events\GroupMembershipsWereProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Repositories\ContactRepository;
use App\Jobs\SendShortMessage;


class ContactAboutGroupMembershipProcessing
{
    use DispatchesJobs;

    private $contacts;

    /**
     * ContactAboutGroupMembershipProcessing constructor.
     * @param $contacts
     */
    public function __construct(ContactRepository $contacts)
    {
        $this->contacts = $contacts;
    }


    /**
     * Handle the event.
     *
     * @param  GroupMembershipsWereProcessed  $event
     * @return void
     */
    public function handle(GroupMembershipsWereProcessed $event)
    {
        $mobile = $event->contact->mobile;

        $handle = $this->contacts->findByField('mobile', $mobile)->first()->handle;
        $message = $event->new
            ? "Thank you, Vgd. {$handle}. You are now subscribed to our messaging system, sir."
            : "Sir, your handle was changed to {$handle}.";

        $job = new SendShortMessage($mobile, $message);

        $this->dispatch($job);
    }
}
