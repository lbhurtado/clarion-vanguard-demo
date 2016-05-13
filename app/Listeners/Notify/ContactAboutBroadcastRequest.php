<?php

namespace App\Listeners\Notify;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\BroadcastWasRequested;
use App\Jobs\SendShortMessage;

class ContactAboutBroadcastRequest
{
    use DispatchesJobs;

    /**
     * Handle the event.
     *
     * @param  BroadcastWasRequested  $event
     * @return void
     */
    public function handle(BroadcastWasRequested $event)
    {
        $message = "Your message {$event->message} to group {$event->group->name} is pending.";
        $job = new SendShortMessage($event->origin, $message);

        $this->dispatch($job);
    }
}
