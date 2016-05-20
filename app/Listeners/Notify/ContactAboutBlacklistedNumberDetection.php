<?php

namespace App\Listeners\Notify;

use App\Events\BlacklistedNumberDetected;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactAboutBlacklistedNumberDetection
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
