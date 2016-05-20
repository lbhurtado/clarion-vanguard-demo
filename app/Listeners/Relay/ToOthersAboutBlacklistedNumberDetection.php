<?php

namespace App\Listeners\Relay;

use App\Events\BlacklistedNumberDetected;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToOthersAboutBlacklistedNumberDetection
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
     * @param  BlacklistedNumberDetected  $event
     * @return void
     */
    public function handle(BlacklistedNumberDetected $event)
    {
        //
    }
}
