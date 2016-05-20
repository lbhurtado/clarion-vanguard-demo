<?php

namespace App\Listeners\Relay;

use App\Events\WhitelistedNumberNotDetected;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToOthersAboutWhitelistedNumberNonDetection
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
     * @param  WhitelistedNumberNotDetected  $event
     * @return void
     */
    public function handle(WhitelistedNumberNotDetected $event)
    {
        //
    }
}
