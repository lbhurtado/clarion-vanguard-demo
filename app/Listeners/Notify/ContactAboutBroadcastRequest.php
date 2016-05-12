<?php

namespace App\Listeners\Notify;

use App\Events\BroadcastWasRequested;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactAboutBroadcastRequest
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
     * @param  BroadcastWasRequested  $event
     * @return void
     */
    public function handle(BroadcastWasRequested $event)
    {
        //
    }
}
