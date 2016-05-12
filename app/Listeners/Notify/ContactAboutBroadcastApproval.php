<?php

namespace App\Listeners\Notify;

use App\Events\BroadcastWasApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactAboutBroadcastApproval
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
     * @param  BroadcastWasApproved  $event
     * @return void
     */
    public function handle(BroadcastWasApproved $event)
    {
        //
    }
}
