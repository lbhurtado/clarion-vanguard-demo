<?php

namespace App\Listeners\Relay;

use App\Events\ContactWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToOthersAboutContactCreation
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
     * @param  ContactWasCreated  $event
     * @return void
     */
    public function handle(ContactWasCreated $event)
    {
        //
    }
}
