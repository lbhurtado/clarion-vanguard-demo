<?php

namespace App\Listeners\Capture;

use App\Events\ShortMessageWasRecorded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GroupMembership
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
     * @param  ShortMessageWasRecorded  $event
     * @return void
     */
    public function handle(ShortMessageWasRecorded $event)
    {
        //
    }
}
