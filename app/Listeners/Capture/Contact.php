<?php

namespace App\Listeners\Capture;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\CreateContactFromShortMessage;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ShortMessageWasRecorded;


class Contact
{
    use DispatchesJobs;

    /**
     * Handle the event.
     *
     * @param  ShortMessageWasRecorded  $event
     * @return void
     */
    public function handle(ShortMessageWasRecorded $event)
    {
        $job = new CreateContactFromShortMessage($event->shortMessage);

        $this->dispatch($job);
    }
}
