<?php

namespace App\Listeners\Notify;

use App\Events\ContactWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactAboutContactCreation
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
