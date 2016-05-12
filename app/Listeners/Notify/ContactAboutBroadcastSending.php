<?php

namespace App\Listeners\Notify;

use App\Events\BroadcastWasSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactAboutBroadcastSending
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
     * @param  BroadcastWasSent  $event
     * @return void
     */
    public function handle(BroadcastWasSent $event)
    {
        //
    }
}
