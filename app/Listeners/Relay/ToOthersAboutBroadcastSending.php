<?php

namespace App\Listeners\Relay;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\BroadcastWasSent;

class ToOthersAboutBroadcastSending
{
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
